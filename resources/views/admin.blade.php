<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notisse — Atelier Admin Panel</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts – same as welcome.blade.php -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">

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
                        brandRed:     '#d52c2b',
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
        ::-webkit-scrollbar      { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #aaaaaa; border-radius: 2px; }

        /* ── Sidebar ───────────────────────────── */
        #admin-sidebar {
            width: 250px;
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
        .nav-link { transition: background 0.2s, padding-left 0.2s, color 0.2s; }
        .nav-link:hover, .nav-link.active {
            background: #E2FF00;
            color: #111111;
            font-weight: 700;
            padding-left: 1.5rem;
        }
        .nav-link span { pointer-events: none; }

        /* ── Table row hover ───────────────────── */
        .admin-tr { transition: background 0.15s; }
        .admin-tr:hover { background: #fafafa; }

        /* ── Badges ────────────────────────────── */
        .badge-paid       { background:#d1fae5; color:#065f46; border: 1px solid #a7f3d0; }
        .badge-cancelled  { background:#fee2e2; color:#991b1b; border: 1px solid #fecaca; }
        .badge-pending    { background:#fef9c3; color:#854d0e; border: 1px solid #fef08a; }

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
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
            padding: 0.65rem 0.9rem;
            font-size: 13px;
            font-family: 'Roboto', sans-serif;
            border-radius: 2px;
        }
        .admin-input:focus {
            border-color: #111111;
            box-shadow: 0 0 0 1px #111111;
        }

        /* ── Modal backdrop ────────────────────── */
        .modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(2px);
            z-index: 60;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden;
            transition: opacity 0.25s, visibility 0.25s;
        }
        .modal-backdrop.open { opacity: 1; visibility: visible; }
        .modal-box {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            max-width: 720px; width: 94%;
            max-height: 90vh; overflow-y: auto;
            padding: 2.2rem;
            transform: translateY(20px);
            transition: transform 0.3s cubic-bezier(0.16,1,0.3,1);
            border-radius: 2px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
            background: rgba(0,0,0,0.55);
            opacity: 0;
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.25s;
        }
        .media-card:hover .media-card-overlay { opacity: 1; }

        /* ── Buttons ───────────────────────────── */
        .btn-primary {
            background: #111111; color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700; letter-spacing: 0.08em; font-size: 0.78rem;
            padding: 0.7rem 1.4rem;
            text-transform: uppercase;
            cursor: pointer; border: none;
            transition: background 0.2s, transform 0.15s;
            border-radius: 2px;
        }
        .btn-primary:hover { background: #333333; transform: translateY(-1px); }

        .btn-accent {
            background: #E2FF00; color: #111111;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700; letter-spacing: 0.08em; font-size: 0.78rem;
            padding: 0.7rem 1.4rem;
            text-transform: uppercase;
            cursor: pointer; border: none;
            transition: opacity 0.2s, transform 0.15s;
            border-radius: 2px;
        }
        .btn-accent:hover { opacity: 0.9; transform: translateY(-1px); }

        .btn-outline {
            background: transparent; color: #111111;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600; letter-spacing: 0.06em; font-size: 0.78rem;
            padding: 0.65rem 1.2rem;
            text-transform: uppercase;
            cursor: pointer; border: 1px solid #d1d5db;
            transition: border-color 0.2s, background 0.2s;
            border-radius: 2px;
        }
        .btn-outline:hover { border-color: #111111; background: #f7f7f5; }

        /* ── Tab nav ───────────────────────────── */
        .tab-btn { border-bottom: 2px solid transparent; transition: border-color 0.2s, color 0.2s; }
        .tab-btn.active { border-color: #111111; color: #111111; font-weight: 700; }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════════════
     SIDEBAR (Original Notisse Red Brand Style)
══════════════════════════════════════════════════════════════ --}}
<div id="admin-sidebar">
    <!-- Logo -->
    <div class="px-7 py-6 border-b border-white/15">
        <a href="/" class="block group">
            <p class="font-montserrat font-extrabold text-xl tracking-[0.3em] uppercase text-white">NOTISSE</p>
            <p class="font-roboto text-[10px] text-white/70 tracking-widest mt-0.5 uppercase">Atelier Admin Panel</p>
        </a>
    </div>

    <!-- Nav -->
    <nav class="flex-1 py-6 space-y-1 overflow-y-auto">
        <a href="#section-products" onclick="showSection('products')"
           class="nav-link active flex items-center gap-3 px-6 py-3.5 text-xs font-montserrat tracking-wider uppercase text-white/90"
           id="nav-products">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span>Products</span>
        </a>

        <a href="#section-artists" onclick="showSection('artists')"
           class="nav-link flex items-center gap-3 px-6 py-3.5 text-xs font-montserrat tracking-wider uppercase text-white/90"
           id="nav-artists">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Artists (Seniman)</span>
        </a>

        <a href="#section-artworks" onclick="showSection('artworks')"
           class="nav-link flex items-center gap-3 px-6 py-3.5 text-xs font-montserrat tracking-wider uppercase text-white/90"
           id="nav-artworks">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>Artworks & Editorial</span>
        </a>

        <a href="#section-media" onclick="showSection('media')"
           class="nav-link flex items-center gap-3 px-6 py-3.5 text-xs font-montserrat tracking-wider uppercase text-white/90"
           id="nav-media">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span>Site Media</span>
        </a>

        <a href="#section-orders" onclick="showSection('orders')"
           class="nav-link flex items-center gap-3 px-6 py-3.5 text-xs font-montserrat tracking-wider uppercase text-white/90"
           id="nav-orders">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17H5a2 2 0 0 0-2 2v2h14v-2a2 2 0 0 0-2-2h-4z"/><path d="M12 12V3"/><path d="M8 7l4-4 4 4"/></svg>
            <span>Orders</span>
        </a>
    </nav>

    <!-- Bottom links -->
    <div class="px-6 py-5 border-t border-white/15 space-y-2.5">
        <form action="/admin/biteship/sync" method="POST" class="w-full">
            @csrf
            <button type="submit" class="w-full btn-accent text-left px-4 py-2.5 flex items-center justify-between shadow-sm cursor-pointer">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                    Sync Biteship
                </span>
                <span class="text-[9px] font-mono text-black font-semibold">API</span>
            </button>
        </form>
        <a href="/" target="_blank" class="block text-xs text-white/80 hover:text-white tracking-widest uppercase transition-colors pt-1 font-montserrat">
            &larr; View Store Web
        </a>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MAIN WRAPPER (Clean Notisse Off-White & Crisp Cards)
══════════════════════════════════════════════════════════════ --}}
<div style="margin-left: 250px; min-height: 100vh;" class="flex flex-col bg-[#f7f7f5]">

    <!-- Top bar -->
    <header class="sticky top-0 z-30 bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center shadow-sm">
        <div>
            <h1 class="font-montserrat font-bold text-base uppercase tracking-[0.2em] text-brandDark" id="page-title">
                Products Catalog Management
            </h1>
            <p class="text-xs text-gray-400 font-roboto tracking-wide mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="flex items-center gap-4">
            @if(session('success'))
            <div id="toast-success" class="flex items-center gap-2 bg-accentYellow px-4 py-2 text-xs font-montserrat font-bold uppercase tracking-wide text-brandDark border border-black/10 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
            @endif
            <a href="/" target="_blank" class="text-xs font-montserrat font-semibold tracking-wider uppercase border border-gray-300 hover:border-black px-3.5 py-1.5 transition-colors text-gray-700 hover:text-black">
                Lihat Toko &rarr;
            </a>
        </div>
    </header>

    <!-- ── STAT CARDS ───────────────────────────────────────────── -->
    <div class="px-8 pt-8 pb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm">
            <p class="text-[11px] font-montserrat font-bold uppercase tracking-widest text-gray-400 mb-1">Total Products</p>
            <p class="font-montserrat font-extrabold text-2xl text-brandDark">{{ $stats['total_products'] ?? $products->count() }}</p>
            <span class="text-[10px] text-gray-400 font-roboto mt-0.5 block">Katalog item toko</span>
        </div>
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm" style="animation-delay:0.05s">
            <p class="text-[11px] font-montserrat font-bold uppercase tracking-widest text-gray-400 mb-1">Physical Stock</p>
            <p class="font-montserrat font-extrabold text-2xl text-brandDark">{{ $stats['total_stock'] ?? 0 }}</p>
            <span class="text-[10px] text-gray-400 font-roboto mt-0.5 block">Total size S - XL</span>
        </div>
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm" style="animation-delay:0.1s">
            <p class="text-[11px] font-montserrat font-bold uppercase tracking-widest text-gray-400 mb-1">Artists Collab</p>
            <p class="font-montserrat font-extrabold text-2xl text-brandDark">{{ $stats['total_artists'] ?? $artists->count() }}</p>
            <span class="text-[10px] text-gray-400 font-roboto mt-0.5 block">Seniman kolaborator</span>
        </div>
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm" style="animation-delay:0.15s">
            <p class="text-[11px] font-montserrat font-bold uppercase tracking-widest text-gray-400 mb-1">Artworks & Editorial</p>
            <p class="font-montserrat font-extrabold text-2xl text-brandDark">{{ $stats['total_artworks'] ?? $artworks->count() }}</p>
            <span class="text-[10px] text-gray-400 font-roboto mt-0.5 block">Karya seni galeri</span>
        </div>
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm" style="animation-delay:0.2s">
            <p class="text-[11px] font-montserrat font-bold uppercase tracking-widest text-gray-400 mb-1">Revenue (IDR)</p>
            <p class="font-montserrat font-extrabold text-xl text-brandDark">
                Rp {{ number_format($stats['paid_revenue'] ?? $orders->where('status','paid')->sum('total_amount'), 0, ',', '.') }}
            </p>
            <span class="text-[10px] text-gray-400 font-roboto mt-0.5 block">{{ $stats['total_orders'] ?? $orders->count() }} Pesanan</span>
        </div>
    </div>

    <!-- ── MAIN CONTENT SECTIONS ───────────────────────────────── -->
    <main class="flex-1 px-8 pb-16 space-y-10">

        {{-- ─────────────────────────────────────────────────────
             SECTION 1: PRODUCTS
        ───────────────────────────────────────────────────────── --}}
        <section id="section-products" class="section-card">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                <div>
                    <h2 class="font-montserrat font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Products Catalog</h2>
                    <p class="text-xs text-gray-500 font-roboto mt-0.5">Kelola produk pakaian, deskripsi editorial, spesifikasi fabric, seniman, foto (Depan, Belakang, Footage, Size Chart), dan stok per ukuran.</p>
                </div>
                <button onclick="openAddProductModal()" class="btn-primary flex items-center gap-2 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>Add Product</span>
                </button>
            </div>

            <div class="bg-white border border-gray-200 overflow-x-auto shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr class="text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500">
                            <th class="px-5 py-3.5">Product & Artist</th>
                            <th class="px-5 py-3.5">Price (IDR)</th>
                            <th class="px-5 py-3.5">Stock & Sizes</th>
                            <th class="px-5 py-3.5">Foto Produk (Depan, Belakang, Footage, Size Chart)</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                        <tr class="admin-tr">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-16 bg-gray-100 border border-gray-200 overflow-hidden shrink-0">
                                        @php
                                            $img = $product->image;
                                            if ($img) {
                                                $imgUrl = Str::startsWith($img, 'http') ? $img : (file_exists(public_path('storage/' . $img)) ? asset('storage/' . $img) : asset($img));
                                            } else {
                                                $imgUrl = asset('footage-baju.jpg');
                                            }
                                        @endphp
                                        <img src="{{ $imgUrl }}" class="w-full h-full object-cover" onerror="this.src='/footage-baju.jpg'">
                                    </div>
                                    <div>
                                        <p class="font-montserrat font-bold text-sm text-gray-900 uppercase">{{ $product->name }}</p>
                                        <span class="inline-block text-[10px] font-montserrat font-semibold uppercase tracking-wider bg-gray-100 text-gray-600 px-2 py-0.5 mt-1 border border-gray-200">
                                            {{ $product->artist ?: 'In-House Atelier' }}
                                        </span>
                                        @if($product->specs)
                                        <p class="text-[10px] text-gray-400 font-mono mt-0.5 line-clamp-1 max-w-xs">{{ $product->specs }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-montserrat font-bold text-sm text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($product->stocks as $stock)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 border text-[11px] font-montserrat font-semibold {{ $stock->stock < 5 ? 'border-red-200 text-red-600 bg-red-50' : 'border-gray-200 text-gray-700 bg-gray-50' }}">
                                        {{ $stock->size }}:
                                        <span class="font-bold">{{ $stock->stock }}</span>
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1 text-[11px] font-roboto">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $product->image ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                        <span class="text-gray-600">Cover Depan: <strong class="font-montserrat">{{ $product->image ? 'Ada' : 'Default' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $product->back_image ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                        <span class="text-gray-600">Belakang: <strong class="font-montserrat">{{ $product->back_image ? 'Ada' : 'Default' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $product->footage_image ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                        <span class="text-gray-600">On-Model Footage: <strong class="font-montserrat">{{ $product->footage_image ? 'Ada' : 'Default' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $product->size_chart_image ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                        <span class="text-gray-600">Size Chart Guide: <strong class="font-montserrat">{{ $product->size_chart_image ? 'Ada' : 'Default' }}</strong></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3 font-montserrat">
                                    <button onclick="openEditProductModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->price }}', '{{ addslashes($product->description ?? '') }}', '{{ addslashes($product->specs ?? '') }}', '{{ addslashes($product->artist ?? '') }}', {{ json_encode($product->stocks->pluck('stock','size')) }}, '{{ $product->image ? asset($product->image) : '' }}', '{{ $product->back_image ? asset($product->back_image) : '' }}', '{{ $product->footage_image ? asset($product->footage_image) : '' }}', '{{ $product->size_chart_image ? asset($product->size_chart_image) : '' }}')"
                                        class="text-xs font-semibold uppercase tracking-wide text-blue-600 hover:text-blue-800 transition-colors cursor-pointer">
                                        Edit
                                    </button>
                                    <form action="/admin/products/{{ $product->id }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin hapus produk {{ addslashes($product->name) }}?')">
                                        @method('DELETE') @csrf
                                        <button type="submit"
                                            class="text-xs font-semibold uppercase tracking-wide text-red-500 hover:text-red-700 transition-colors cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400 font-roboto">
                                Belum ada produk. Tambahkan produk pertama di atas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────
             SECTION 2: ARTISTS / SENIMAN (NEW!)
        ───────────────────────────────────────────────────────── --}}
        <section id="section-artists" class="section-card hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                <div>
                    <h2 class="font-montserrat font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Artists & Collaborators (Seniman)</h2>
                    <p class="text-xs text-gray-500 font-roboto mt-0.5">Kelola profil seniman kolaborator Notisse (seperti Mustafa Alatas, dll), kota domisili, biografi, dan karya seni yang tampil di halaman 'Artist Collab'.</p>
                </div>
                <button onclick="openAddArtistModal()" class="btn-primary flex items-center gap-2 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>Add Artist (Tambah Seniman)</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($artists as $art)
                <div class="bg-white border border-gray-200 overflow-hidden flex flex-col justify-between group shadow-sm">
                    <div>
                        <!-- Artist Portrait -->
                        <div class="aspect-square bg-gray-100 relative overflow-hidden border-b border-gray-200">
                            @php
                                $photoUrl = $art->photo ? (Str::startsWith($art->photo, 'http') ? $art->photo : (file_exists(public_path('storage/' . $art->photo)) ? asset('storage/' . $art->photo) : asset($art->photo))) : 'https://placehold.co/500x500/181818/ffffff?text=' . urlencode($art->name);
                            @endphp
                            <img src="{{ $photoUrl }}" alt="{{ $art->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @if($art->city)
                            <div class="absolute bottom-2.5 left-2.5">
                                <span class="text-[9px] font-bold font-montserrat tracking-widest uppercase bg-black text-white px-2 py-0.5 shadow-sm">
                                    {{ $art->city }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="p-4 space-y-2">
                            <h3 class="font-montserrat font-bold text-base text-gray-900 uppercase">{{ $art->name }}</h3>
                            <p class="line-clamp-3 text-xs text-gray-500 font-roboto leading-relaxed">
                                {{ $art->bio ?: 'Seniman kolaborator eksklusif lini tekstil dan garmen atelier Notisse.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between font-montserrat text-xs">
                        <span class="text-[10px] text-gray-400 font-mono">ID: #ART-{{ $art->id }}</span>
                        <div class="flex items-center gap-3">
                            <button onclick="openEditArtistModal({{ $art->id }}, '{{ addslashes($art->name) }}', '{{ addslashes($art->city ?? '') }}', '{{ addslashes($art->bio ?? '') }}', '{{ $art->photo ? asset($art->photo) : '' }}', '{{ $art->artwork_preview ? asset($art->artwork_preview) : '' }}')"
                                class="font-semibold uppercase tracking-wide text-blue-600 hover:text-blue-800 transition-colors cursor-pointer">
                                Edit
                            </button>
                            <form action="/admin/artists/{{ $art->id }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus seniman {{ addslashes($art->name) }}?')">
                                @method('DELETE') @csrf
                                <button type="submit" class="font-semibold uppercase tracking-wide text-red-500 hover:text-red-700 transition-colors cursor-pointer">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full p-12 bg-white border border-gray-200 text-center text-gray-400 font-roboto text-sm">
                    Belum ada data seniman. Klik "Add Artist (Tambah Seniman)" di atas untuk menambahkan.
                </div>
                @endforelse
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────
             SECTION 3: ARTWORKS & ARTIST EDITORIAL
        ───────────────────────────────────────────────────────── --}}
        <section id="section-artworks" class="section-card hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                <div>
                    <h2 class="font-montserrat font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Artworks & Artist Editorial</h2>
                    <p class="text-xs text-gray-500 font-roboto mt-0.5">Kelola karya seni orisinil untuk program penawaran akuisisi kolektor serta editorial seniman kolaborator.</p>
                </div>
                <button onclick="openAddArtworkModal()" class="btn-primary flex items-center gap-2 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>Add Artwork / Editorial</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($artworks as $art)
                <div class="bg-white border border-gray-200 overflow-hidden flex flex-col justify-between group shadow-sm">
                    <div>
                        <!-- Thumbnail -->
                        <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden border-b border-gray-200">
                            @php
                                $aImg = $art->image;
                                if ($aImg) {
                                    if (Str::startsWith($aImg, 'http')) { $artUrl = $aImg; }
                                    elseif (file_exists(public_path('storage/' . $aImg))) { $artUrl = asset('storage/' . $aImg); }
                                    else { $artUrl = asset($aImg); }
                                } else {
                                    $artUrl = asset('footagebaju2.jpg');
                                }
                            @endphp
                            <img src="{{ $artUrl }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='/footagebaju2.jpg'">
                            <div class="absolute top-3 left-3">
                                <span class="text-[9px] font-bold font-montserrat tracking-widest uppercase bg-black text-white px-2 py-0.5 shadow-sm">
                                    {{ $art->status ?: 'Available for Acquisition' }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-2.5">
                            <div>
                                <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-400">{{ $art->artist_name ?: 'Curated Artist' }} &bull; {{ $art->year ?: '2024' }}</span>
                                <h3 class="font-montserrat font-bold text-base text-gray-900 uppercase mt-0.5">{{ $art->title }}</h3>
                            </div>
                            <div class="text-xs text-gray-600 font-roboto space-y-1">
                                <p><strong class="text-gray-900">Medium:</strong> {{ $art->medium ?: 'Oil on Canvas' }}</p>
                                <p class="line-clamp-2 text-gray-500 leading-relaxed">{{ $art->description ?: 'Karya seni orisinil dalam program kolaborasi galeri Notisse.' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[10px] font-mono text-gray-400 uppercase">REF: #NTS-ART-{{ $art->id }}</span>
                        <div class="flex items-center gap-3 font-montserrat text-xs">
                            <button onclick="openEditArtworkModal({{ $art->id }}, '{{ addslashes($art->title) }}', '{{ addslashes($art->artist_name ?? '') }}', '{{ addslashes($art->medium ?? '') }}', '{{ addslashes($art->year ?? '') }}', '{{ addslashes($art->status ?? '') }}', '{{ addslashes($art->description ?? '') }}')"
                                class="font-semibold uppercase tracking-wide text-blue-600 hover:text-blue-800 transition-colors cursor-pointer">
                                Edit
                            </button>
                            <form action="/admin/artworks/{{ $art->id }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus karya seni {{ addslashes($art->title) }}?')">
                                @method('DELETE') @csrf
                                <button type="submit" class="font-semibold uppercase tracking-wide text-red-500 hover:text-red-700 transition-colors cursor-pointer">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full p-12 bg-white border border-gray-200 text-center text-gray-400 font-roboto text-sm">
                    Belum ada karya seni atau artist editorial. Klik "Add Artwork / Editorial" di atas untuk menambahkan.
                </div>
                @endforelse
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────
             SECTION 4: SITE MEDIA (CAROUSEL & FOOTAGE)
        ───────────────────────────────────────────────────────── --}}
        <section id="section-media" class="section-card hidden">
            <h2 class="font-montserrat font-extrabold text-lg uppercase tracking-[0.18em] mb-2 text-brandDark">Site Media & Lookbook Footage</h2>
            <p class="text-xs text-gray-500 font-roboto mb-6">Kelola Hero Carousel slides dan Footage Photoshoot yang tampil di beranda toko. Admin juga bisa menautkan gambar footage ke produk tertentu.</p>

            <!-- TAB: Carousel vs Footage -->
            <div class="flex gap-0 border-b border-gray-200 mb-6">
                <button onclick="switchMediaTab('carousel')" id="tab-carousel"
                    class="tab-btn active px-5 py-2.5 text-xs font-montserrat uppercase tracking-widest text-gray-500 cursor-pointer">
                    Hero Carousel
                </button>
                <button onclick="switchMediaTab('footage')" id="tab-footage"
                    class="tab-btn px-5 py-2.5 text-xs font-montserrat uppercase tracking-widest text-gray-500 cursor-pointer">
                    Footage / Lookbook Media
                </button>
            </div>

            <!-- Carousel Grid -->
            <div id="media-tab-carousel" class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($carousels as $media)
                <div class="bg-white border border-gray-200 overflow-hidden group shadow-sm">
                    <div class="media-card">
                        @php
                            $mUrl = Str::startsWith($media->image,'http') ? $media->image : (file_exists(public_path('storage/'.$media->image)) ? asset('storage/'.$media->image) : asset($media->image));
                        @endphp
                        <img src="{{ $mUrl }}" alt="{{ $media->title }}" onerror="this.style.display='none'">
                        <div class="media-card-overlay">
                            <button onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}', '{{ $media->product_id ?? '' }}')"
                                class="btn-accent text-xs px-4 py-2 cursor-pointer">
                                Ganti Gambar
                            </button>
                        </div>
                    </div>
                    <div class="px-4 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-montserrat font-bold uppercase tracking-wide text-gray-800">{{ $media->title ?? 'Slide ' . $loop->iteration }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">{{ ucfirst($media->section) }} &bull; Order {{ $media->order }}</p>
                        </div>
                        <button onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}', '{{ $media->product_id ?? '' }}')"
                            class="text-xs font-montserrat font-semibold uppercase tracking-wider text-blue-600 hover:underline">
                            Edit
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Footage Grid -->
            <div id="media-tab-footage" class="grid grid-cols-1 md:grid-cols-3 gap-5 hidden">
                @foreach($footages as $media)
                <div class="bg-white border border-gray-200 overflow-hidden group shadow-sm">
                    <div class="media-card">
                        @php
                            $mUrl = Str::startsWith($media->image,'http') ? $media->image : (file_exists(public_path('storage/'.$media->image)) ? asset('storage/'.$media->image) : asset($media->image));
                        @endphp
                        <img src="{{ $mUrl }}" alt="{{ $media->title }}" onerror="this.style.display='none'">
                        <div class="media-card-overlay">
                            <button onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}', '{{ $media->product_id ?? '' }}')"
                                class="btn-accent text-xs px-4 py-2 cursor-pointer">
                                Ganti Gambar
                            </button>
                        </div>
                    </div>
                    <div class="px-4 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-montserrat font-bold uppercase tracking-wide text-gray-800">{{ $media->title ?? 'Footage ' . $loop->iteration }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">
                                Order {{ $media->order }} 
                                @if($media->product_id)
                                    &bull; <span class="text-emerald-600 font-semibold">Terkait Produk #{{ $media->product_id }}</span>
                                @endif
                            </p>
                        </div>
                        <button onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}', '{{ $media->product_id ?? '' }}')"
                            class="text-xs font-montserrat font-semibold uppercase tracking-wider text-blue-600 hover:underline">
                            Edit
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────
             SECTION 5: ORDERS
        ───────────────────────────────────────────────────────── --}}
        <section id="section-orders" class="section-card hidden">
            <h2 class="font-montserrat font-extrabold text-lg uppercase tracking-[0.18em] mb-5 text-brandDark">Recent Orders</h2>

            <div class="bg-white border border-gray-200 overflow-x-auto shadow-sm">
                <table class="w-full text-left border-collapse">
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
                            <td class="px-5 py-4 font-mono text-xs text-gray-500 font-bold">#{{ $order->id }}</td>
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
                            <td class="px-5 py-4 text-sm font-roboto font-bold text-gray-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-600">{{ $order->courier_name ?? '—' }}</td>
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
     MODAL 1: ADD / EDIT PRODUCT (Structured Clear Multi-Image UX)
