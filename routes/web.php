<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArtworkOfferingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Models\Artist;
use App\Models\ArtworkOffering;
use App\Models\Editorial;
use App\Models\Fotoshoot;
use App\Models\Lookbook;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SiteMedia;
use App\Models\SiteSetting;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

// ─── Storefront Master SPA Routes ─────────────────────────
$getStorefrontData = function ($activeView = 'view-shop', $extra = []) {
    $products = Product::with('stocks')->get();
    $fotoshoots = Fotoshoot::all();
    $artists = Artist::with(['artworks', 'editorials'])->get();
    $editorials = Editorial::with('artist')->latest()->get();
    $lookbooks = Lookbook::with('fotoshoots')->orderBy('year', 'desc')->orderBy('id', 'desc')->get();
    $settings = SiteSetting::pluck('value', 'key')->toArray();
    $newcomer = Artist::where('is_newcomer', true)->first() ?: Artist::first();
    $carousels = SiteMedia::where('section', 'carousel')->orderBy('order')->get();
    $addresses = auth()->check() ? auth()->user()->addresses : collect();

    return array_merge(compact(
        'products',
        'fotoshoots',
        'artists',
        'editorials',
        'lookbooks',
        'settings',
        'newcomer',
        'carousels',
        'addresses',
        'activeView'
    ), $extra);
};

Route::get('/', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-shop'));
});

Route::get('/shop', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-shop'));
});

Route::get('/product/{id}', function ($id) use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-product-detail', ['initialProductId' => (int) $id]));
});

Route::get('/products/{id}', function ($id) use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-product-detail', ['initialProductId' => (int) $id]));
});

Route::get('/artist', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-artist-collab'));
});

Route::get('/artists', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-artist-collab'));
});

Route::get('/artist-collab', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-artist-collab'));
});

Route::get('/artist/{id}', function ($id) use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-artist-detail', ['initialArtistId' => (int) $id]));
});

Route::get('/artists/{id}', function ($id) use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-artist-detail', ['initialArtistId' => (int) $id]));
});

Route::get('/artwork/{id}', function ($id) use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-artist-artwork-detail', ['initialArtworkId' => (int) $id]));
});

Route::get('/artworks/{id}', function ($id) use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-artist-artwork-detail', ['initialArtworkId' => (int) $id]));
});

Route::get('/editorial', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-editorials-all'));
});

Route::get('/editorials', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-editorials-all'));
});

Route::get('/editorial/{id}', function ($id) use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-artist-editorial-detail', ['initialEditorialId' => (int) $id]));
});

Route::get('/editorials/{id}', function ($id) use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-artist-editorial-detail', ['initialEditorialId' => (int) $id]));
});

Route::get('/lookbook', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-lookbook-list'));
});

Route::get('/lookbooks', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-lookbook-list'));
});

Route::get('/lookbook/{id}', function ($id) use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-lookbook-detail', ['initialLookbookId' => (int) $id]));
});

Route::get('/lookbooks/{id}', function ($id) use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-lookbook-detail', ['initialLookbookId' => (int) $id]));
});

Route::get('/about', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-about'));
});

Route::get('/contact', function () use ($getStorefrontData) {
    return view('welcome', $getStorefrontData('view-contact'));
});

Route::get('/login', function () use ($getStorefrontData) {
    if (auth()->check()) {
        return redirect('/account');
    }

    return view('welcome', $getStorefrontData('view-login'));
})->name('login');

Route::get('/register', function () use ($getStorefrontData) {
    if (auth()->check()) {
        return redirect('/account');
    }

    return view('welcome', $getStorefrontData('view-register'));
});

Route::get('/account', function () use ($getStorefrontData) {
    if (! auth()->check()) {
        return redirect('/login');
    }
    $user = auth()->user();
    $orders = Order::with('items.product')->where('user_id', $user->id)->latest()->take(10)->get();
    $offerings = ArtworkOffering::with('artwork.artist')->where('user_id', $user->id)->latest()->get();

    return view('welcome', $getStorefrontData('view-account-profile', compact('user', 'orders', 'offerings')));
});

