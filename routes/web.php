<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Models\Product;
use App\Models\Fotoshoot;

// ─── Front Store Multi-Page Routes ─────────────────────────
// Shop & Products
Route::get('/', function () {
    $products = Product::with('stocks')->get();
    $carousels = \App\Models\SiteMedia::where('section', 'carousel')->orderBy('order')->get();
    return view('shop.index', compact('products', 'carousels'));
});

Route::get('/shop', function () {
    $products = Product::with('stocks')->get();
    $carousels = \App\Models\SiteMedia::where('section', 'carousel')->orderBy('order')->get();
    return view('shop.index', compact('products', 'carousels'));
});

Route::get('/products/{id}', function ($id) {
    $product = Product::with('stocks')->findOrFail($id);
    $products = Product::with('stocks')->get();
    return view('shop.show', compact('product', 'products'));
});

// Artists & Artworks
Route::get('/artists', function () {
    $artists = \App\Models\Artist::with(['artworks', 'editorials'])->get();
    $newcomer = \App\Models\Artist::where('is_newcomer', true)->first() ?: \App\Models\Artist::first();
    return view('artists.index', compact('artists', 'newcomer'));
});

Route::get('/artists/{id}', function ($id) {
    $artist = \App\Models\Artist::with(['artworks', 'editorials', 'products'])->findOrFail($id);
    $artists = \App\Models\Artist::with(['artworks', 'editorials'])->get();
    return view('artists.show', compact('artist', 'artists'));
});

Route::get('/artworks/{id}', function ($id) {
    $artwork = \App\Models\Artwork::with('artist')->findOrFail($id);
    return view('artworks.show', compact('artwork'));
});

// Editorials
Route::get('/editorials', function () {
    $editorials = \App\Models\Editorial::with('artist')->latest()->get();
    return view('editorials.index', compact('editorials'));
});

Route::get('/editorials/{id}', function ($id) {
    $editorial = \App\Models\Editorial::with('artist')->findOrFail($id);
    return view('editorials.show', compact('editorial'));
});

// Lookbooks
Route::get('/lookbooks', function () {
    $lookbooks = \App\Models\Lookbook::with('fotoshoots')->orderBy('year', 'desc')->orderBy('id', 'desc')->get();
    return view('lookbooks.index', compact('lookbooks'));
});

Route::get('/lookbooks/{id}', function ($id) {
    $lookbook = \App\Models\Lookbook::with('fotoshoots')->findOrFail($id);
    $lookbooks = \App\Models\Lookbook::with('fotoshoots')->orderBy('year', 'desc')->orderBy('id', 'desc')->get();
    return view('lookbooks.show', compact('lookbook', 'lookbooks'));
});

// Brand Information Pages
Route::get('/about', function () {
    return view('pages.about');
});

Route::get('/contact', function () {
    return view('pages.contact');
});

// Customer Authentication
Route::get('/login', function () {
    if (auth()->check()) return redirect('/account');
    return view('auth.login');
});

Route::get('/register', function () {
    if (auth()->check()) return redirect('/account');
    return view('auth.register');
});

// Customer Account
Route::get('/account', function () {
    if (!auth()->check()) return redirect('/login');
    $user = auth()->user();
    $orders = \App\Models\Order::with('items.product')->where('user_id', $user->id)->latest()->take(10)->get();
    $offerings = \App\Models\ArtworkOffering::with('artwork.artist')->where('user_id', $user->id)->latest()->get();
    return view('account.profile', compact('user', 'orders', 'offerings'));
});

Route::get('/account/edit', function () {
    if (!auth()->check()) return redirect('/login');
    $user = auth()->user();
    return view('account.edit', compact('user'));
});

