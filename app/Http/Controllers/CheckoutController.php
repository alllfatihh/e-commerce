<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function process(Request $request)
    {
        if (auth()->check()) {
            $pendingOrder = Order::where('user_id', auth()->id())->where('status', 'pending')->latest()->first();
            if ($pendingOrder) {
                // Jika order pending sudah lebih dari 2 jam, otomatis batalkan dan kembalikan stok
                if ($pendingOrder->created_at && $pendingOrder->created_at->diffInHours(now()) >= 2) {
                    $pendingOrder->update(['status' => 'cancelled']);
                    foreach ($pendingOrder->items as $pItem) {
                        if ($pItem->product_id) {
                            ProductStock::where('product_id', $pItem->product_id)
                                ->where('size', $pItem->size)
                                ->increment('stock', $pItem->quantity);
                        }
                    }
                } else {
                    return response()->json([
                        'error' => 'Anda masih memiliki transaksi yang belum diselesaikan.',
                        'pending_order' => true,
                        'order_id' => $pendingOrder->id,
                        'snap_token' => $pendingOrder->snap_token,
                    ], 403);
                }
            }
        }

        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'destination_area_id' => 'required|string',
            'items' => 'required|array|min:1',
            'shipping_cost' => 'required|numeric|min:0',
            'courier_name' => 'required|string',
            'promo_code' => 'nullable|string',
        ]);

        // Verifikasi harga asli dari database untuk mencegah price tampering dari client
        $subtotal = 0;
        $verifiedItems = [];
        foreach ($validated['items'] as $item) {
            $productId = $item['id'] ?? null;
            $size = $item['size'] ?? 'All Size';
            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            $product = $productId ? Product::find($productId) : null;
            $unitPrice = $product ? (int) $product->price : (int) ($item['price'] ?? 0);
            $productName = $product ? $product->name : ($item['name'] ?? 'Product');

            $subtotal += ($unitPrice * $quantity);

            $verifiedItems[] = [
                'id' => $productId,
                'name' => $productName,
                'price' => $unitPrice,
                'quantity' => $quantity,
                'size' => $size,
            ];
        }

        // Kalkulasi diskon promo code secara dinamis dari database
        $discountAmount = 0;
        $appliedDiscount = null;
        $promoCode = strtoupper(trim($request->input('promo_code', '')));
        if ($promoCode) {
            $discountModel = DiscountCode::where('code', $promoCode)
                ->where('is_active', true)
                ->first();

            if ($discountModel && $discountModel->isValidForSubtotal($subtotal)) {
                $discountAmount = $discountModel->calculateDiscount($subtotal);
                $appliedDiscount = $discountModel;
            } elseif ($promoCode === 'NOTISSE' || $promoCode === 'NOTISSE10') {
                // Backward-compatible fallback
                $discountAmount = (int) round($subtotal * 0.10);
            } elseif ($promoCode === 'NOTISSE50') {
                $discountAmount = (int) round($subtotal * 0.50);
            }
        }

        // Anti-Shipping Cost Tampering: Validasi batas bawah ongkir secara server-side
        $shippingCost = (int) $validated['shipping_cost'];
        $minZoneRate = $this->getMinimumShippingRate($validated['destination_area_id'], $verifiedItems);
        if ($shippingCost < $minZoneRate) {
            $shippingCost = $minZoneRate;
        }

        $totalAmount = max(10000, $subtotal - $discountAmount + $shippingCost);

        // ATOMIC INVENTORY CONCURRENCY LOCK & TRANSACTION
        try {
            $order = DB::transaction(function () use ($validated, $verifiedItems, $subtotal, $shippingCost, $discountAmount, $totalAmount, $appliedDiscount) {
                // 1. Pessimistic Locking: Kunci dan verifikasi stok secara atomik
                foreach ($verifiedItems as $item) {
                    $productId = $item['id'];
                    $size = $item['size'];

                    if ($productId) {
                        $stockRecord = ProductStock::where('product_id', $productId)
                            ->where('size', $size)
                            ->lockForUpdate()
                            ->first();

                        if (! $stockRecord || $stockRecord->stock < $item['quantity']) {
                            throw new \Exception('Maaf, stok produk "'.$item['name'].'" ('.$size.') tidak mencukupi.');
                        }
                    }
                }

                // 2. Buat Order dengan rincian finansial lengkap (Subtotal, Ongkir, Diskon)
                $createdOrder = Order::create([
                    'user_id' => auth()->id(),
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'destination_area_id' => $validated['destination_area_id'] ?? null,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'courier_name' => $validated['courier_name'],
                ]);

                // 3. Catat OrderItems dan kurangi stok secara atomik
                foreach ($verifiedItems as $item) {
                    $productId = $item['id'];
                    $size = $item['size'];

                    OrderItem::create([
                        'order_id' => $createdOrder->id,
                        'product_id' => $productId,
                        'product_name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'size' => $size,
                    ]);

                    if ($productId) {
                        ProductStock::where('product_id', $productId)
                            ->where('size', $size)
                            ->decrement('stock', $item['quantity']);
                    }
                }

                // 4. Catat penggunaan kode promo
                if ($appliedDiscount) {
                    $appliedDiscount->increment('used_count');
                }

                return $createdOrder;
            });
        } catch (\Exception $e) {
            Log::warning('Checkout order/stock transaction aborted: '.$e->getMessage());

            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }

        // Konfigurasi Midtrans
        $midtransOrderId = $order->id.'-'.time();
        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $validated['customer_name'],
                'email' => $validated['customer_email'],
                'phone' => $validated['customer_phone'],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update([
                'midtrans_order_id' => $midtransOrderId,
                'snap_token' => $snapToken,
            ]);

            // Bersihkan cart di database jika user login
            if (auth()->check()) {
                $userCart = Cart::where('user_id', auth()->id())->first();
                if ($userCart) {
                    $userCart->items()->delete();
                }
            }

            return response()->json(['snap_token' => $snapToken, 'order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Checkout Error: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchArea(Request $request)
    {
        $keyword = $request->query('keyword');
        if (! $keyword) {
            return response()->json(['areas' => []]);
        }

        $response = Http::withHeaders([
            'Authorization' => env('BITESHIP_API_KEY'),
        ])->get('https://api.biteship.com/v1/maps/areas', [
            'countries' => 'ID',
            'input' => $keyword,
            'type' => 'single',
        ]);

        return response()->json($response->json());
    }

    public function getShippingRates(Request $request)
    {
        try {
            $validated = $request->validate([
                'destination_area_id' => 'required|string',
                'items' => 'required|array',
            ]);

            $biteshipItems = array_map(function ($item) {
                return [
                    'name' => $item['name'] ?? 'Item',
                    'description' => $item['name'] ?? 'Apparel Notisse',
                    'value' => $item['price'] ?? 100000,
                    'length' => 20,
                    'width' => 20,
                    'height' => 5,
                    'weight' => 200, // asumsikan 200gram per baju
                    'quantity' => $item['quantity'] ?? 1,
                ];
            }, $validated['items']);

            $response = Http::withHeaders([
                'Authorization' => env('BITESHIP_API_KEY'),
            ])->post('https://api.biteship.com/v1/rates/couriers', [
                'origin_area_id' => env('STORE_ORIGIN_AREA_ID', 'IDNP9IDNC22IDND2044IDZ40353'),
                'destination_area_id' => $validated['destination_area_id'],
                'couriers' => 'jne,sicepat,jnt,anteraja',
                'items' => $biteshipItems,
            ]);

            $data = $response->json();

            // Live Biteship API Check
            if ($response->successful() && ! empty($data['pricing']) && (! isset($data['success']) || $data['success'] !== false)) {
                return response()->json($data);
            }

            // Log info jika Biteship API rate tidak mengembalikan pricing (misal saldo habis / akun test)
            Log::info('Biteship rates API fallback triggered: '.json_encode($data));

            // Gunakan Kalkulasi Fallback Cerdas Berbasis Zona & Berat Destinasi
            $fallbackPricing = $this->getZoneFallbackRates($validated['destination_area_id'], $biteshipItems);

            return response()->json($fallbackPricing);
        } catch (\Exception $e) {
            Log::error('getShippingRates error: '.$e->getMessage());
            $fallbackPricing = $this->getZoneFallbackRates($request->input('destination_area_id', ''), $request->input('items', []));

            return response()->json($fallbackPricing);
        }
    }

    /**
     * Fallback tarif cerdas berdasarkan lokasi provinsi & berat jika saldo Biteship belum di-topup
     */
    private function getZoneFallbackRates(string $destinationAreaId, array $items): array
    {
        // 1. Hitung total berat dalam kilogram
        $totalGrams = 0;
        foreach ($items as $item) {
            $qty = $item['quantity'] ?? 1;
            $weight = $item['weight'] ?? 200;
            $totalGrams += ($weight * $qty);
        }
        $weightKg = max(1, (int) ceil($totalGrams / 1000));

        // 2. Ambil detail lokasi dari Biteship Maps API (Free endpoint)
        $province = '';
        $city = '';
        if ($destinationAreaId) {
            try {
                $areaRes = Http::withHeaders([
                    'Authorization' => env('BITESHIP_API_KEY'),
                ])->timeout(3)->get("https://api.biteship.com/v1/maps/areas/{$destinationAreaId}");

                if ($areaRes->successful()) {
                    $areaData = $areaRes->json();
                    if (! empty($areaData['areas'][0])) {
                        $province = strtolower($areaData['areas'][0]['administrative_division_level_1_name'] ?? '');
                        $city = strtolower($areaData['areas'][0]['administrative_division_level_2_name'] ?? '');
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Failed area lookup for zone fallback: '.$e->getMessage());
            }
        }

        // 3. Tentukan tarif dasar per kg dari gudang asal (Dayeuhkolot, Bandung)
        $jneBase = 12000;
        $sicepatBase = 11000;
        $jntBase = 13000;
        $duration = '1 - 3 hari';

        if (str_contains($city, 'bandung') || str_contains($city, 'cimahi')) {
            // Bandung Raya (Lokal)
            $jneBase = 10000;
            $sicepatBase = 9000;
            $jntBase = 10000;
            $duration = '1 - 2 hari';
        } elseif (str_contains($province, 'jakarta') || str_contains($province, 'banten') || str_contains($province, 'jawa barat')) {
            // DKI Jakarta, Banten, Jawa Barat
            $jneBase = 12000;
            $sicepatBase = 11000;
            $jntBase = 13000;
            $duration = '1 - 3 hari';
        } elseif (str_contains($province, 'jawa tengah') || str_contains($province, 'jawa timur') || str_contains($province, 'yogyakarta')) {
            // Jawa Tengah, DIY, Jawa Timur
            $jneBase = 19000;
            $sicepatBase = 18000;
            $jntBase = 20000;
            $duration = '2 - 3 hari';
        } elseif (str_contains($province, 'bali') || str_contains($province, 'nusa tenggara barat') || str_contains($province, 'lampung') || str_contains($province, 'sumatera selatan')) {
            // Bali, NTB, Lampung, Sumsel
            $jneBase = 28000;
            $sicepatBase = 26000;
            $jntBase = 30000;
            $duration = '2 - 4 hari';
        } elseif (str_contains($province, 'sumatera') || str_contains($province, 'riau') || str_contains($province, 'jambi') || str_contains($province, 'bengkulu') || str_contains($province, 'aceh') || str_contains($province, 'bangka') || str_contains($province, 'kalimantan')) {
            // Sumatera lainnya & Kalimantan
            $jneBase = 38000;
            $sicepatBase = 36000;
            $jntBase = 40000;
            $duration = '3 - 5 hari';
        } elseif (str_contains($province, 'sulawesi') || str_contains($province, 'nusa tenggara timur') || str_contains($province, 'gorontalo')) {
            // Sulawesi & NTT
            $jneBase = 46000;
            $sicepatBase = 44000;
            $jntBase = 48000;
            $duration = '3 - 6 hari';
        } elseif (str_contains($province, 'papua') || str_contains($province, 'maluku')) {
            // Maluku & Papua
            $jneBase = 95000;
            $sicepatBase = 90000;
            $jntBase = 100000;
            $duration = '4 - 8 hari';
        }

        return [
            'success' => true,
            'pricing' => [
                [
                    'courier_name' => 'SiCepat',
                    'courier_service_name' => 'REG',
                    'duration' => $duration,
                    'price' => $sicepatBase * $weightKg,
                ],
                [
                    'courier_name' => 'JNE',
                    'courier_service_name' => 'REG',
                    'duration' => $duration,
                    'price' => $jneBase * $weightKg,
                ],
                [
                    'courier_name' => 'J&T',
                    'courier_service_name' => 'EZ',
                    'duration' => $duration,
                    'price' => $jntBase * $weightKg,
                ],
            ],
        ];
    }

    /**
     * Dapatkan tarif batas bawah ongkir secara server-side untuk mencegah tampering
     */
    private function getMinimumShippingRate(string $destinationAreaId, array $items): int
    {
        $rates = $this->getZoneFallbackRates($destinationAreaId, $items);
        $minPrice = 9000;
        if (! empty($rates['pricing'])) {
            $prices = array_column($rates['pricing'], 'price');
            if (! empty($prices)) {
                $minPrice = (int) min($prices);
            }
        }

        return $minPrice;
    }

    public function webhook(Request $request)
    {
        try {
            $notif = new Notification;

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = explode('-', $notif->order_id)[0];
            $fraud = $notif->fraud_status;

            // Verifikasi Signature Key Resmi Midtrans untuk mencegah manipulasi data
            $serverKey = env('MIDTRANS_SERVER_KEY');
            if ($serverKey && ! empty($notif->signature_key)) {
                $signatureKey = hash('sha512', $notif->order_id.$notif->status_code.$notif->gross_amount.$serverKey);
                if ($notif->signature_key !== $signatureKey) {
                    Log::warning('Midtrans webhook invalid signature: '.$notif->order_id);

                    return response()->json(['message' => 'Invalid signature'], 403);
                }
            }

            $order = Order::where('midtrans_order_id', $notif->order_id)->first() ?? Order::find($order_id);
            if (! $order) {
                return response()->json('Order not found', 404);
            }

            $oldStatus = $order->status;
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $order->status = 'challenge';
                    } else {
                        $order->status = 'paid';
                    }
                }
            } elseif ($transaction == 'settlement') {
                $order->status = 'paid';
            } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                $order->status = 'cancelled';
            }

            if (! empty($notif->transaction_id)) {
                $order->midtrans_transaction_id = $notif->transaction_id;
            }

            if ($oldStatus !== 'cancelled' && $order->status === 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        ProductStock::where('product_id', $item->product_id)
                            ->where('size', $item->size)
                            ->increment('stock', $item->quantity);
                    }
                }
            }

            $order->save();

            // Auto dispatch Biteship courier order when payment is confirmed (paid)
            if ($order->status === 'paid' && $oldStatus !== 'paid') {
                $this->dispatchBiteshipOrder($order);
            }

            return response()->json(['status' => 'success', 'order_status' => $order->status]);
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Exception: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Dispatch order to Biteship courier API upon confirmed payment
     */
    public function dispatchBiteshipOrder(Order $order): ?array
    {
        // Don't duplicate if already dispatched to live Biteship
        if ($order->biteship_order_id && ! str_starts_with($order->biteship_order_id, 'BS-MANUAL') && ! str_starts_with($order->biteship_order_id, 'BS-')) {
            return ['success' => true, 'already_dispatched' => true, 'id' => $order->biteship_order_id];
        }

        $apiKey = env('BITESHIP_API_KEY');
        if (! $apiKey) {
            Log::warning('dispatchBiteshipOrder skipped: BITESHIP_API_KEY is not configured');

            return null;
        }

        $order->loadMissing('items.product');

        $biteshipItems = [];
        foreach ($order->items as $item) {
            $biteshipItems[] = [
                'name' => $item->product_name ?? 'Produk Notisse',
                'description' => ($item->product_name ?? 'Produk Notisse').($item->size ? ' ('.$item->size.')' : ''),
                'value' => (int) $item->price,
                'length' => 20,
                'width' => 20,
                'height' => 5,
                'weight' => 200,
                'quantity' => (int) $item->quantity,
            ];
        }

        if (empty($biteshipItems)) {
            $biteshipItems[] = [
                'name' => 'Apparel Notisse',
                'description' => 'Apparel Notisse',
                'value' => (int) $order->total_amount,
                'length' => 20,
                'width' => 20,
                'height' => 5,
                'weight' => 200,
                'quantity' => 1,
            ];
        }

        $courierParts = explode(' ', $order->courier_name ?? 'JNE REG');
        $courierCompany = strtolower($courierParts[0] ?? 'jne');
        if ($courierCompany === 'j&t') {
            $courierCompany = 'jnt';
        }
        $courierType = strtolower($courierParts[1] ?? 'reg');

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->timeout(8)->post('https://api.biteship.com/v1/orders', [
                'shipper_contact_name' => 'Notisse Admin',
                'shipper_contact_phone' => '081234567890',
                'shipper_contact_email' => 'admin@notisse.com',
                'shipper_organization' => 'Notisse',
                'origin_contact_name' => 'Notisse Admin',
                'origin_contact_phone' => '081234567890',
                'origin_address' => env('STORE_ORIGIN_ADDRESS', 'Jl. Sukapura No.62, Dayeuhkolot'),
                'origin_area_id' => env('STORE_ORIGIN_AREA_ID', 'IDNP9IDNC22IDND2044IDZ40353'),
                'destination_contact_name' => $order->customer_name,
                'destination_contact_phone' => $order->customer_phone,
                'destination_contact_email' => $order->customer_email,
                'destination_address' => $order->shipping_address,
                'destination_area_id' => $order->destination_area_id,
                'courier_company' => $courierCompany,
                'courier_type' => $courierType,
                'delivery_type' => 'now',
                'items' => $biteshipItems,
            ]);

            $biteshipData = $response->json();
            Log::info('Biteship Dispatch Order Response for #'.$order->id.':', (array) $biteshipData);

            if (isset($biteshipData['id'])) {
                $updateData = ['biteship_order_id' => $biteshipData['id']];
                if (! empty($biteshipData['courier']['waybill_id'])) {
                    $updateData['tracking_number'] = $biteshipData['courier']['waybill_id'];
                } elseif (! empty($biteshipData['courier']['tracking_id'])) {
                    $updateData['tracking_number'] = $biteshipData['courier']['tracking_id'];
                }
                $order->update($updateData);

                return $biteshipData;
            }
        } catch (\Exception $e) {
            Log::warning('dispatchBiteshipOrder exception for #'.$order->id.': '.$e->getMessage());
        }

        return null;
    }

    public function checkPromoCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $subtotal = (float) $request->input('subtotal');

        $discount = DiscountCode::where('code', $code)->first();

        if (! $discount) {
            if ($code === 'NOTISSE' || $code === 'NOTISSE10') {
                $amt = (int) round($subtotal * 0.10);

                return response()->json([
                    'success' => true,
                    'code' => $code,
                    'type' => 'percentage',
                    'value' => 10,
                    'discount_amount' => $amt,
                    'message' => 'Kode promo 10% berhasil diterapkan.',
                ]);
            }
            if ($code === 'NOTISSE50') {
                $amt = (int) round($subtotal * 0.50);

                return response()->json([
                    'success' => true,
                    'code' => $code,
                    'type' => 'percentage',
                    'value' => 50,
                    'discount_amount' => $amt,
                    'message' => 'Kode promo 50% berhasil diterapkan.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Kode diskon tidak valid atau tidak ditemukan.',
            ], 422);
        }

        $reason = $discount->getInvalidReason($subtotal);
        if ($reason !== null) {
            return response()->json([
                'success' => false,
                'message' => $reason,
            ], 422);
        }

        $amount = $discount->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'code' => $discount->code,
            'description' => $discount->description,
            'type' => $discount->type,
            'value' => $discount->value,
            'discount_amount' => $amount,
            'message' => 'Kode diskon '.$discount->code.' berhasil diterapkan!',
        ]);
    }
}
