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
            $unitPrice = $product ? (int) $product->effective_price : (int) ($item['price'] ?? 0);
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

            if ($discountModel) {
                $discountReason = null;
                $eligibleSubtotal = 0;
                $calculated = $discountModel->calculateDiscountForItems($verifiedItems, $discountReason, $eligibleSubtotal);
                if ($discountReason === null && $calculated > 0) {
                    $discountAmount = $calculated;
                    $appliedDiscount = $discountModel;
                }
            } elseif ($promoCode === 'NOTISSE' || $promoCode === 'NOTISSE10') {
                $eligibleSubtotal = 0;
                foreach ($verifiedItems as $vItem) {
                    $p = $vItem['id'] ? Product::find($vItem['id']) : null;
                    if (! ($p && $p->hasDiscount())) {
                        $eligibleSubtotal += ($vItem['price'] * $vItem['quantity']);
                    }
                }
                if ($eligibleSubtotal > 0) {
                    $discountAmount = (int) round($eligibleSubtotal * 0.10);
                }
            } elseif ($promoCode === 'NOTISSE50') {
                $eligibleSubtotal = 0;
                foreach ($verifiedItems as $vItem) {
                    $p = $vItem['id'] ? Product::find($vItem['id']) : null;
                    if (! ($p && $p->hasDiscount())) {
                        $eligibleSubtotal += ($vItem['price'] * $vItem['quantity']);
                    }
                }
                if ($eligibleSubtotal > 0) {
                    $discountAmount = (int) round($eligibleSubtotal * 0.50);
                }
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
            $snapToken = app()->environment('testing') ? 'mock_snap_token_testing' : Snap::getSnapToken($params);
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
        $keyword = trim((string) $request->query('keyword', ''));
        if (strlen($keyword) < 2) {
            return response()->json(['areas' => []]);
        }

        $biteshipKey = env('BITESHIP_API_KEY');

        // Jika API Key Biteship tersedia dan valid (bukan placeholder), coba query live Biteship Maps API
        if ($biteshipKey && ! str_contains($biteshipKey, 'YOUR_BITESHIP_KEY')) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(4)
                    ->withHeaders([
                        'Authorization' => $biteshipKey,
                    ])->get('https://api.biteship.com/v1/maps/areas', [
                        'countries' => 'ID',
                        'input' => $keyword,
                        'type' => 'single',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (! empty($data['areas'])) {
                        return response()->json($data);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Biteship searchArea live lookup error: '.$e->getMessage());
            }
        }

        // Fallback cerdas: cari dari daftar lengkap kecamatan & kota di Indonesia
        $fallbackAreas = $this->getFallbackAreas($keyword);

        return response()->json([
            'success' => true,
            'areas' => $fallbackAreas,
        ]);
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

            $response = Http::withoutVerifying()->withHeaders([
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
                $areaRes = Http::withoutVerifying()->withHeaders([
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

            // Fallback nama provinsi dan kota dari data internal jika API tidak merespons
            if (empty($province)) {
                $fallback = $this->findFallbackAreaById($destinationAreaId);
                if ($fallback) {
                    $province = strtolower($fallback['administrative_division_level_1_name'] ?? '');
                    $city = strtolower($fallback['administrative_division_level_2_name'] ?? '');
                }
            }
        }

        // 3. Tentukan tarif dasar per kg dari gudang asal (Dayeuhkolot, Bandung)
        $jneBase = 12000;
        $sicepatBase = 11000;
        $jntBase = 13000;
        $duration = '1 - 3 hari';

        if (str_contains($destinationAreaId, 'IDNC22') || str_contains($destinationAreaId, 'IDNC23') || str_contains($city, 'bandung') || str_contains($city, 'cimahi')) {
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
            $response = Http::withoutVerifying()->withHeaders([
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
                $updateData = [
                    'biteship_order_id' => $biteshipData['id'],
                    'biteship_status' => strtolower($biteshipData['status'] ?? 'confirmed'),
                    'biteship_tracking_link' => $biteshipData['courier']['link'] ?? null,
                ];
                if (! empty($biteshipData['courier']['waybill_id'])) {
                    $updateData['tracking_number'] = $biteshipData['courier']['waybill_id'];
                } elseif (! empty($biteshipData['courier']['tracking_id'])) {
                    $updateData['tracking_number'] = $biteshipData['courier']['tracking_id'];
                }
                if ($order->status === 'paid') {
                    $updateData['status'] = 'processing';
                }
                $order->update($updateData);

                return $biteshipData;
            }
        } catch (\Exception $e) {
            Log::warning('dispatchBiteshipOrder exception for #'.$order->id.': '.$e->getMessage());
        }

        return null;
    }

    /**
     * Synchronize order tracking & status directly from Biteship API
     */
    public function syncBiteshipOrderStatus(Order $order): ?array
    {
        if (! $order->biteship_order_id || str_starts_with($order->biteship_order_id, 'BS-MANUAL')) {
            return null;
        }

        $apiKey = env('BITESHIP_API_KEY');
        if (! $apiKey) {
            return null;
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => $apiKey,
            ])->timeout(8)->get("https://api.biteship.com/v1/orders/{$order->biteship_order_id}");

            if (! $response->successful()) {
                Log::warning("Failed to sync Biteship order #{$order->id}: ".$response->body());

                return null;
            }

            $data = $response->json();
            $biteshipStatus = strtolower($data['status'] ?? '');
            $waybill = $data['courier']['waybill_id'] ?? null;
            $trackingId = $data['courier']['tracking_id'] ?? null;
            $link = $data['courier']['link'] ?? null;

            $updateData = [];

            if ($biteshipStatus) {
                $updateData['biteship_status'] = $biteshipStatus;
            }

            // Always update tracking_number to latest courier waybill (AWB) or tracking ID
            if (! empty($waybill)) {
                $updateData['tracking_number'] = $waybill;
            } elseif (! empty($trackingId) && empty($order->tracking_number)) {
                $updateData['tracking_number'] = $trackingId;
            }

            if (! empty($link)) {
                $updateData['biteship_tracking_link'] = $link;
            }

            // Map Biteship status to main Order status (unless buyer already completed receipt)
            if ($order->status !== 'completed' && $biteshipStatus) {
                if (in_array($biteshipStatus, ['confirmed', 'allocated', 'placed', 'scheduled'])) {
                    if (in_array($order->status, ['pending', 'paid'])) {
                        $updateData['status'] = 'processing';
                    }
                } elseif (in_array($biteshipStatus, ['picking_up', 'picked', 'in_transit', 'dropping_off'])) {
                    $updateData['status'] = 'shipped';
                } elseif ($biteshipStatus === 'delivered') {
                    $updateData['status'] = 'delivered';
                } elseif (in_array($biteshipStatus, ['cancelled', 'rejected', 'courier_not_found'])) {
                    $updateData['status'] = 'cancelled';
                }
            }

            if (! empty($updateData)) {
                $order->update($updateData);
            }

            return $data;
        } catch (\Exception $e) {
            Log::error("syncBiteshipOrderStatus exception for #{$order->id}: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Handle incoming webhook updates from Biteship
     */
    public function biteshipWebhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Biteship Webhook Received:', $payload);

        $orderId = $request->input('order_id') ?? $request->input('id');
        if (! $orderId) {
            return response()->json(['status' => 'error', 'message' => 'Missing order_id'], 400);
        }

        $order = Order::where('biteship_order_id', $orderId)->first();
        if (! $order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $result = $this->syncBiteshipOrderStatus($order);

        return response()->json([
            'status' => 'success',
            'order_id' => $order->id,
            'biteship_status' => $order->fresh()->biteship_status,
            'order_status' => $order->fresh()->status,
            'tracking_number' => $order->fresh()->tracking_number,
        ]);
    }

    public function checkPromoCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
            'items' => 'nullable|array',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $subtotal = (float) $request->input('subtotal');
        $items = $request->input('items');

        if (! $items || ! is_array($items) || count($items) === 0) {
            $userId = auth()->id();
            $sessionId = $request->cookie('notisse_cart_session');
            $cart = null;
            if ($userId) {
                $cart = Cart::where('user_id', $userId)->first();
            } elseif ($sessionId) {
                $cart = Cart::where('session_id', $sessionId)->first();
            }
            if ($cart) {
                $items = $cart->items->map(function ($ci) {
                    return [
                        'id' => $ci->product_id,
                        'quantity' => $ci->quantity,
                        'price' => $ci->product ? (float) $ci->product->effective_price : 0,
                    ];
                })->toArray();
            }
        }

        $discount = DiscountCode::where('code', $code)->first();

        if (! $discount) {
            if ($code === 'NOTISSE' || $code === 'NOTISSE10' || $code === 'NOTISSE50') {
                $pct = ($code === 'NOTISSE50') ? 50 : 10;
                $eligibleSubtotal = $subtotal;

                if (! empty($items)) {
                    $eligibleSubtotal = 0;
                    $hasDiscounted = false;
                    foreach ($items as $it) {
                        $p = ! empty($it['id']) ? Product::find($it['id']) : null;
                        if ($p && $p->hasDiscount()) {
                            $hasDiscounted = true;

                            continue;
                        }
                        $pPrice = $p ? (float) $p->effective_price : (float) ($it['price'] ?? 0);
                        $pQty = max(1, (int) ($it['quantity'] ?? $it['qty'] ?? 1));
                        $eligibleSubtotal += ($pPrice * $pQty);
                    }

                    if ($eligibleSubtotal <= 0 && $hasDiscounted) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Kode diskon/potongan harga tidak dapat digunakan pada produk yang sudah diskon.',
                        ], 422);
                    }
                }

                $amt = (int) round($eligibleSubtotal * ($pct / 100));

                return response()->json([
                    'success' => true,
                    'code' => $code,
                    'type' => 'percentage',
                    'value' => $pct,
                    'discount_amount' => $amt,
                    'message' => 'Kode promo '.$pct.'% berhasil diterapkan.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Kode diskon tidak valid atau tidak ditemukan.',
            ], 422);
        }

        if (! empty($items)) {
            $reason = null;
            $eligibleSubtotal = 0;
            $amount = $discount->calculateDiscountForItems($items, $reason, $eligibleSubtotal);

            if ($reason !== null) {
                return response()->json([
                    'success' => false,
                    'message' => $reason,
                ], 422);
            }
        } else {
            $reason = $discount->getInvalidReason($subtotal);
            if ($reason !== null) {
                return response()->json([
                    'success' => false,
                    'message' => $reason,
                ], 422);
            }
            $amount = $discount->calculateDiscount($subtotal);
        }

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

    /**
     * Fallback cerdas: Daftar lengkap kecamatan & kota di Indonesia jika Biteship API offline / key test
     */
    private function getFallbackAreas(string $keyword): array
    {
        $keyword = strtolower(trim($keyword));
        $allAreas = $this->getAllFallbackAreasData();

        // Cari berdasarkan kecocokan nama, kota/kabupaten, provinsi, kecamatan, atau kode pos
        $results = array_filter($allAreas, function ($area) use ($keyword) {
            $searchable = strtolower(
                $area['name'].' '.
                $area['administrative_division_level_1_name'].' '.
                $area['administrative_division_level_2_name'].' '.
                ($area['administrative_division_level_3_name'] ?? '').' '.
                ($area['postal_code'] ?? '')
            );

            return str_contains($searchable, $keyword);
        });

        // Jika keyword >= 3 huruf namun tidak ada di daftar statis, sediakan entri dinamis
        // agar user tidak pernah terblokir saat checkout atau simpan alamat
        if (empty($results) && strlen($keyword) >= 3) {
            $cleanName = ucwords($keyword);
            $results[] = [
                'id' => 'IDNP-GEN-'.strtoupper(substr(md5($keyword), 0, 8)),
                'name' => $cleanName,
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota '.$cleanName,
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => $cleanName,
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 40000,
            ];
        }

        return array_values($results);
    }

    /**
     * Cari area dari fallback dataset berdasarkan Area ID
     */
    private function findFallbackAreaById(string $areaId): ?array
    {
        foreach ($this->getAllFallbackAreasData() as $area) {
            if ($area['id'] === $areaId) {
                return $area;
            }
        }

        return null;
    }

    /**
     * Dataset komprehensif kecamatan dan kota utama se-Indonesia
     */
    private function getAllFallbackAreasData(): array
    {
        return [
            // Bandung & Sekitarnya (Local Zone)
            [
                'id' => 'IDNP9IDNC22IDND2044IDZ40353',
                'name' => 'Dayeuhkolot, Kab. Bandung',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kabupaten Bandung',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Dayeuhkolot',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 40353,
            ],
            [
                'id' => 'IDNP9IDNC23IDND2050IDZ40115',
                'name' => 'Coblong, Kota Bandung',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Bandung',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Coblong',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 40115,
            ],
            [
                'id' => 'IDNP9IDNC23IDND2051IDZ40132',
                'name' => 'Dago (Coblong), Kota Bandung',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Bandung',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Dago',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 40132,
            ],
            [
                'id' => 'IDNP9IDNC23IDND2052IDZ40124',
                'name' => 'Cibeunying Kaler, Kota Bandung',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Bandung',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Cibeunying Kaler',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 40124,
            ],
            [
                'id' => 'IDNP9IDNC23IDND2053IDZ40161',
                'name' => 'Sukajadi, Kota Bandung',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Bandung',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Sukajadi',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 40161,
            ],
            [
                'id' => 'IDNP9IDNC24IDND2060IDZ40511',
                'name' => 'Cimahi Tengah, Kota Cimahi',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Cimahi',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Cimahi Tengah',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 40511,
            ],

            // DKI Jakarta
            [
                'id' => 'IDNP6IDNC148IDND1163IDZ10110',
                'name' => 'Gambir, Kota Jakarta Pusat',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'DKI Jakarta',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Jakarta Pusat',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Gambir',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 10110,
            ],
            [
                'id' => 'IDNP6IDNC148IDND1164IDZ10310',
                'name' => 'Menteng, Kota Jakarta Pusat',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'DKI Jakarta',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Jakarta Pusat',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Menteng',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 10310,
            ],
            [
                'id' => 'IDNP6IDNC149IDND1170IDZ12110',
                'name' => 'Kebayoran Baru, Kota Jakarta Selatan',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'DKI Jakarta',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Jakarta Selatan',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Kebayoran Baru',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 12110,
            ],
            [
                'id' => 'IDNP6IDNC149IDND1171IDZ12410',
                'name' => 'Cilandak, Kota Jakarta Selatan',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'DKI Jakarta',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Jakarta Selatan',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Cilandak',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 12410,
            ],
            [
                'id' => 'IDNP6IDNC149IDND1172IDZ12710',
                'name' => 'Tebet, Kota Jakarta Selatan',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'DKI Jakarta',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Jakarta Selatan',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Tebet',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 12710,
            ],
            [
                'id' => 'IDNP6IDNC150IDND1175IDZ11410',
                'name' => 'Grogol Petamburan, Kota Jakarta Barat',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'DKI Jakarta',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Jakarta Barat',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Grogol Petamburan',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 11410,
            ],
            [
                'id' => 'IDNP6IDNC151IDND1180IDZ13410',
                'name' => 'Duren Sawit, Kota Jakarta Timur',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'DKI Jakarta',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Jakarta Timur',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Duren Sawit',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 13410,
            ],
            [
                'id' => 'IDNP6IDNC152IDND1185IDZ14240',
                'name' => 'Kelapa Gading, Kota Jakarta Utara',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'DKI Jakarta',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Jakarta Utara',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Kelapa Gading',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 14240,
            ],

            // Bodetabek
            [
                'id' => 'IDNP9IDNC27IDND2090IDZ16111',
                'name' => 'Bogor Tengah, Kota Bogor',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Bogor',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Bogor Tengah',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 16111,
            ],
            [
                'id' => 'IDNP9IDNC26IDND2080IDZ16411',
                'name' => 'Pancoran Mas, Kota Depok',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Depok',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Pancoran Mas',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 16411,
            ],
            [
                'id' => 'IDNP3IDNC30IDND3010IDZ15111',
                'name' => 'Tangerang, Kota Tangerang',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Banten',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Tangerang',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Tangerang',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 15111,
            ],
            [
                'id' => 'IDNP3IDNC31IDND3020IDZ15310',
                'name' => 'Serpong (BSD), Kota Tangerang Selatan',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Banten',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Tangerang Selatan',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Serpong',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 15310,
            ],
            [
                'id' => 'IDNP9IDNC25IDND2070IDZ17111',
                'name' => 'Bekasi Barat, Kota Bekasi',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Bekasi',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Bekasi Barat',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 17111,
            ],

            // Jawa Tengah & DIY
            [
                'id' => 'IDNP10IDNC40IDND4010IDZ50134',
                'name' => 'Semarang Tengah, Kota Semarang',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Tengah',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Semarang',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Semarang Tengah',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 50134,
            ],
            [
                'id' => 'IDNP10IDNC41IDND4020IDZ57111',
                'name' => 'Banjarsari, Kota Surakarta (Solo)',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Tengah',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Surakarta',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Banjarsari',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 57111,
            ],
            [
                'id' => 'IDNP5IDNC50IDND5010IDZ55213',
                'name' => 'Danurejan (Malioboro), Kota Yogyakarta',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'DI Yogyakarta',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Yogyakarta',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Danurejan',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 55213,
            ],
            [
                'id' => 'IDNP5IDNC51IDND5020IDZ55281',
                'name' => 'Depok (Sleman), Kab. Sleman, Yogyakarta',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'DI Yogyakarta',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kabupaten Sleman',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Depok',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 55281,
            ],

            // Jawa Timur
            [
                'id' => 'IDNP11IDNC60IDND6010IDZ60241',
                'name' => 'Wonokromo, Kota Surabaya',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Timur',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Surabaya',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Wonokromo',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 60241,
            ],
            [
                'id' => 'IDNP11IDNC60IDND6011IDZ60271',
                'name' => 'Gubeng, Kota Surabaya',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Timur',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Surabaya',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Gubeng',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 60271,
            ],
            [
                'id' => 'IDNP11IDNC61IDND6020IDZ65111',
                'name' => 'Klojen, Kota Malang',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Jawa Timur',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Malang',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Klojen',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 65111,
            ],

            // Bali & Nusa Tenggara
            [
                'id' => 'IDNP1IDNC70IDND7010IDZ80232',
                'name' => 'Denpasar Selatan, Kota Denpasar, Bali',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Bali',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Denpasar',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Denpasar Selatan',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 80232,
            ],
            [
                'id' => 'IDNP1IDNC71IDND7020IDZ80361',
                'name' => 'Kuta, Kab. Badung, Bali',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Bali',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kabupaten Badung',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Kuta',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 80361,
            ],

            // Sumatera
            [
                'id' => 'IDNP34IDNC80IDND8010IDZ20111',
                'name' => 'Medan Kota, Kota Medan',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Sumatera Utara',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Medan',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Medan Kota',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 20111,
            ],
            [
                'id' => 'IDNP33IDNC90IDND9010IDZ30111',
                'name' => 'Ilir Barat I, Kota Palembang',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Sumatera Selatan',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Palembang',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Ilir Barat I',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 30111,
            ],
            [
                'id' => 'IDNP17IDNC120IDND12010IDZ29432',
                'name' => 'Batam Kota, Kota Batam',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Kepulauan Riau',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Batam',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Batam Kota',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 29432,
            ],
            [
                'id' => 'IDNP26IDNC130IDND13010IDZ28111',
                'name' => 'Pekanbaru Kota, Kota Pekanbaru',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Riau',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Pekanbaru',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Pekanbaru Kota',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 28111,
            ],
            [
                'id' => 'IDNP32IDNC140IDND14010IDZ25111',
                'name' => 'Padang Barat, Kota Padang',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Sumatera Barat',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Padang',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Padang Barat',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 25111,
            ],
            [
                'id' => 'IDNP18IDNC150IDND15010IDZ35111',
                'name' => 'Tanjung Karang Pusat, Kota Bandar Lampung',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Lampung',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Bandar Lampung',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Tanjung Karang Pusat',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 35111,
            ],

            // Kalimantan & Sulawesi
            [
                'id' => 'IDNP28IDNC100IDND10010IDZ90111',
                'name' => 'Ujung Pandang, Kota Makassar',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Sulawesi Selatan',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Makassar',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Ujung Pandang',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 90111,
            ],
            [
                'id' => 'IDNP15IDNC110IDND11010IDZ75111',
                'name' => 'Samarinda Kota, Kota Samarinda',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Kalimantan Timur',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Samarinda',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Samarinda Kota',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 75111,
            ],
            [
                'id' => 'IDNP15IDNC111IDND11020IDZ76111',
                'name' => 'Balikpapan Kota, Kota Balikpapan',
                'country_name' => 'Indonesia',
                'country_code' => 'ID',
                'administrative_division_level_1_name' => 'Kalimantan Timur',
                'administrative_division_level_1_type' => 'province',
                'administrative_division_level_2_name' => 'Kota Balikpapan',
                'administrative_division_level_2_type' => 'city',
                'administrative_division_level_3_name' => 'Balikpapan Kota',
                'administrative_division_level_3_type' => 'district',
                'postal_code' => 76111,
            ],
        ];
    }
}