══════════════════════════════════════════════════════════════ --}}
<div id="modal-product" class="modal-backdrop" onclick="closeProductModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <div>
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-gray-400 block">Catalog Management</span>
                <h3 id="modal-product-title" class="font-montserrat font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Add Product</h3>
            </div>
            <button onclick="closeProductModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form id="product-form" action="/admin/products" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST" disabled>

            <!-- SECTION A: Basic Info -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold font-montserrat uppercase tracking-wider text-black border-b border-gray-100 pb-1">1. Informasi Pokok Produk</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Nama Produk *</label>
                        <input type="text" name="name" id="p-name" required placeholder="Contoh: Raja Diamuk Massa Tee V1" class="admin-input">
                    </div>
                    <div>
                        <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Seniman / Kolaborator</label>
                        <div class="relative">
                            <input list="artists-datalist" name="artist" id="p-artist" placeholder="Pilih atau ketik nama seniman..." class="admin-input">
                            <datalist id="artists-datalist">
                                @foreach($artists as $art)
                                    <option value="{{ $art->name }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Harga Produk (IDR) *</label>
                    <input type="number" name="price" id="p-price" required placeholder="Contoh: 250000" class="admin-input">
                </div>

                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Spesifikasi Garmen & Fabric Details</label>
                    <input type="text" name="specs" id="p-specs" placeholder="Contoh: 100% Heavyweight 24s Cotton Combed (220 GSM) • Boxy Drop Shoulder Fit • High-Density Screenprint" class="admin-input">
                    <p class="text-[10px] text-gray-400 font-roboto mt-1">Detail gramasi kain, model potongan, dan teknik sablon/jahitan.</p>
                </div>

                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Deskripsi Editorial Produk</label>
                    <textarea name="description" id="p-desc" rows="3" placeholder="Tuliskan narasi filosofi, konsep desain, dan cerita editorial produk..." class="admin-input resize-none leading-relaxed"></textarea>
                </div>
            </div>

            <!-- SECTION B: Structured Visual Media Uploads -->
            <div class="space-y-4 pt-2">
                <div>
                    <h4 class="text-xs font-bold font-montserrat uppercase tracking-wider text-black">2. Pengaturan Foto & Media Produk</h4>
                    <p class="text-[11px] text-gray-500 font-roboto mt-0.5">Admin dapat mengupload foto spesifik untuk katalog, bagian belakang, model photoshoot, dan panduan size chart agar tidak tertukar.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- 1. Foto Depan / Katalog Utama -->
                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-montserrat font-bold uppercase tracking-wider text-black">Foto Depan (Katalog Utama)</label>
                            <span class="text-[9px] font-montserrat font-bold uppercase bg-black text-white px-1.5 py-0.5">Shop Grid</span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-roboto leading-normal">Foto tampak depan dengan latar bersih untuk cover katalog toko.</p>
                        <input type="file" name="image" id="p-img-front" accept="image/*" onchange="previewImage(this,'preview-front')" class="admin-input py-1.5 text-xs bg-white cursor-pointer">
                        <img id="preview-front" class="w-full h-28 object-cover border border-gray-200 hidden mt-2 rounded-sm" src="" alt="Front Preview">
                    </div>

                    <!-- 2. Foto Belakang Produk -->
                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-montserrat font-bold uppercase tracking-wider text-black">Foto Belakang (Back View)</label>
                            <span class="text-[9px] font-montserrat font-bold uppercase bg-neutral-700 text-white px-1.5 py-0.5">Product Detail</span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-roboto leading-normal">Foto tampak belakang untuk memperlihatkan grafis/detail punggung.</p>
                        <input type="file" name="back_image" id="p-img-back" accept="image/*" onchange="previewImage(this,'preview-back')" class="admin-input py-1.5 text-xs bg-white cursor-pointer">
                        <img id="preview-back" class="w-full h-28 object-cover border border-gray-200 hidden mt-2 rounded-sm" src="" alt="Back Preview">
                    </div>

                    <!-- 3. Foto On-Model Footage -->
                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-montserrat font-bold uppercase tracking-wider text-black">Foto On-Model Footage</label>
                            <span class="text-[9px] font-montserrat font-bold uppercase bg-neutral-700 text-white px-1.5 py-0.5">Photoshoot</span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-roboto leading-normal">Foto produk saat dipakai model photoshoot / lookbook di halaman detail.</p>
                        <input type="file" name="footage_image" id="p-img-footage" accept="image/*" onchange="previewImage(this,'preview-footage')" class="admin-input py-1.5 text-xs bg-white cursor-pointer">
                        <img id="preview-footage" class="w-full h-28 object-cover border border-gray-200 hidden mt-2 rounded-sm" src="" alt="Footage Preview">
                    </div>

                    <!-- 4. Foto Size Chart -->
                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-montserrat font-bold uppercase tracking-wider text-black">Foto Panduan Size Chart</label>
                            <span class="text-[9px] font-montserrat font-bold uppercase bg-neutral-700 text-white px-1.5 py-0.5">Size Guide</span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-roboto leading-normal">Tabel ukuran spesifik garmen ini yang tampil saat popup Size Guide diklik.</p>
                        <input type="file" name="size_chart_image" id="p-img-sizechart" accept="image/*" onchange="previewImage(this,'preview-sizechart')" class="admin-input py-1.5 text-xs bg-white cursor-pointer">
                        <img id="preview-sizechart" class="w-full h-28 object-cover border border-gray-200 hidden mt-2 rounded-sm" src="" alt="Size Chart Preview">
                    </div>
                </div>
            </div>

            <!-- SECTION C: Stock per Size -->
            <div class="space-y-3 pt-2">
                <h4 class="text-xs font-bold font-montserrat uppercase tracking-wider text-black border-b border-gray-100 pb-1">3. Jumlah Stok Fisik per Ukuran</h4>
                <div class="grid grid-cols-4 gap-3">
                    @foreach(['S','M','L','XL'] as $sz)
                    <div class="bg-gray-50 border border-gray-200 p-2 text-center rounded-sm">
                        <span class="block text-[11px] font-montserrat font-bold text-gray-500 mb-1 text-center">{{ $sz }}</span>
                        <input type="number" name="stocks[{{ $sz }}]" id="p-stock-{{ $sz }}" min="0"
                            class="admin-input text-center font-bold" placeholder="0">
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeProductModal()" class="btn-outline cursor-pointer">Cancel</button>
                <button type="submit" class="btn-primary cursor-pointer">Save Product</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL 2: ADD / EDIT ARTIST (NEW!)
