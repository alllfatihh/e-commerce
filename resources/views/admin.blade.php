<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notisse — Admin Panel</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logowebkecil.png') }}" type="image/png">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts – same as welcome.blade.php -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'],
                        roboto:     ['Roboto', 'sans-serif'],
                        cursive:    ['Caveat', 'cursive'],
                    },
                    colors: {
                        brandDark:    '#1c1c1c',
                        taupeBanner:  '#A29892',
                        accentYellow: '#E2FF00',
                    }
                }
            }
        }
    </script>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f5;
            color: #111111;
            overflow-x: hidden;
        }

        /* ── Scrollbar ─────────────────────────── */
        ::-webkit-scrollbar      { width: 4px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #aaaaaa; border-radius: 2px; }

        /* ── Sidebar ───────────────────────────── */
        #admin-sidebar {
            width: 240px;
            min-height: 100vh;
            background: #d52c2b;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 40;
            transition: transform 0.35s cubic-bezier(0.16,1,0.3,1);
        }

        /* ── Nav link active state ─────────────── */
        .nav-link { transition: background 0.2s, padding-left 0.2s; }
        .nav-link:hover, .nav-link.active {
            background: #E2FF00;
            color: #111111;
            padding-left: 1.5rem;
        }
        .nav-link span { pointer-events: none; }

        /* ── Table row hover ───────────────────── */
        .admin-tr { transition: background 0.15s; }
        .admin-tr:hover { background: #fafafa; }

        /* ── Badge ─────────────────────────────── */
        .badge-paid       { background:#d1fae5; color:#065f46; }
        .badge-cancelled  { background:#fee2e2; color:#991b1b; }
        .badge-pending    { background:#fef9c3; color:#854d0e; }

        /* ── Animations ────────────────────────── */
        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(12px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .section-card {
            animation: fadeInUp 0.35s cubic-bezier(0.16,1,0.3,1) forwards;
        }

        /* ── Input focus ───────────────────────── */
        .admin-input {
            border: 1px solid #d1d5db;
            background: #fff;
            outline: none;
            transition: border-color 0.2s;
            width: 100%;
            padding: 0.6rem 0.9rem;
            font-family: 'Roboto', sans-serif;
        }
        .admin-input:focus { border-color: #111111; }

        /* ── Modal backdrop ────────────────────── */
        .modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 60;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden;
            transition: opacity 0.25s, visibility 0.25s;
        }
        .modal-backdrop.open { opacity:1; visibility:visible; }
        .modal-box {
            background: #fff;
            max-width: 640px; width: 100%;
            max-height: 90vh; overflow-y: auto;
            padding: 2.5rem;
            transform: translateY(20px);
            transition: transform 0.3s cubic-bezier(0.16,1,0.3,1);
        }
        .modal-backdrop.open .modal-box { transform: translateY(0); }

        /* ── Media grid card ───────────────────── */
        .media-card {
            position: relative; overflow: hidden;
            background: #e5e5e5;
            aspect-ratio: 4/3;
        }
        .media-card img { width:100%; height:100%; object-fit:cover; }
        .media-card-overlay {
            position: absolute; inset: 0;
            background: rgba(0,0,0,0.5);
            opacity: 0;
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.25s;
        }
        .media-card:hover .media-card-overlay { opacity: 1; }

        /* ── Btn primary ───────────────────────── */
        .btn-primary {
            background: #111111; color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600; letter-spacing: 0.07em; font-size: 0.78rem;
            padding: 0.65rem 1.4rem;
            text-transform: uppercase;
            cursor: pointer; border: none;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-primary:hover { background: #333; transform: translateY(-1px); }

        .btn-accent {
            background: #E2FF00; color: #111111;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700; letter-spacing: 0.07em; font-size: 0.78rem;
            padding: 0.65rem 1.4rem;
            text-transform: uppercase;
            cursor: pointer; border: none;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-accent:hover { opacity: 0.85; transform: translateY(-1px); }

        .btn-outline {
            background: transparent; color: #111111;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600; letter-spacing: 0.06em; font-size: 0.78rem;
            padding: 0.6rem 1.2rem;
            text-transform: uppercase;
            cursor: pointer; border: 1px solid #d1d5db;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-outline:hover { border-color: #111111; background: #f7f7f5; }

        /* ── Section tab nav ───────────────────── */
        .tab-btn { border-bottom: 2px solid transparent; transition: border-color 0.2s, color 0.2s; }
        .tab-btn.active { border-color: #111111; color: #111111; font-weight: 600; }

        /* ── Image preview ─────────────────────── */
        #product-img-preview, #media-img-preview { display:none; }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════════════════════════ --}}
<div id="admin-sidebar">
    <!-- Logo -->
    <div class="px-7 py-6 border-b border-white/10">
        <a href="/" class="block">
            <p class="font-montserrat font-bold text-xl tracking-[0.3em] uppercase text-white">NOTISSE</p>
            <p class="font-roboto text-[10px] text-white/40 tracking-widest mt-0.5 uppercase">Admin Panel</p>
        </a>
    </div>

    <!-- Nav -->
    <nav class="flex-1 py-6 space-y-0.5">
        <a href="#section-products" onclick="showSection('products')"
           class="nav-link active flex items-center gap-3 px-5 py-3 text-sm font-roboto tracking-wide text-white/80"
           id="nav-products">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span>Products</span>
        </a>
        <a href="#section-media" onclick="showSection('media')"
           class="nav-link flex items-center gap-3 px-5 py-3 text-sm font-roboto tracking-wide text-white/80"
           id="nav-media">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span>Site Media</span>
        </a>
        <a href="#section-orders" onclick="showSection('orders')"
           class="nav-link flex items-center gap-3 px-5 py-3 text-sm font-roboto tracking-wide text-white/80"
           id="nav-orders">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 17H5a2 2 0 0 0-2 2v2h14v-2a2 2 0 0 0-2-2h-4z"/><path d="M12 12V3"/><path d="M8 7l4-4 4 4"/></svg>
            <span>Orders</span>
        </a>
    </nav>

    <!-- Bottom links -->
    <div class="px-5 py-5 border-t border-white/10 space-y-2">
        <form action="/admin/biteship/sync" method="POST" class="w-full">
            @csrf
            <button type="submit" class="w-full btn-accent text-left px-4 py-2.5 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                Sync Biteship
            </button>
        </form>
        <a href="/" class="block text-xs text-white/40 hover:text-white/80 tracking-widest uppercase transition-colors pt-1">
            ← View Store
        </a>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MAIN WRAPPER
══════════════════════════════════════════════════════════════ --}}
<div style="margin-left:240px; min-height:100vh;" class="flex flex-col">

    <!-- Top bar -->
    <header class="sticky top-0 z-30 bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
        <div>
            <h1 class="font-montserrat font-bold text-base uppercase tracking-[0.2em]">Dashboard</h1>
            <p class="text-xs text-gray-400 font-roboto tracking-wide mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="flex items-center gap-4">
            @if(session('success'))
            <div id="toast-success" class="flex items-center gap-2 bg-accentYellow px-4 py-2 text-xs font-montserrat font-bold uppercase tracking-wide text-brandDark">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
            @endif
        </div>
    </header>

    <!-- ── STAT CARDS ───────────────────────────────────────────── -->
    <div class="px-8 pt-8 pb-4 grid grid-cols-3 gap-6">
        <div class="bg-white border border-gray-200 p-6 section-card">
            <p class="text-xs font-montserrat uppercase tracking-widest text-gray-400 mb-2">Total Products</p>
            <p class="font-montserrat font-bold text-3xl text-brandDark">{{ $products->count() }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-6 section-card" style="animation-delay:0.05s">
            <p class="text-xs font-montserrat uppercase tracking-widest text-gray-400 mb-2">Total Orders</p>
            <p class="font-montserrat font-bold text-3xl text-brandDark">{{ $orders->count() }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-6 section-card" style="animation-delay:0.1s">
            <p class="text-xs font-montserrat uppercase tracking-widest text-gray-400 mb-2">Revenue (IDR)</p>
            <p class="font-montserrat font-bold text-3xl text-brandDark">
                {{ 'Rp ' . number_format($orders->where('status','paid')->sum('total_amount'), 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- ── MAIN CONTENT SECTIONS ───────────────────────────────── -->
    <main class="flex-1 px-8 pb-16 space-y-10">

        {{-- ─────────────────────────────────────────────────────
             SECTION: PRODUCTS
        ───────────────────────────────────────────────────────── --}}
        <section id="section-products" class="section-card">
            <div class="flex justify-between items-center mb-5">
                <h2 class="font-montserrat font-bold text-lg uppercase tracking-[0.18em]">Products</h2>
                <button onclick="openAddProductModal()" class="btn-primary flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Product
                </button>
            </div>

            <div class="bg-white border border-gray-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr class="text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500">
                            <th class="px-5 py-3.5">Product</th>
                            <th class="px-5 py-3.5">Price (IDR)</th>
                            <th class="px-5 py-3.5">Stock & Sizes</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                        <tr class="admin-tr">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gray-100 border border-gray-200 overflow-hidden shrink-0">
                                        @if($product->image)
                                            @php
                                                if (Str::startsWith($product->image, 'http')) {
                                                    $imgUrl = $product->image;
                                                } elseif (file_exists(public_path('storage/' . $product->image))) {
                                                    $imgUrl = asset('storage/' . $product->image);
                                                } else {
                                                    $imgUrl = asset($product->image);
                                                }
                                            @endphp
                                            <img src="{{ $imgUrl }}" class="w-full h-full object-cover" onerror="this.style.display='none'">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-roboto font-medium text-sm text-gray-900">{{ $product->name }}</p>
                                        @if($product->description)
                                        <p class="text-xs text-gray-400 mt-0.5 line-clamp-1 max-w-xs">{{ $product->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-roboto text-sm text-gray-700">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($product->stocks as $stock)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 border border-gray-200 text-[11px] font-montserrat font-semibold {{ $stock->stock < 5 ? 'border-red-200 text-red-600 bg-red-50' : 'text-gray-600 bg-gray-50' }}">
                                        {{ $stock->size }}
                                        <span class="font-normal">{{ $stock->stock }}</span>
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button onclick="openEditProductModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->price }}', '{{ addslashes($product->description ?? '') }}', {{ json_encode($product->stocks->pluck('stock','size')) }})"
                                        class="text-xs font-montserrat font-semibold uppercase tracking-wide text-blue-600 hover:text-blue-800 transition-colors">
                                        Edit
                                    </button>
                                    <form action="/admin/products/{{ $product->id }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin hapus produk {{ addslashes($product->name) }}?')">
                                        @method('DELETE') @csrf
                                        <button type="submit"
                                            class="text-xs font-montserrat font-semibold uppercase tracking-wide text-red-500 hover:text-red-700 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-400 font-roboto">
                                No products yet. Add your first product above.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────
             SECTION: SITE MEDIA (CAROUSEL & FOOTAGE)
        ───────────────────────────────────────────────────────── --}}
        <section id="section-media" class="section-card hidden">
            <h2 class="font-montserrat font-bold text-lg uppercase tracking-[0.18em] mb-2">Site Media</h2>
            <p class="text-xs text-gray-400 font-roboto mb-6">Kelola gambar Hero Carousel dan Footage/Look section yang tampil di halaman utama toko.</p>

            <!-- TAB: Carousel -->
            <div class="flex gap-0 border-b border-gray-200 mb-6">
                <button onclick="switchMediaTab('carousel')" id="tab-carousel"
                    class="tab-btn active px-5 py-2.5 text-xs font-montserrat uppercase tracking-widest text-gray-500">
                    Hero Carousel
                </button>
                <button onclick="switchMediaTab('footage')" id="tab-footage"
                    class="tab-btn px-5 py-2.5 text-xs font-montserrat uppercase tracking-widest text-gray-500">
                    Footage / Look
                </button>
            </div>

            <!-- Carousel Grid -->
            <div id="media-tab-carousel" class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($carousels as $media)
                <div class="bg-white border border-gray-200 overflow-hidden group">
                    <div class="media-card">
                        @php
                            $mUrl = Str::startsWith($media->image,'http') ? $media->image : (file_exists(public_path('storage/'.$media->image)) ? asset('storage/'.$media->image) : asset($media->image));
                        @endphp
                        <img src="{{ $mUrl }}" alt="{{ $media->title }}" onerror="this.style.display='none'">
                        <div class="media-card-overlay">
                            <button onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}')"
                                class="btn-accent text-xs px-4 py-2">
                                Ganti Gambar
                            </button>
                        </div>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-xs font-montserrat font-semibold uppercase tracking-wide text-gray-700">{{ $media->title ?? 'Slide ' . $loop->iteration }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ ucfirst($media->section) }} · Order {{ $media->order }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Footage Grid -->
            <div id="media-tab-footage" class="grid grid-cols-1 md:grid-cols-3 gap-5 hidden">
                @foreach($footages as $media)
                <div class="bg-white border border-gray-200 overflow-hidden group">
                    <div class="media-card">
                        @php
                            $mUrl = Str::startsWith($media->image,'http') ? $media->image : (file_exists(public_path('storage/'.$media->image)) ? asset('storage/'.$media->image) : asset($media->image));
                        @endphp
                        <img src="{{ $mUrl }}" alt="{{ $media->title }}" onerror="this.style.display='none'">
                        <div class="media-card-overlay">
                            <button onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}')"
                                class="btn-accent text-xs px-4 py-2">
                                Ganti Gambar
                            </button>
                        </div>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-xs font-montserrat font-semibold uppercase tracking-wide text-gray-700">{{ $media->title ?? 'Footage ' . $loop->iteration }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ ucfirst($media->section) }} · Order {{ $media->order }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────
             SECTION: ORDERS
        ───────────────────────────────────────────────────────── --}}
        <section id="section-orders" class="section-card hidden">
            <h2 class="font-montserrat font-bold text-lg uppercase tracking-[0.18em] mb-5">Recent Orders</h2>

            <div class="bg-white border border-gray-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr class="text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500">
                            <th class="px-5 py-3.5">Order ID</th>
                            <th class="px-5 py-3.5">Customer</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Amount (IDR)</th>
                            <th class="px-5 py-3.5">Courier</th>
                            <th class="px-5 py-3.5">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                        <tr class="admin-tr">
                            <td class="px-5 py-4 font-mono text-xs text-gray-500">#{{ $order->id }}</td>
                            <td class="px-5 py-4">
                                <p class="text-sm font-roboto font-medium text-gray-900">{{ $order->customer_name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $order->customer_email ?? '' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-block px-2.5 py-1 text-[11px] font-montserrat font-bold uppercase tracking-wide
                                    {{ $order->status === 'paid' ? 'badge-paid' : ($order->status === 'cancelled' ? 'badge-cancelled' : 'badge-pending') }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm font-roboto text-gray-700">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500">{{ $order->courier_name ?? '—' }}</td>
                            <td class="px-5 py-4 text-xs text-gray-400 font-roboto">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400 font-roboto">No orders yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL: ADD / EDIT PRODUCT
══════════════════════════════════════════════════════════════ --}}
<div id="modal-product" class="modal-backdrop" onclick="closeProductModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6">
            <h3 id="modal-product-title" class="font-montserrat font-bold text-xl uppercase tracking-[0.15em]">Add Product</h3>
            <button onclick="closeProductModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors">&times;</button>
        </div>

        <form id="product-form" action="/admin/products" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST" disabled>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Product Name *</label>
                <input type="text" name="name" id="p-name" required class="admin-input">
            </div>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Price (IDR) *</label>
                <input type="number" name="price" id="p-price" required class="admin-input">
            </div>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Description</label>
                <textarea name="description" id="p-desc" rows="3" class="admin-input resize-none"></textarea>
            </div>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Product Image</label>
                <input type="file" name="image" id="product-img-input" accept="image/*"
                    onchange="previewImage(this,'product-img-preview')"
                    class="admin-input py-2 text-sm bg-gray-50 cursor-pointer">
                <img id="product-img-preview" class="mt-3 w-32 h-32 object-cover border border-gray-200" src="" alt="Preview">
            </div>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500 mb-2">Stock per Size</label>
                <div class="grid grid-cols-4 gap-3">
                    @foreach(['S','M','L','XL'] as $sz)
                    <div>
                        <span class="block text-[11px] font-montserrat font-bold text-gray-400 mb-1 text-center">{{ $sz }}</span>
                        <input type="number" name="stocks[{{ $sz }}]" id="p-stock-{{ $sz }}" min="0"
                            class="admin-input text-center" placeholder="0">
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeProductModal()" class="btn-outline">Cancel</button>
                <button type="submit" class="btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL: EDIT SITE MEDIA
══════════════════════════════════════════════════════════════ --}}
<div id="modal-media" class="modal-backdrop" onclick="closeMediaModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-montserrat font-bold text-xl uppercase tracking-[0.15em]">Ganti Gambar</h3>
            <button onclick="closeMediaModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors">&times;</button>
        </div>

        <form id="media-form" action="" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Title (opsional)</label>
                <input type="text" name="title" id="m-title" class="admin-input">
            </div>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Upload Gambar Baru</label>
                <input type="file" name="image" id="media-img-input" accept="image/*"
                    onchange="previewImage(this,'media-img-preview')"
                    class="admin-input py-2 text-sm bg-gray-50 cursor-pointer">
                <img id="media-img-preview" class="mt-3 w-full max-h-48 object-cover border border-gray-200" src="" alt="Preview">
                <p class="text-[11px] text-gray-400 mt-1.5 font-roboto">Biarkan kosong jika tidak ingin mengganti gambar.</p>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeMediaModal()" class="btn-outline">Cancel</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════════════════ --}}
<script>
    // ── Section switcher ────────────────────────────────────────
    function showSection(name) {
        ['products','media','orders'].forEach(s => {
            const el = document.getElementById('section-' + s);
            if (el) el.classList.toggle('hidden', s !== name);
        });
        ['products','media','orders'].forEach(s => {
            const nav = document.getElementById('nav-' + s);
            if (nav) nav.classList.toggle('active', s === name);
        });
    }

    // ── Media tab switcher ──────────────────────────────────────
    function switchMediaTab(tab) {
        ['carousel','footage'].forEach(t => {
            const pane = document.getElementById('media-tab-' + t);
            const btn  = document.getElementById('tab-' + t);
            if (pane) pane.classList.toggle('hidden', t !== tab);
            if (btn)  btn.classList.toggle('active', t === tab);
        });
    }

    // ── Image preview ───────────────────────────────────────────
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ── Product modal ───────────────────────────────────────────
    function openAddProductModal() {
        document.getElementById('modal-product-title').textContent = 'Add Product';
        document.getElementById('product-form').action = '/admin/products';
        document.getElementById('product-form').reset();
        document.getElementById('form-method').disabled = true;
        document.getElementById('product-img-preview').style.display = 'none';
        document.getElementById('modal-product').classList.add('open');
    }

    function openEditProductModal(id, name, price, desc, stocksObj) {
        document.getElementById('modal-product-title').textContent = 'Edit Product';
        document.getElementById('product-form').action = '/admin/products/' + id;
        document.getElementById('form-method').value   = 'PUT';
        document.getElementById('form-method').disabled = false;

        document.getElementById('p-name').value  = name;
        document.getElementById('p-price').value = price;
        document.getElementById('p-desc').value  = desc;
        document.getElementById('product-img-preview').style.display = 'none';

        ['S','M','L','XL'].forEach(s => {
            const el = document.getElementById('p-stock-' + s);
            if (el) el.value = stocksObj[s] !== undefined ? stocksObj[s] : '';
        });

        document.getElementById('modal-product').classList.add('open');
    }

    function closeProductModal() {
        document.getElementById('modal-product').classList.remove('open');
    }

    // ── Media modal ─────────────────────────────────────────────
    function openMediaModal(id, title, desc) {
        document.getElementById('media-form').action = '/admin/media/' + id;
        document.getElementById('m-title').value     = title;
        document.getElementById('media-img-preview').style.display = 'none';
        document.getElementById('media-img-input').value = '';
        document.getElementById('modal-media').classList.add('open');
    }

    function closeMediaModal() {
        document.getElementById('modal-media').classList.remove('open');
    }

    // ── Auto-dismiss toast ───────────────────────────────────────
    const toast = document.getElementById('toast-success');
    if (toast) setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.5s'; setTimeout(() => toast.remove(), 500); }, 4000);

    // ── Keyboard ESC to close modals ─────────────────────────────
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeProductModal();
            closeMediaModal();
        }
    });
</script>

</body>
</html>