Route::get('/account/edit', function () use ($getStorefrontData) {
    if (! auth()->check()) {
        return redirect('/login');
    }
    $user = auth()->user();

    return view('welcome', $getStorefrontData('view-edit-profile', compact('user')));
});

// Seamless Auth, Manual Login/Register & Google Login
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:15,1');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::get('/checkout', [AuthController::class, 'showCheckoutAuth']);
Route::get('/auth/checkout', [AuthController::class, 'showCheckoutAuth']);
Route::get('/auth/google', [AuthController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);
Route::post('/auth/seamless', [AuthController::class, 'seamlessRegisterOrLogin'])->middleware('throttle:10,1');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return response()->json(['success' => true]);
});
Route::post('/update-profile', [AuthController::class, 'updateProfile']);

// Admin Routes (Protected with RBAC: auth + admin)

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::post('/admin/products', [AdminController::class, 'storeProduct']);
    Route::match(['post', 'put'], '/admin/products/{id}', [AdminController::class, 'updateProduct']);
    Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct']);
    Route::post('/admin/media', [AdminController::class, 'storeMedia']);
    Route::match(['post', 'put'], '/admin/media/{id}', [AdminController::class, 'updateMedia']);
    Route::delete('/admin/media/{id}', [AdminController::class, 'deleteMedia']);
    Route::post('/admin/artworks', [AdminController::class, 'storeArtwork']);
    Route::match(['post', 'put'], '/admin/artworks/{id}', [AdminController::class, 'updateArtwork']);
    Route::delete('/admin/artworks/{id}', [AdminController::class, 'deleteArtwork']);
    Route::post('/admin/editorials', [AdminController::class, 'storeEditorial']);
    Route::match(['post', 'put'], '/admin/editorials/{id}', [AdminController::class, 'updateEditorial']);
    Route::delete('/admin/editorials/{id}', [AdminController::class, 'deleteEditorial']);
    Route::post('/admin/artists', [AdminController::class, 'storeArtist']);
    Route::match(['post', 'put'], '/admin/artists/{id}', [AdminController::class, 'updateArtist']);
    Route::delete('/admin/artists/{id}', [AdminController::class, 'deleteArtist']);
    Route::post('/admin/offerings/{id}/status', [AdminController::class, 'updateOfferingStatus']);
    Route::post('/admin/lookbooks', [AdminController::class, 'storeLookbook']);
    Route::match(['post', 'put'], '/admin/lookbooks/{id}', [AdminController::class, 'updateLookbook']);
    Route::delete('/admin/lookbooks/{id}', [AdminController::class, 'deleteLookbook']);
    Route::post('/admin/lookbooks/{id}/photos', [AdminController::class, 'storeLookbookPhoto']);
    Route::delete('/admin/lookbooks/photos/{id}', [AdminController::class, 'deleteLookbookPhoto']);
    Route::post('/admin/settings', [AdminController::class, 'updateSettings']);
    Route::post('/admin/biteship/sync', [AdminController::class, 'syncBiteship']);
    Route::post('/admin/orders/{id}/status', [AdminController::class, 'updateOrderStatus']);
    Route::post('/admin/orders/{id}/dispatch', [AdminController::class, 'dispatchBiteship']);
    Route::post('/admin/orders/{id}/sync-biteship', [AdminController::class, 'syncOrderBiteship']);
    Route::post('/admin/discounts', [AdminController::class, 'storeDiscountCode']);
    Route::match(['post', 'put'], '/admin/discounts/{id}', [AdminController::class, 'updateDiscountCode']);
    Route::delete('/admin/discounts/{id}', [AdminController::class, 'deleteDiscountCode']);
    Route::post('/admin/discounts/{id}/toggle', [AdminController::class, 'toggleDiscountCode']);
    Route::post('/admin/admins', [AdminController::class, 'storeAdmin']);
    Route::match(['post', 'put'], '/admin/admins/{id}', [AdminController::class, 'updateAdmin']);
    Route::delete('/admin/admins/{id}', [AdminController::class, 'deleteAdmin']);
});

