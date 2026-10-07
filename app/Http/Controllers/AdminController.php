<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Order;
use App\Models\SiteMedia;
use App\Models\Fotoshoot;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::with('stocks')->orderBy('id', 'desc')->get();
        $orders = Order::with('items')->orderBy('id', 'desc')->take(30)->get();
        $carousels = SiteMedia::where('section', 'carousel')->orderBy('order')->get();
        $footages = SiteMedia::where('section', 'footage')->orderBy('order')->get();
        $artworks = Fotoshoot::orderBy('id', 'desc')->get();

        $stats = [
            'total_products' => $products->count(),
            'total_stock'    => ProductStock::sum('stock'),
            'total_orders'   => Order::count(),
            'total_artworks' => $artworks->count(),
            'paid_revenue'   => Order::where('status', 'paid')->sum('total_amount'),
        ];

        return view('admin', compact('products', 'orders', 'carousels', 'footages', 'artworks', 'stats'));
    }

    // ─── Products ─────────────────────────────────────────────
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'specs'       => 'nullable|string',
            'artist'      => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:6144',
            'stocks'      => 'nullable|array'
        ]);

        $data = $request->only('name', 'price', 'description', 'specs', 'artist');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product = Product::create($data);

        if ($request->has('stocks')) {
            foreach ($request->stocks as $size => $qty) {
                if ($qty !== null && $qty !== '') {
                    ProductStock::create([
                        'product_id' => $product->id,
                        'size'       => $size,
                        'stock'      => (int) $qty
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
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'specs'       => 'nullable|string',
            'artist'      => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:6144',
            'stocks'      => 'nullable|array'
        ]);

        $data = $request->only('name', 'price', 'description', 'specs', 'artist');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product->update($data);

        if ($request->has('stocks')) {
            foreach ($request->stocks as $size => $qty) {
                if ($qty !== null && $qty !== '') {
                    $stock = ProductStock::firstOrNew([
                        'product_id' => $product->id,
                        'size'       => $size
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
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }

    // ─── Artworks & Artist Editorial ─────────────────────────
    public function storeArtwork(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'artist_name' => 'nullable|string|max:255',
            'medium'      => 'nullable|string|max:255',
            'year'        => 'nullable|string|max:20',
            'status'      => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:8192',
        ]);

        $data = $request->only('title', 'artist_name', 'medium', 'year', 'status', 'description');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artworks', 'public');
            $data['image'] = $path;
        } else {
            $data['image'] = 'footagebaju2.jpg';
        }

        Fotoshoot::create($data);

        return redirect()->back()->with('success', 'Karya seni / editorial berhasil ditambahkan.');
    }

    public function updateArtwork(Request $request, $id)
    {
        $artwork = Fotoshoot::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'artist_name' => 'nullable|string|max:255',
            'medium'      => 'nullable|string|max:255',
            'year'        => 'nullable|string|max:20',
            'status'      => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:8192',
        ]);

        $data = $request->only('title', 'artist_name', 'medium', 'year', 'status', 'description');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artworks', 'public');
            $data['image'] = $path;
        }

        $artwork->update($data);

        return redirect()->back()->with('success', 'Karya seni / editorial berhasil diperbarui.');
    }

    public function deleteArtwork($id)
    {
        $artwork = Fotoshoot::findOrFail($id);
        $artwork->delete();
        return redirect()->back()->with('success', 'Karya seni / editorial berhasil dihapus.');
    }

    // ─── Site Media (Carousel & Footage) ─────────────────────
    public function updateMedia(Request $request, $id)
    {
        $media = SiteMedia::findOrFail($id);

        $request->validate([
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:8192',
        ]);

        $data = [];
        if ($request->filled('title')) {
            $data['title'] = $request->title;
        }
        if ($request->filled('description')) {
            $data['description'] = $request->description;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('site-media', 'public');
            $data['image'] = $path;
        }

        $media->update($data);

        return redirect()->back()->with('success', 'Gambar media berhasil diperbarui.');
    }

    // ─── Biteship Sync ───────────────────────────────────────
    public function syncBiteship()
    {
        Artisan::call('biteship:sync-products');
        $output = Artisan::output();
        return redirect()->back()->with('success', 'Sinkronisasi Biteship Selesai: ' . $output);
    }
}