══════════════════════════════════════════════════════════════ --}}
<div id="modal-artist" class="modal-backdrop" onclick="closeArtistModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <div>
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-gray-400 block">Artist Collaborations</span>
                <h3 id="modal-artist-title" class="font-montserrat font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Add Artist</h3>
            </div>
            <button onclick="closeArtistModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form id="artist-form" action="/admin/artists" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" id="artist-form-method" name="_method" value="POST" disabled>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Nama Seniman *</label>
                    <input type="text" name="name" id="artist-name" required placeholder="Contoh: Mustafa Alatas" class="admin-input">
                </div>
                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Kota Asal / Domisili</label>
                    <input type="text" name="city" id="artist-city" placeholder="Contoh: Temanggung" class="admin-input">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Biografi & Profil Cerita Seniman</label>
                <textarea name="bio" id="artist-bio" rows="4" placeholder="Tuliskan latar belakang artistik, gaya melukis, dan narasi kolaborasi dengan Notisse..." class="admin-input resize-none leading-relaxed"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Foto Profil / Portrait Seniman</label>
                    <input type="file" name="photo" id="artist-photo-input" accept="image/*"
                        onchange="previewImage(this,'artist-photo-preview')"
                        class="admin-input py-2 text-sm bg-gray-50 cursor-pointer">
                    <img id="artist-photo-preview" class="mt-3 w-32 h-32 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Preview">
                </div>
                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Foto Karya Seni Unggulan (Preview)</label>
                    <input type="file" name="artwork_preview" id="artist-art-input" accept="image/*"
                        onchange="previewImage(this,'artist-art-preview')"
                        class="admin-input py-2 text-sm bg-gray-50 cursor-pointer">
                    <img id="artist-art-preview" class="mt-3 w-32 h-32 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Preview">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeArtistModal()" class="btn-outline cursor-pointer">Cancel</button>
                <button type="submit" class="btn-primary cursor-pointer">Save Artist</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL 3: ADD / EDIT ARTWORK & EDITORIAL
