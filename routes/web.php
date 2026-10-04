<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Models\Product;
use App\Models\Fotoshoot;

Route::get('/', function () {
    $products = Product::all();
    $fotoshoots = Fotoshoot::all();
    return view('welcome', compact('products', 'fotoshoots'));
});

// Checkout Routes
Route::post('/checkout', [CheckoutController::class, 'process'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/shipping-rates', [CheckoutController::class, 'getShippingRates'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/midtrans/webhook', [CheckoutController::class, 'webhook'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/my-orders', function() {
    $orders = \App\Models\Order::with('items')->orderBy('created_at', 'desc')->take(10)->get();
    
    // Sinkronisasi otomatis ke Midtrans (Berguna untuk localhost yang tidak dapat Webhook)
    foreach ($orders as $order) {
        if ($order->status === 'pending') {
            try {
                \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                $status = \Midtrans\Transaction::status($order->id . '-' . strtotime($order->created_at));
                
                if ($status && in_array($status->transaction_status, ['settlement', 'capture'])) {
                    $order->update(['status' => 'paid']);
                } elseif ($status && in_array($status->transaction_status, ['cancel', 'deny', 'expire'])) {
                    $order->update(['status' => 'cancelled']);
                }
            } catch (\Exception $e) {
                // Abaikan jika tidak ditemukan di Midtrans
            }
        }
    }
    
    return $orders;
});

Route::post('/my-orders/{id}/cancel', function($id) {
    $order = \App\Models\Order::find($id);
    if ($order && $order->status === 'pending') {
        $order->update(['status' => 'cancelled']);
        
        try {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Transaction::cancel($order->id . '-' . strtotime($order->created_at));
        } catch (\Exception $e) {
            // Abaikan jika tidak ditemukan di Midtrans
        }

        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 400);
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// HANYA UNTUK TESTING LOKAL (Karena Midtrans tidak bisa menembak webhook ke localhost)
Route::post('/midtrans/local-success', function(\Illuminate\Http\Request $request) {
    $orderId = explode('-', $request->order_id)[0];
    $order = \App\Models\Order::find($orderId);
    if ($order) {
        $order->update(['status' => 'paid']);
    }
    return response()->json(['success' => true]);
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
