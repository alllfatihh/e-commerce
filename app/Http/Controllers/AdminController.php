<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\ArtworkOffering;
use App\Models\DiscountCode;
use App\Models\Editorial;
use App\Models\Fotoshoot;
use App\Models\Lookbook;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SiteMedia;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private function deleteStorageFile(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        $cleanPath = ltrim($path, '/');
        if (Str::startsWith($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        if (Storage::disk('public')->exists($cleanPath)) {
            Storage::disk('public')->delete($cleanPath);
        }
    }

    public function index()
    {
        $products = Product::with('stocks')->orderBy('id', 'desc')->get();
        $orders = Order::with(['items.product', 'user'])->orderBy('id', 'desc')->take(30)->get();
        $carousels = SiteMedia::where('section', 'carousel')->orderBy('order')->get();
        $footages = SiteMedia::where('section', 'footage')->orderBy('order')->get();
        $artists = Artist::with(['artworks', 'editorials'])->orderBy('id', 'desc')->get();
        $artworks = Artwork::with('artist')->orderBy('id', 'desc')->get();
        $editorials = Editorial::with('artist')->orderBy('id', 'desc')->get();
        $offerings = ArtworkOffering::with(['user', 'artwork.artist'])->orderBy('id', 'desc')->get();
        $lookbooks = Lookbook::with('fotoshoots')->orderBy('year', 'desc')->orderBy('id', 'desc')->get();
        $discounts = DiscountCode::orderBy('id', 'desc')->get();
        $admins = User::where('role', 'admin')->orderBy('id', 'asc')->get();
        $settings = SiteSetting::pluck('value', 'key')->toArray();

        $stats = [
            'total_products' => $products->count(),
            'total_stock' => ProductStock::sum('stock'),
            'total_orders' => Order::count(),
            'total_artists' => $artists->count(),
            'total_artworks' => $artworks->count(),
            'total_editorials' => $editorials->count(),
            'total_offerings' => $offerings->count(),
            'total_lookbooks' => $lookbooks->count(),
            'total_discounts' => $discounts->count(),
            'total_admins' => $admins->count(),
            'paid_revenue' => Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])->sum('total_amount'),
        ];

        return view('admin', compact('products', 'orders', 'carousels', 'footages', 'artworks', 'editorials', 'artists', 'offerings', 'lookbooks', 'discounts', 'admins', 'settings', 'stats'));
    }

    // ─── Products ─────────────────────────────────────────────
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'specs' => 'nullable|string',
            'artist' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'back_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'footage_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'size_chart_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'stocks' => 'nullable|array',
            'stocks.*' => 'nullable|integer|min:0',
        ]);

        $data = $request->only('name', 'price', 'description', 'specs', 'artist');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('back_image')) {
            $data['back_image'] = $request->file('back_image')->store('products', 'public');
        }
        if ($request->hasFile('footage_image')) {
            $data['footage_image'] = $request->file('footage_image')->store('products', 'public');
        }
        if ($request->hasFile('size_chart_image')) {
            $data['size_chart_image'] = $request->file('size_chart_image')->store('products', 'public');
        }

        $product = Product::create($data);

        if ($request->has('stocks')) {
            foreach ($request->stocks as $size => $qty) {
                if ($qty !== null && $qty !== '') {
                    ProductStock::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'stock' => (int) $qty,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'specs' => 'nullable|string',
            'artist' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'back_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'footage_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'size_chart_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'stocks' => 'nullable|array',
            'stocks.*' => 'nullable|integer|min:0',
        ]);

        $data = $request->only('name', 'price', 'description', 'specs', 'artist');

        if ($request->hasFile('image')) {
            $this->deleteStorageFile($product->getRawOriginal('image'));
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('back_image')) {
            $this->deleteStorageFile($product->getRawOriginal('back_image'));
            $data['back_image'] = $request->file('back_image')->store('products', 'public');
        }
        if ($request->hasFile('footage_image')) {
            $this->deleteStorageFile($product->getRawOriginal('footage_image'));
            $data['footage_image'] = $request->file('footage_image')->store('products', 'public');
        }
        if ($request->hasFile('size_chart_image')) {
            $this->deleteStorageFile($product->getRawOriginal('size_chart_image'));
            $data['size_chart_image'] = $request->file('size_chart_image')->store('products', 'public');
        }

        $product->update($data);

        if ($request->has('stocks')) {
            foreach ($request->stocks as $size => $qty) {
                if ($qty !== null && $qty !== '') {
                    $stock = ProductStock::firstOrNew([
                        'product_id' => $product->id,
                        'size' => $size,
                    ]);
                    $stock->stock = (int) $qty;
                    $stock->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        $this->deleteStorageFile($product->getRawOriginal('image'));
        $this->deleteStorageFile($product->getRawOriginal('back_image'));
        $this->deleteStorageFile($product->getRawOriginal('footage_image'));
        $this->deleteStorageFile($product->getRawOriginal('size_chart_image'));

        ProductStock::where('product_id', $product->id)->delete();
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }

    // ─── Artists (Seniman Kolaborator) ────────────────────────
    public function storeArtist(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'artwork_preview' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'is_newcomer' => 'nullable|boolean',
        ]);

        $data = $request->only('name', 'city', 'bio');
        $isNewcomer = $request->boolean('is_newcomer');

        if ($isNewcomer) {
            Artist::where('is_newcomer', true)->update(['is_newcomer' => false]);
            $data['is_newcomer'] = true;
        } else {
            $data['is_newcomer'] = false;
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('artists', 'public');
        }
        if ($request->hasFile('artwork_preview')) {
            $data['artwork_preview'] = $request->file('artwork_preview')->store('artists', 'public');
        }

        Artist::create($data);

        return redirect()->back()->with('success', 'Seniman kolaborator berhasil ditambahkan.');
    }

    public function updateArtist(Request $request, $id)
    {
        $artist = Artist::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'artwork_preview' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'is_newcomer' => 'nullable|boolean',
        ]);

        $data = $request->only('name', 'city', 'bio');
        $isNewcomer = $request->boolean('is_newcomer');

        if ($isNewcomer) {
            Artist::where('id', '!=', $artist->id)->where('is_newcomer', true)->update(['is_newcomer' => false]);
            $data['is_newcomer'] = true;
        } else {
            $data['is_newcomer'] = false;
        }

        if ($request->hasFile('photo')) {
            $this->deleteStorageFile($artist->getRawOriginal('photo'));
            $data['photo'] = $request->file('photo')->store('artists', 'public');
        }
        if ($request->hasFile('artwork_preview')) {
            $this->deleteStorageFile($artist->getRawOriginal('artwork_preview'));
            $data['artwork_preview'] = $request->file('artwork_preview')->store('artists', 'public');
        }

        $artist->update($data);

        return redirect()->back()->with('success', 'Data seniman kolaborator berhasil diperbarui.');
    }

    public function deleteArtist($id)
    {
        $artist = Artist::findOrFail($id);

        $this->deleteStorageFile($artist->getRawOriginal('photo'));
        $this->deleteStorageFile($artist->getRawOriginal('artwork_preview'));
        $artist->delete();

        return redirect()->back()->with('success', 'Seniman berhasil dihapus.');
    }

    // ─── Artworks (Karya Seni Fisik per Seniman) ──────────────
    public function storeArtwork(Request $request)
    {
        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required|string|max:255',
            'medium' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:100',
            'year' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:100',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
        ]);

        $data = $request->only('artist_id', 'title', 'medium', 'dimensions', 'year', 'status', 'price', 'description');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artworks', 'public');
            $data['image'] = $path;
        } else {
            $data['image'] = 'https://placehold.co/600x800/d9d9d9/555555?text=New+Artwork';
        }

        Artwork::create($data);

        return redirect()->back()->with('success', 'Karya seni (Artwork) berhasil ditambahkan untuk seniman terkait.');
    }

    public function updateArtwork(Request $request, $id)
    {
        $artwork = Artwork::findOrFail($id);

        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required|string|max:255',
            'medium' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:100',
            'year' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:100',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
        ]);

        $data = $request->only('artist_id', 'title', 'medium', 'dimensions', 'year', 'status', 'price', 'description');

        if ($request->hasFile('image')) {
            $this->deleteStorageFile($artwork->getRawOriginal('image'));
            $path = $request->file('image')->store('artworks', 'public');
            $data['image'] = $path;
        }

        $artwork->update($data);

        return redirect()->back()->with('success', 'Karya seni berhasil diperbarui.');
    }

    public function deleteArtwork($id)
    {
        $artwork = Artwork::findOrFail($id);
        $this->deleteStorageFile($artwork->getRawOriginal('image'));
        $artwork->delete();

        return redirect()->back()->with('success', 'Karya seni berhasil dihapus.');
    }

    // ─── Editorials (Artikel & Wawancara Eksklusif) ────────────
    public function storeEditorial(Request $request)
    {
        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'video_url' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
        ]);

        $data = $request->only('artist_id', 'title', 'author', 'excerpt', 'content', 'video_url');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('editorials', 'public');
            $data['image'] = $path;
        } else {
            $data['image'] = 'https://placehold.co/600x600/d9d9d9/555555?text=New+Editorial';
        }

        Editorial::create($data);

        return redirect()->back()->with('success', 'Artikel editorial berhasil ditambahkan.');
    }

    public function updateEditorial(Request $request, $id)
    {
        $editorial = Editorial::findOrFail($id);

        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'video_url' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
        ]);

        $data = $request->only('artist_id', 'title', 'author', 'excerpt', 'content', 'video_url');

        if ($request->hasFile('image')) {
            $this->deleteStorageFile($editorial->getRawOriginal('image'));
            $path = $request->file('image')->store('editorials', 'public');
            $data['image'] = $path;
        }

        $editorial->update($data);

        return redirect()->back()->with('success', 'Artikel editorial berhasil diperbarui.');
    }

    public function deleteEditorial($id)
    {
        $editorial = Editorial::findOrFail($id);
        $this->deleteStorageFile($editorial->getRawOriginal('image'));
        $editorial->delete();

        return redirect()->back()->with('success', 'Artikel editorial berhasil dihapus.');
    }

    // ─── Site Media (Carousel & Footage) ─────────────────────
    public function storeMedia(Request $request)
    {
        $request->validate([
            'section' => 'required|string|in:carousel,footage',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:8192',
            'order' => 'nullable|integer',
        ]);

        $order = $request->filled('order')
            ? (int) $request->order
            : ((SiteMedia::where('section', $request->section)->max('order') ?? 0) + 1);

        $path = $request->file('image')->store('site-media', 'public');

        SiteMedia::create([
            'section' => $request->section,
            'title' => $request->title ?: 'Slide '.$order,
            'description' => $request->description,
            'image' => $path,
            'order' => $order,
        ]);

        return redirect()->back()->with('success', 'Gambar '.($request->section === 'carousel' ? 'Hero Carousel' : 'Footage').' baru berhasil ditambahkan.');
    }

    public function updateMedia(Request $request, $id)
    {
        $media = SiteMedia::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'product_id' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'order' => 'nullable|integer',
        ]);

        $data = [];
        if ($request->filled('title')) {
            $data['title'] = $request->title;
        }
        if ($request->filled('description')) {
            $data['description'] = $request->description;
        }
        if ($request->has('product_id')) {
            $data['product_id'] = $request->filled('product_id') ? $request->product_id : null;
        }
        if ($request->filled('order')) {
            $data['order'] = (int) $request->order;
        }

        if ($request->hasFile('image')) {
            $this->deleteStorageFile($media->getRawOriginal('image'));
            $path = $request->file('image')->store('site-media', 'public');
            $data['image'] = $path;
        }

        $media->update($data);

        return redirect()->back()->with('success', 'Gambar media berhasil diperbarui.');
    }

    public function deleteMedia($id)
    {
        $media = SiteMedia::findOrFail($id);

        $this->deleteStorageFile($media->getRawOriginal('image'));
        $media->delete();

        return redirect()->back()->with('success', 'Gambar media berhasil dihapus.');
    }

    // ─── Site Settings ─────────────────────────────────────────
    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect()->back()->with('success', 'Pengaturan situs (Site Settings) berhasil diperbarui.');
    }

    // ─── Biteship Sync ───────────────────────────────────────
    public function syncBiteship()
    {
        Artisan::call('biteship:sync-products');
        $output = Artisan::output();

        return redirect()->back()->with('success', 'Sinkronisasi Biteship Selesai: '.$output);
    }

    // ─── Artwork Offerings ─────────────────────────────────────
    public function updateOfferingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $offering = ArtworkOffering::findOrFail($id);
        $offering->update([
            'status' => $request->status,
        ]);

        return redirect('/admin')->with('success', 'Status penawaran karya berhasil diperbarui!');
    }

    // ─── Lookbooks Management ───────────────────────────────
    public function storeLookbook(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt,webm|max:102400',
            'video_url' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
        ]);

        $data = [
            'title' => $request->title,
            'year' => $request->year ?: date('Y'),
            'description' => $request->description,
        ];

        if ($request->hasFile('video')) {
            $data['video_url'] = $request->file('video')->store('lookbooks/videos', 'public');
        } elseif ($request->filled('video_url')) {
            $data['video_url'] = $request->video_url;
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('lookbooks/covers', 'public');
        }

        $lookbook = Lookbook::create($data);

        return redirect('/admin')->with('success', 'Lookbook album "'.$lookbook->title.'" berhasil dibuat.');
    }

    public function updateLookbook(Request $request, $id)
    {
        $lookbook = Lookbook::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt,webm|max:102400',
            'video_url' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
        ]);

        $lookbook->title = $request->title;
        if ($request->filled('year')) {
            $lookbook->year = $request->year;
        }
        $lookbook->description = $request->description;

        if ($request->hasFile('video')) {
            $this->deleteStorageFile($lookbook->getRawOriginal('video_url'));
            $lookbook->video_url = $request->file('video')->store('lookbooks/videos', 'public');
        } elseif ($request->filled('video_url')) {
            $lookbook->video_url = $request->video_url;
        }

        if ($request->hasFile('cover_image')) {
            $this->deleteStorageFile($lookbook->getRawOriginal('cover_image'));
            $lookbook->cover_image = $request->file('cover_image')->store('lookbooks/covers', 'public');
        }

        $lookbook->save();

        return redirect('/admin')->with('success', 'Lookbook "'.$lookbook->title.'" berhasil diperbarui.');
    }

    public function deleteLookbook($id)
    {
        $lookbook = Lookbook::with('fotoshoots')->findOrFail($id);
        $title = $lookbook->title;

        foreach ($lookbook->fotoshoots as $photo) {
            $this->deleteStorageFile($photo->getRawOriginal('image'));
            $photo->delete();
        }

        $this->deleteStorageFile($lookbook->getRawOriginal('cover_image'));
        $this->deleteStorageFile($lookbook->getRawOriginal('video_url'));
        $lookbook->delete();

        return redirect('/admin')->with('success', 'Lookbook "'.$title.'" beserta foto-fotonya berhasil dihapus.');
    }

    public function storeLookbookPhoto(Request $request, $id)
    {
        $lookbook = Lookbook::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:8192',
        ]);

        $path = $request->file('image')->store('lookbooks/photos', 'public');

        Fotoshoot::create([
            'lookbook_id' => $lookbook->id,
            'title' => $request->title ?: $lookbook->title,
            'description' => $request->description,
            'image' => $path,
        ]);

        if (empty($lookbook->getRawOriginal('cover_image'))) {
            $lookbook->update(['cover_image' => $path]);
        }

        return redirect('/admin')->with('success', 'Foto berhasil ditambahkan ke lookbook "'.$lookbook->title.'".');
    }

    public function deleteLookbookPhoto($id)
    {
        $photo = Fotoshoot::findOrFail($id);
        $this->deleteStorageFile($photo->getRawOriginal('image'));
        $photo->delete();

        return redirect('/admin')->with('success', 'Foto lookbook berhasil dihapus.');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:pending,paid,processing,shipped,delivered,completed,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->input('status');
        $order->status = $newStatus;

        if ($request->has('tracking_number')) {
            $order->tracking_number = $request->input('tracking_number');
        }

        if ($newStatus === 'completed' && ! $order->received_at) {
            $order->received_at = now();
        } elseif ($newStatus !== 'completed' && $oldStatus === 'completed') {
            $order->received_at = null;
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            // If status changed to cancelled and wasn't cancelled before, restore stock
            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        ProductStock::where('product_id', $item->product_id)
                            ->where('size', $item->size)
                            ->increment('stock', $item->quantity);
                    }
                }
            }
            // If status changed from cancelled back to an active status, deduct stock again
            elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        ProductStock::where('product_id', $item->product_id)
                            ->where('size', $item->size)
                            ->decrement('stock', $item->quantity);
                    }
                }
            }

            $order->save();
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'order' => $order]);
        }

        return redirect('/admin')->with('success', 'Status pesanan #'.$order->id.' berhasil diperbarui.');
    }

    public function dispatchBiteship(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $checkoutController = new CheckoutController;
        $result = $checkoutController->dispatchBiteshipOrder($order);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => $result !== null,
                'result' => $result,
                'order' => $order->fresh(),
            ]);
        }

        if ($result) {
            return redirect('/admin')->with('success', 'Pesanan #'.$order->id.' berhasil didispatch ke Biteship.');
        } else {
            return redirect('/admin')->with('error', 'Gagal dispatch ke Biteship. Periksa konfigurasi API Key atau saldo Biteship.');
        }
    }

    public function syncOrderBiteship(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (! $order->biteship_order_id) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Pesanan belum memiliki Biteship Order ID.'], 422);
            }

            return redirect('/admin')->with('error', 'Pesanan ini belum memiliki Biteship Order ID.');
        }

        $checkoutController = new CheckoutController;
        $result = $checkoutController->syncBiteshipOrderStatus($order);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => $result !== null,
                'result' => $result,
                'order' => $order->fresh(),
            ]);
        }

        if ($result) {
            $freshOrder = $order->fresh();
            $statusLabel = strtoupper($freshOrder->biteship_status ?? $freshOrder->status);

            return redirect('/admin')->with('success', 'Status pesanan #'.$order->id.' berhasil disinkronkan dengan Biteship (Status: '.$statusLabel.', Resi: '.($freshOrder->tracking_number ?? '—').').');
        }

        return redirect('/admin')->with('error', 'Gagal menyinkronkan status dengan Biteship API.');
    }

    // ─── Discount Codes (Promo Codes) ─────────────────────────
    public function storeDiscountCode(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discount_codes,code',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['min_order_amount'] = $validated['min_order_amount'] ?? 0;

        DiscountCode::create($validated);

        return redirect('/admin#section-discounts')->with('success', 'Kode diskon '.$validated['code'].' berhasil ditambahkan.');
    }

    public function updateDiscountCode(Request $request, $id)
    {
        $discount = DiscountCode::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discount_codes,code,'.$id,
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active');
        $validated['min_order_amount'] = $validated['min_order_amount'] ?? 0;

        $discount->update($validated);

        return redirect('/admin#section-discounts')->with('success', 'Kode diskon '.$discount->code.' berhasil diperbarui.');
    }

    public function deleteDiscountCode($id)
    {
        $discount = DiscountCode::findOrFail($id);
        $code = $discount->code;
        $discount->delete();

        return redirect('/admin#section-discounts')->with('success', 'Kode diskon '.$code.' berhasil dihapus.');
    }

    public function toggleDiscountCode($id)
    {
        $discount = DiscountCode::findOrFail($id);
        $discount->update(['is_active' => ! $discount->is_active]);

        $statusText = $discount->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect('/admin#section-discounts')->with('success', 'Kode diskon '.$discount->code.' berhasil '.$statusText.'.');
    }

    // ─── Admins Management ────────────────────────────────────
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:50',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => 'admin',
        ]);

        return redirect('/admin#section-admins')->with('success', 'Admin baru '.$validated['name'].' berhasil ditambahkan.');
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:50',
        ]);

        $admin->name = $validated['name'];
        $admin->email = strtolower(trim($validated['email']));
        $admin->phone = $validated['phone'] ?? null;

        if (! empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect('/admin#section-admins')->with('success', 'Data admin '.$admin->name.' berhasil diperbarui.');
    }

    public function deleteAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        if (auth()->id() === (int) $id) {
            return redirect('/admin#section-admins')->with('error', 'Anda tidak dapat menghapus akun admin yang sedang Anda gunakan.');
        }

        $totalAdmins = User::where('role', 'admin')->count();
        if ($totalAdmins <= 1) {
            return redirect('/admin#section-admins')->with('error', 'Tidak dapat menghapus admin terakhir pada sistem.');
        }

        $name = $admin->name;
        $admin->delete();

        return redirect('/admin#section-admins')->with('success', 'Akun admin '.$name.' berhasil dihapus.');
    }
}
