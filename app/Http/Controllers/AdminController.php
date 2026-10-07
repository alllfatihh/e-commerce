<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Order;
use App\Models\SiteMedia;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::with('stocks')->orderBy('id', 'desc')->get();
        $orders = Order::orderBy('id', 'desc')->take(20)->get();
        $carousels = SiteMedia::where('section', 'carousel')->orderBy('order')->get();
        $footages = SiteMedia::where('section', 'footage')->orderBy('order')->get();

        return view('admin', compact('products', 'orders', 'carousels', 'footages'));
    }

    // ─── Products ─────────────────────────────────────────────
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:4096',
            'stocks'      => 'nullable|array'
        ]);

        $data = $request->only('name', 'price', 'description');

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
                        'stock'      => $qty
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
            'image'       => 'nullable|image|max:4096',
            'stocks'      => 'nullable|array'
        ]);

        $data = $request->only('name', 'price', 'description');

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
                    $stock->stock = $qty;
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
            // Simpan ke storage/app/public/site-media/
            $path = $request->file('image')->store('site-media', 'public');
            $data['image'] = $path;
        }

        $media->update($data);

        return redirect()->back()->with('success', 'Gambar berhasil diperbarui.');
    }

    // ─── Biteship Sync ───────────────────────────────────────
    public function syncBiteship()
    {
        Artisan::call('biteship:sync-products');
        $output = Artisan::output();
        return redirect()->back()->with('success', 'Sinkronisasi Biteship Selesai: ' . $output);
    }
}