// Seamless Auth & Google Login
Route::get('/auth/checkout', [AuthController::class, 'showCheckoutAuth']);
Route::get('/auth/google', [AuthController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);
Route::post('/auth/seamless', [AuthController::class, 'seamlessRegisterOrLogin'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return response()->json(['success' => true]);
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/update-profile', [AuthController::class, 'updateProfile'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
// Admin Routes
use App\Http\Controllers\AdminController;
Route::get('/admin', [AdminController::class, 'index']);
Route::post('/admin/products', [AdminController::class, 'storeProduct'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['post', 'put'], '/admin/products/{id}', [AdminController::class, 'updateProduct'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/admin/media', [AdminController::class, 'storeMedia'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['post', 'put'], '/admin/media/{id}', [AdminController::class, 'updateMedia'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::delete('/admin/media/{id}', [AdminController::class, 'deleteMedia'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/admin/artworks', [AdminController::class, 'storeArtwork'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['post', 'put'], '/admin/artworks/{id}', [AdminController::class, 'updateArtwork'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::delete('/admin/artworks/{id}', [AdminController::class, 'deleteArtwork'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/admin/editorials', [AdminController::class, 'storeEditorial'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['post', 'put'], '/admin/editorials/{id}', [AdminController::class, 'updateEditorial'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::delete('/admin/editorials/{id}', [AdminController::class, 'deleteEditorial'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/admin/artists', [AdminController::class, 'storeArtist'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['post', 'put'], '/admin/artists/{id}', [AdminController::class, 'updateArtist'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::delete('/admin/artists/{id}', [AdminController::class, 'deleteArtist'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/admin/offerings/{id}/status', [AdminController::class, 'updateOfferingStatus'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/admin/lookbooks', [AdminController::class, 'storeLookbook'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['post', 'put'], '/admin/lookbooks/{id}', [AdminController::class, 'updateLookbook'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::delete('/admin/lookbooks/{id}', [AdminController::class, 'deleteLookbook'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/admin/lookbooks/{id}/photos', [AdminController::class, 'storeLookbookPhoto'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::delete('/admin/lookbooks/photos/{id}', [AdminController::class, 'deleteLookbookPhoto'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Cart Routes
use App\Http\Controllers\CartController;
Route::get('/api/cart', [CartController::class, 'index'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/api/cart', [CartController::class, 'add'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/api/cart/clear', [CartController::class, 'clear'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::put('/api/cart/{id}', [CartController::class, 'updateItem'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::delete('/api/cart/{id}', [CartController::class, 'remove'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/admin/biteship/sync', [AdminController::class, 'syncBiteship'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

use App\Http\Controllers\ArtworkOfferingController;
Route::post('/api/artwork-offerings', [ArtworkOfferingController::class, 'store'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/my-artwork-offers', function () {
    if (!auth()->check()) return response()->json([]);

    return \App\Models\ArtworkOffering::with(['artwork.artist'])
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();
});

// Checkout Routes
Route::post('/checkout', [CheckoutController::class, 'process'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/shipping-areas', [CheckoutController::class, 'searchArea']);
Route::post('/shipping-rates', [CheckoutController::class, 'getShippingRates'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/midtrans/webhook', [CheckoutController::class, 'webhook'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/my-orders', function () {
    if (!auth()->check()) return response()->json([]);

    $orders = \App\Models\Order::with('items.product')
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

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

Route::post('/my-orders/{id}/cancel', function ($id) {
    $order = \App\Models\Order::find($id);
    if ($order && $order->status === 'pending') {
        $order->update(['status' => 'cancelled']);

        // Restore stock since order is cancelled
        foreach ($order->items as $item) {
            if ($item->product_id) {
                \App\Models\ProductStock::where('product_id', $item->product_id)
                    ->where('size', $item->size)
                    ->increment('stock', $item->quantity);
            }
        }

        try {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Transaction::cancel($order->id . '-' . strtotime($order->created_at));
        } catch (\Exception $e) {
            // Abaikan jika tidak ditemukan di Midtrans
        }
    }
    return response()->json(['success' => true]);
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/my-orders/{id}/pay', function ($id) {
    $order = \App\Models\Order::with('items')->find($id);
    if (!$order || $order->status !== 'pending' || $order->user_id !== auth()->id()) {
        return response()->json(['error' => 'Pesanan tidak valid.'], 400);
    }

    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => $order->id . '-' . time(),
            'gross_amount' => $order->total_amount,
        ],
        'customer_details' => [
            'first_name' => $order->customer_name,
            'email' => $order->customer_email,
            'phone' => $order->customer_phone,
        ],
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json(['snap_token' => $snapToken]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// HANYA UNTUK TESTING LOKAL (Karena Midtrans tidak bisa menembak webhook ke localhost)
Route::post('/midtrans/local-success', function (\Illuminate\Http\Request $request) {
    $orderId = explode('-', $request->order_id)[0];
    $order = \App\Models\Order::find($orderId);
    if ($order && $order->status !== 'paid') {
        $order->update(['status' => 'paid']);
        // Stock deduction is already handled during CheckoutController process.
    }
    return response()->json(['success' => true]);
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
