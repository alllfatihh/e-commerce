<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

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
            $pendingOrder = Order::where('user_id', auth()->id())->where('status', 'pending')->first();
            if ($pendingOrder) {
                return response()->json([
                    'error' => 'Anda masih memiliki transaksi yang belum diselesaikan.',
                    'pending_order' => true
                ], 403);
            }
        }

        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'destination_area_id' => 'required|string',
            'items' => 'required|array',
            'shipping_cost' => 'required|numeric',
            'courier_name' => 'required|string',
        ]);

        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $totalAmount += $validated['shipping_cost'];

        // PRE-CHECK STOCK
        foreach ($validated['items'] as $item) {
            $productId = $item['id'] ?? null;
            $size = $item['size'] ?? 'All Size';
            
            if ($productId) {
                $stockRecord = \App\Models\ProductStock::where('product_id', $productId)->where('size', $size)->first();
                if (!$stockRecord || $stockRecord->stock < $item['quantity']) {
                    return response()->json(['success' => false, 'error' => 'Maaf, stok produk "' . $item['name'] . '" (' . $size . ') tidak mencukupi.'], 400);
                }
            }
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'shipping_address' => $validated['shipping_address'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($validated['items'] as $item) {
            $productId = $item['id'] ?? null;
            $size = $item['size'] ?? 'All Size';
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'size' => $size,
            ]);

            if ($productId) {
                \App\Models\ProductStock::where('product_id', $productId)->where('size', $size)->decrement('stock', $item['quantity']);
            }
        }

        $biteshipItems = array_map(function($item) {
            return [
                "name" => $item['name'],
                "description" => $item['name'],
                "value" => $item['price'],
                "length" => 20,
                "width" => 20,
                "height" => 5,
                "weight" => 200,
                "quantity" => $item['quantity']
            ];
        }, $validated['items']);

        // Pisahkan nama kurir dan service name (misal "JNE REG")
        $courierParts = explode(' ', $validated['courier_name']);
        $courierCompany = strtolower($courierParts[0]);
        if ($courierCompany === 'j&t') $courierCompany = 'jnt';
        $courierType = strtolower($courierParts[1] ?? 'reg');

        // Create Order Biteship
        $biteshipResponse = Http::withHeaders([
            'Authorization' => env('BITESHIP_API_KEY')
        ])->post('https://api.biteship.com/v1/orders', [
            "shipper_contact_name" => "Notisse Admin",
            "shipper_contact_phone" => "081234567890",
            "shipper_contact_email" => "admin@notisse.com",
            "shipper_organization" => "Notisse",
            "origin_contact_name" => "Notisse Admin",
            "origin_contact_phone" => "081234567890",
            "origin_address" => env('STORE_ORIGIN_ADDRESS', "Jl. Sukapura No.62, Dayeuhkolot"),
            "origin_area_id" => env('STORE_ORIGIN_AREA_ID', "IDNP9IDNC22IDND2044IDZ40353"),
            "destination_contact_name" => $validated['customer_name'],
            "destination_contact_phone" => $validated['customer_phone'],
            "destination_contact_email" => $validated['customer_email'],
            "destination_address" => $validated['shipping_address'],
            "destination_area_id" => $validated['destination_area_id'],
            "courier_company" => $courierCompany,
            "courier_type" => $courierType,
            "delivery_type" => "now",
            "items" => $biteshipItems
        ]);

        $biteshipData = $biteshipResponse->json();
        
        // --- TAMBAHAN LOGGING UNTUK DEBUG BITESIP ---
        \Illuminate\Support\Facades\Log::info('Biteship Create Order Response:', (array) $biteshipData);

        $biteshipOrderId = 'BS-' . uniqid(); // Fallback
        if (isset($biteshipData['id'])) {
            $biteshipOrderId = $biteshipData['id'];
        }

        $order->update([
            'biteship_order_id' => $biteshipOrderId, 
            'courier_name' => $validated['courier_name']
        ]);

        // Konfigurasi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $order->id . '-' . time(),
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
            $order->update(['snap_token' => $snapToken]);
            return response()->json(['snap_token' => $snapToken, 'order_id' => $order->id]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchArea(Request $request)
    {
        $keyword = $request->query('keyword');
        if (!$keyword) return response()->json(['areas' => []]);

        $response = Http::withHeaders([
            'Authorization' => env('BITESHIP_API_KEY')
        ])->get('https://api.biteship.com/v1/maps/areas', [
            'countries' => 'ID',
            'input' => $keyword,
            'type' => 'single'
        ]);

        return response()->json($response->json());
    }

    public function getShippingRates(Request $request)
    {
        try {
            $validated = $request->validate([
                'destination_area_id' => 'required|string',
                'items' => 'required|array'
            ]);

            $biteshipItems = array_map(function($item) {
                return [
                    "name" => $item['name'] ?? "Item",
                    "description" => $item['name'] ?? "Apparel Notisse",
                    "value" => $item['price'] ?? 100000,
                    "length" => 20,
                    "width" => 20,
                    "height" => 5,
                    "weight" => 200, // asumsikan 200gram per baju
                    "quantity" => $item['quantity'] ?? 1
                ];
            }, $validated['items']);

            $response = Http::withHeaders([
                'Authorization' => env('BITESHIP_API_KEY')
            ])->post('https://api.biteship.com/v1/rates/couriers', [
                "origin_area_id" => env('STORE_ORIGIN_AREA_ID', "IDNP9IDNC22IDND2044IDZ40353"),
                "destination_area_id" => $validated['destination_area_id'],
                "couriers" => "jne,sicepat,jnt,anteraja",
                "items" => $biteshipItems
            ]);

            $data = $response->json();

            // Fallback Simulasi jika Biteship error (karena kurang saldo di akun, timeout, dll)
            if ($response->failed() || (isset($data['success']) && $data['success'] === false) || empty($data['pricing'])) {
                return response()->json([
                    'success' => true,
                    'pricing' => [
                        [
                            'courier_name' => 'JNE',
                            'courier_service_name' => 'REG',
                            'price' => 15000
                        ],
                        [
                            'courier_name' => 'SiCepat',
                            'courier_service_name' => 'REG',
                            'price' => 12000
                        ],
                        [
                            'courier_name' => 'J&T',
                            'courier_service_name' => 'EZ',
                            'price' => 17000
                        ]
                    ]
                ]);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('getShippingRates error: ' . $e->getMessage());
            // Berikan fallback jika terjadi exception server
            return response()->json([
                'success' => true,
                'pricing' => [
                    [
                        'courier_name' => 'JNE',
                        'courier_service_name' => 'REG',
                        'price' => 15000
                    ],
                    [
                        'courier_name' => 'SiCepat',
                        'courier_service_name' => 'REG',
                        'price' => 12000
                    ],
                    [
                        'courier_name' => 'J&T',
                        'courier_service_name' => 'EZ',
                        'price' => 17000
                    ]
                ]
            ]);
        }
    }

    public function webhook(Request $request)
    {
        $notif = new Notification();
        
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = explode('-', $notif->order_id)[0];
        $fraud = $notif->fraud_status;

        $order = Order::find($order_id);
        if(!$order) return response()->json('Order not found', 404);

        $oldStatus = $order->status;
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $order->status = 'challenge';
                } else {
                    $order->status = 'paid';
                }
            }
        } else if ($transaction == 'settlement') {
            $order->status = 'paid';
        } else if (in_array($transaction, ['deny', 'expire', 'cancel'])) {
            $order->status = 'cancelled';
        }

        if ($oldStatus !== 'cancelled' && $order->status === 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    \App\Models\ProductStock::where('product_id', $item->product_id)
                        ->where('size', $item->size)
                        ->increment('stock', $item->quantity);
                }
            }
        }

        $order->save();
        return response()->json('OK');
    }
}