══════════════════════════════════════════════════════════════ --}}
<div id="modal-artwork" class="modal-backdrop" onclick="closeArtworkModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <div>
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-gray-400 block">Editorial Curatorial</span>
                <h3 id="modal-artwork-title" class="font-montserrat font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Add Artwork / Editorial</h3>
            </div>
            <button onclick="closeArtworkModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form id="artwork-form" action="/admin/artworks" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" id="artwork-form-method" name="_method" value="POST" disabled>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Artwork Title *</label>
                <input type="text" name="title" id="art-title" required placeholder="Contoh: Mereka Ulang Ungkapan Indah Leila" class="admin-input">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Artist Collaborator Name</label>
                    <input list="artists-datalist-2" name="artist_name" id="art-artist" placeholder="Pilih atau ketik nama seniman..." class="admin-input">
                    <datalist id="artists-datalist-2">
                        @foreach($artists as $art)
                            <option value="{{ $art->name }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Year Created</label>
                    <input type="text" name="year" id="art-year" placeholder="Contoh: 2024" class="admin-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Medium & Dimensions</label>
                    <input type="text" name="medium" id="art-medium" placeholder="Contoh: Oil on linen &bull; 80x100cm" class="admin-input">
                </div>
                <div>
                    <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Status / Availability</label>
                    <input type="text" name="status" id="art-status" placeholder="Contoh: Available for Acquisition" class="admin-input">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Curatorial Notes & Description</label>
                <textarea name="description" id="art-desc" rows="3" placeholder="Tuliskan latar belakang karya seni, narasi visual, dan pesan seniman..." class="admin-input resize-none leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Artwork Image (High-Res)</label>
                <input type="file" name="image" id="artwork-img-input" accept="image/*"
                    onchange="previewImage(this,'artwork-img-preview')"
                    class="admin-input py-2 text-sm bg-gray-50 cursor-pointer">
                <img id="artwork-img-preview" class="mt-3 w-40 h-28 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Preview">
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeArtworkModal()" class="btn-outline cursor-pointer">Cancel</button>
                <button type="submit" class="btn-primary cursor-pointer">Save Artwork</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL 4: EDIT SITE MEDIA (With Product Link Option)