// Cart Routes (Protected with Rate Limiting)
Route::get('/api/cart', [CartController::class, 'index']);
Route::post('/api/cart', [CartController::class, 'add'])->middleware('throttle:60,1');
Route::post('/api/cart/clear', [CartController::class, 'clear'])->middleware('throttle:30,1');
Route::put('/api/cart/{id}', [CartController::class, 'updateItem'])->middleware('throttle:60,1');
Route::delete('/api/cart/{id}', [CartController::class, 'remove'])->middleware('throttle:60,1');

// User Address Book Routes (Max 3)
Route::get('/api/user/addresses', [AddressController::class, 'index']);
Route::post('/api/user/addresses', [AddressController::class, 'store']);
Route::put('/api/user/addresses/{id}', [AddressController::class, 'update']);
Route::delete('/api/user/addresses/{id}', [AddressController::class, 'destroy']);
Route::post('/api/user/addresses/{id}/primary', [AddressController::class, 'setPrimary']);

Route::post('/api/artwork-offerings', [ArtworkOfferingController::class, 'store'])->middleware('throttle:15,1');
Route::get('/my-artwork-offers', function () {
    if (! auth()->check()) {
        return response()->json([]);
    }

    return ArtworkOffering::with(['artwork.artist'])
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();
});

// Checkout Routes (Protected with Rate Limiting)
Route::post('/checkout', [CheckoutController::class, 'process'])->middleware('throttle:15,1');
Route::post('/api/check-promo', [CheckoutController::class, 'checkPromoCode'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->middleware('throttle:30,1');
Route::get('/shipping-areas', [CheckoutController::class, 'searchArea'])->middleware('throttle:60,1');
Route::post('/shipping-rates', [CheckoutController::class, 'getShippingRates'])->middleware('throttle:60,1');
Route::post('/midtrans/webhook', [CheckoutController::class, 'webhook'])->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/biteship/webhook', [CheckoutController::class, 'biteshipWebhook'])->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/my-orders', function () {
    if (! auth()->check()) {
        return response()->json([]);
    }

    $orders = Order::with('items.product')
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

    $checkoutController = new CheckoutController;

    // Sinkronisasi otomatis ke Midtrans & Biteship (Berguna untuk localhost yang tidak dapat Webhook)
    foreach ($orders as $order) {
        if ($order->status === 'pending') {
            try {
                Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                $midtransId = $order->midtrans_order_id ?? ($order->id.'-'.strtotime($order->created_at));
                $status = Transaction::status($midtransId);

                if ($status && in_array($status->transaction_status, ['settlement', 'capture'])) {
                    DB::transaction(function () use ($order, $checkoutController) {
                        $locked = Order::where('id', $order->id)->lockForUpdate()->first();
                        if ($locked && $locked->status === 'pending') {
                            $locked->update(['status' => 'paid']);
                            $checkoutController->dispatchBiteshipOrder($locked);
                        }
                    });
                } elseif ($status && in_array($status->transaction_status, ['cancel', 'deny', 'expire'])) {
                    DB::transaction(function () use ($order) {
                        $locked = Order::with('items')->where('id', $order->id)->lockForUpdate()->first();
                        if ($locked && $locked->status === 'pending') {
                            $locked->update(['status' => 'cancelled']);
                            foreach ($locked->items as $item) {
                                if ($item->product_id) {
                                    ProductStock::where('product_id', $item->product_id)
                                        ->where('size', $item->size)
                                        ->increment('stock', $item->quantity);
                                }
                            }
                        }
                    });
                }
            } catch (Exception $e) {
                // Abaikan jika tidak ditemukan di Midtrans
            }
        } elseif ($order->biteship_order_id && ! in_array($order->status, ['completed', 'cancelled'])) {
            // Live sync tracking & status from Biteship API
            try {
                $checkoutController->syncBiteshipOrderStatus($order);
            } catch (Exception $e) {
                // Abaikan jika error koneksi sementara
            }
        }
    }

    return Order::with('items.product')
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
});

Route::post('/my-orders/{id}/cancel', function ($id) {
    if (! auth()->check()) {
        return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
    }

    $order = Order::find($id);
    if (! $order || $order->status !== 'pending' || ($order->user_id !== auth()->id() && ! auth()->user()->isAdmin())) {
        return response()->json(['error' => 'Pesanan tidak ditemukan atau Anda tidak memiliki akses.'], 403);
    }

    DB::transaction(function () use ($order) {
        $lockedOrder = Order::with('items')->where('id', $order->id)->lockForUpdate()->first();
        if ($lockedOrder && $lockedOrder->status === 'pending') {
            $lockedOrder->update(['status' => 'cancelled']);

            // Restore stock since order is cancelled
            foreach ($lockedOrder->items as $item) {
                if ($item->product_id) {
                    ProductStock::where('product_id', $item->product_id)
                        ->where('size', $item->size)
                        ->increment('stock', $item->quantity);
                }
            }
        }
    });

    try {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        $midtransId = $order->midtrans_order_id ?? ($order->id.'-'.strtotime($order->created_at));
        Transaction::cancel($midtransId);
    } catch (Exception $e) {
        // Abaikan jika tidak ditemukan di Midtrans
    }

    return response()->json(['success' => true]);
});

Route::post('/my-orders/{id}/pay', function ($id) {
    if (! auth()->check()) {
        return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
    }

    $order = Order::with('items')->find($id);
    if (! $order || $order->status !== 'pending' || ($order->user_id !== auth()->id() && ! auth()->user()->isAdmin())) {
        return response()->json(['error' => 'Pesanan tidak valid atau Anda tidak memiliki akses.'], 403);
    }

    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $midtransOrderId = $order->id.'-'.time();
    $params = [
        'transaction_details' => [
            'order_id' => $midtransOrderId,
            'gross_amount' => $order->total_amount,
        ],
        'customer_details' => [
            'first_name' => $order->customer_name,
            'email' => $order->customer_email,
            'phone' => $order->customer_phone,
        ],
    ];

    try {
        $snapToken = Snap::getSnapToken($params);
        $order->update([
            'midtrans_order_id' => $midtransOrderId,
            'snap_token' => $snapToken,
        ]);

        return response()->json(['snap_token' => $snapToken]);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::post('/my-orders/{id}/confirm-received', function ($id) {
    if (! auth()->check()) {
        return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
    }

    $order = Order::find($id);
    if (! $order || ($order->user_id !== auth()->id() && ! auth()->user()->isAdmin())) {
        return response()->json(['error' => 'Pesanan tidak ditemukan atau Anda tidak memiliki akses.'], 403);
    }

    if (! $order->canBeConfirmedReceived()) {
        return response()->json(['error' => 'Pesanan hanya dapat dikonfirmasi setelah paket tiba / sudah diantarkan oleh kurir.'], 422);
    }

    DB::transaction(function () use ($order) {
        $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();
        if ($lockedOrder && $lockedOrder->canBeConfirmedReceived()) {
            $updateData = [
                'status' => 'completed',
                'received_at' => now(),
            ];
            if ($lockedOrder->biteship_order_id && (! $lockedOrder->biteship_status || $lockedOrder->biteship_status !== 'delivered')) {
                $updateData['biteship_status'] = 'delivered';
            }
            $lockedOrder->update($updateData);
        }
    });

    return response()->json([
        'success' => true,
        'message' => 'Pesanan telah berhasil dikonfirmasi diterima.',
        'order' => $order->fresh(),
    ]);
});

// HANYA UNTUK TESTING LOKAL (Karena Midtrans tidak bisa menembak webhook ke localhost)
if (app()->environment('local', 'testing')) {
    Route::post('/midtrans/local-success', function (Request $request) {
        $orderId = explode('-', $request->order_id)[0];
        $order = Order::where('midtrans_order_id', $request->order_id)->first() ?? Order::find($orderId);
        if ($order && $order->status !== 'paid') {
            $order->update(['status' => 'paid']);
            (new CheckoutController)->dispatchBiteshipOrder($order);
        }

        return response()->json(['success' => true]);
    })->withoutMiddleware([VerifyCsrfToken::class]);
}
