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
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'items' => 'required|array',
            'shipping_cost' => 'required|numeric',
            'courier_name' => 'required|string',
        ]);

        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $totalAmount += $validated['shipping_cost'];

        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'shipping_address' => $validated['shipping_address'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'size' => $item['size'] ?? 'All Size',
            ]);
        }

        // Create Order Biteship
        // Di aplikasi nyata, Anda bisa memanggil API create order Biteship di sini
        $order->update(['biteship_order_id' => 'BS-' . uniqid(), 'courier_name' => $validated['courier_name']]);

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
        $validated = $request->validate([
            'destination_area_id' => 'required|string',
            'items' => 'required|array'
        ]);

        $biteshipItems = array_map(function($item) {
            return [
                "name" => $item['name'],
                "description" => $item['name'] ?? "Apparel Notisse",
                "value" => $item['price'],
                "length" => 20,
                "width" => 20,
                "height" => 5,
                "weight" => 200, // asumsikan 200gram per baju
                "quantity" => $item['quantity']
            ];
        }, $validated['items']);

        $response = Http::withHeaders([
            'Authorization' => env('BITESHIP_API_KEY')
        ])->post('https://api.biteship.com/v1/rates/couriers', [
            "origin_area_id" => "IDNP11IDNC23IDND164IDZ12440", // Origin: Jakarta Selatan (contoh)
            "destination_area_id" => $validated['destination_area_id'],
            "couriers" => "jne,sicepat,jnt,anteraja",
            "items" => $biteshipItems
        ]);

        return response()->json($response->json());
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

        $order->save();
        return response()->json('OK');
    }
}