══════════════════════════════════════════════════════════════ --}}
<div id="modal-media" class="modal-backdrop" onclick="closeMediaModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <h3 class="font-montserrat font-bold text-xl uppercase tracking-[0.15em] text-gray-900">Ganti Gambar</h3>
            <button onclick="closeMediaModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form id="media-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Title / Keterangan</label>
                <input type="text" name="title" id="m-title" class="admin-input">
            </div>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Tautkan ke Produk (Opsional)</label>
                <select name="product_id" id="m-product-id" class="admin-input">
                    <option value="">-- Tidak Terhubung ke Produk --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}">{{ $prod->name }} (#{{ $prod->id }})</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-400 mt-1 font-roboto">Pilih produk jika foto footage ini merupakan foto katalog garmen tertentu.</p>
            </div>

            <div>
                <label class="block text-[11px] font-montserrat font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Upload Gambar Baru</label>
                <input type="file" name="image" id="media-img-input" accept="image/*"
                    onchange="previewImage(this,'media-img-preview')"
                    class="admin-input py-2 text-sm bg-gray-50 cursor-pointer">
                <img id="media-img-preview" class="mt-3 w-full max-h-48 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Preview">
                <p class="text-[11px] text-gray-400 mt-1.5 font-roboto">Biarkan kosong jika tidak ingin mengganti gambar.</p>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeMediaModal()" class="btn-outline cursor-pointer">Cancel</button>
                <button type="submit" class="btn-primary cursor-pointer">Simpan</button>
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
        const sections = ['products', 'artists', 'artworks', 'media', 'orders'];
        const titles = {
            'products': 'Products Catalog Management',
            'artists':  'Artists & Collaborators Management',
            'artworks': 'Artworks & Artist Editorial Management',
            'media':    'Site Visual Media (Hero & Lookbook)',
            'orders':   'Customer Orders & Transactions'
        };

        sections.forEach(s => {
            const el = document.getElementById('section-' + s);
            const nav = document.getElementById('nav-' + s);
            if (el) el.classList.toggle('hidden', s !== name);
            if (nav) nav.classList.toggle('active', s === name);
        });

        const pageTitle = document.getElementById('page-title');
        if (pageTitle && titles[name]) {
            pageTitle.innerText = titles[name];
        }
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
                preview.classList.remove('hidden');
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

        ['preview-front','preview-back','preview-footage','preview-sizechart'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { el.classList.add('hidden'); el.src = ''; }
        });

        document.getElementById('modal-product').classList.add('open');
    }

    function openEditProductModal(id, name, price, desc, specs, artist, stocksObj, frontImg, backImg, footageImg, sizechartImg) {
        document.getElementById('modal-product-title').textContent = 'Edit Product';
        document.getElementById('product-form').action = '/admin/products/' + id;
        document.getElementById('form-method').value   = 'PUT';
        document.getElementById('form-method').disabled = false;

        document.getElementById('p-name').value   = name;
        document.getElementById('p-price').value  = price;
        document.getElementById('p-desc').value   = desc || '';
        document.getElementById('p-specs').value  = specs || '';
        document.getElementById('p-artist').value = artist || '';

        // Pre-fill Image Previews if exist
        setPreviewImage('preview-front', frontImg);
        setPreviewImage('preview-back', backImg);
        setPreviewImage('preview-footage', footageImg);
        setPreviewImage('preview-sizechart', sizechartImg);

        ['S','M','L','XL'].forEach(s => {
            const el = document.getElementById('p-stock-' + s);
            if (el) el.value = (stocksObj && stocksObj[s] !== undefined) ? stocksObj[s] : '';
        });

        document.getElementById('modal-product').classList.add('open');
    }

    function setPreviewImage(id, url) {
        const el = document.getElementById(id);
        if (!el) return;
        if (url && url.length > 5) {
            el.src = url;
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
            el.src = '';
        }
    }

    function closeProductModal() {
        document.getElementById('modal-product').classList.remove('open');
    }

    // ── Artist modal (NEW!) ─────────────────────────────────────
    function openAddArtistModal() {
        document.getElementById('modal-artist-title').textContent = 'Add Artist (Tambah Seniman)';
        document.getElementById('artist-form').action = '/admin/artists';
        document.getElementById('artist-form').reset();
        document.getElementById('artist-form-method').disabled = true;
        document.getElementById('artist-photo-preview').classList.add('hidden');
        document.getElementById('artist-art-preview').classList.add('hidden');
        document.getElementById('modal-artist').classList.add('open');
    }

    function openEditArtistModal(id, name, city, bio, photoUrl, artUrl) {
        document.getElementById('modal-artist-title').textContent = 'Edit Artist (Edit Seniman)';
        document.getElementById('artist-form').action = '/admin/artists/' + id;
        document.getElementById('artist-form-method').value = 'PUT';
        document.getElementById('artist-form-method').disabled = false;

        document.getElementById('artist-name').value = name;
        document.getElementById('artist-city').value = city || '';
        document.getElementById('artist-bio').value  = bio || '';

        setPreviewImage('artist-photo-preview', photoUrl);
        setPreviewImage('artist-art-preview', artUrl);

        document.getElementById('modal-artist').classList.add('open');
    }

    function closeArtistModal() {
        document.getElementById('modal-artist').classList.remove('open');
    }

    // ── Artwork modal ───────────────────────────────────────────
    function openAddArtworkModal() {
        document.getElementById('modal-artwork-title').textContent = 'Add Artwork / Editorial';
        document.getElementById('artwork-form').action = '/admin/artworks';
        document.getElementById('artwork-form').reset();
        document.getElementById('artwork-form-method').disabled = true;
        document.getElementById('artwork-img-preview').classList.add('hidden');
        document.getElementById('modal-artwork').classList.add('open');
    }

    function openEditArtworkModal(id, title, artist, medium, year, status, desc) {
        document.getElementById('modal-artwork-title').textContent = 'Edit Artwork / Editorial';
        document.getElementById('artwork-form').action = '/admin/artworks/' + id;
        document.getElementById('artwork-form-method').value = 'PUT';
        document.getElementById('artwork-form-method').disabled = false;

        document.getElementById('art-title').value  = title;
        document.getElementById('art-artist').value = artist || '';
        document.getElementById('art-medium').value = medium || '';
        document.getElementById('art-year').value   = year || '';
        document.getElementById('art-status').value = status || '';
        document.getElementById('art-desc').value   = desc || '';
        document.getElementById('artwork-img-preview').classList.add('hidden');

        document.getElementById('modal-artwork').classList.add('open');
    }

    function closeArtworkModal() {
        document.getElementById('modal-artwork').classList.remove('open');
    }

    // ── Media modal ─────────────────────────────────────────────
    function openMediaModal(id, title, desc, productId) {
        document.getElementById('media-form').action = '/admin/media/' + id;
        document.getElementById('m-title').value     = title || '';
        document.getElementById('m-product-id').value = productId || '';
        document.getElementById('media-img-preview').classList.add('hidden');
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
            closeArtistModal();
            closeArtworkModal();
            closeMediaModal();
        }
    });
</script>

</body>
</html>
