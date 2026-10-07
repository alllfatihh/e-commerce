<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTISSE — Atelier Admin & Curatorial Panel</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'],
                        roboto:     ['Roboto', 'sans-serif'],
                    },
                    colors: {
                        obsidian:  '#0d0d0d',
                        panelDark: '#141414',
                        cardDark:  '#1a1a1a',
                        borderDark:'#262626',
                        accentRed: '#d52c2b',
                        accentYellow: '#e2ff00',
                    }
                }
            }
        }
    </script>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #0d0d0d;
            color: #f4f4f5;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Scrollbars */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #141414; }
        ::-webkit-scrollbar-thumb { background: #333333; border-radius: 2px; }

        /* Sidebar Styling */
        #admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background: #111111;
            border-right: 1px solid #222222;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 40;
            transition: transform 0.3s ease;
        }

        .nav-item {
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .nav-item:hover {
            background-color: #1a1a1a;
            color: #ffffff;
            padding-left: 1.5rem;
        }
        .nav-item.active {
            background-color: #ffffff;
            color: #000000;
            font-weight: 700;
            padding-left: 1.5rem;
        }
        .nav-item.active svg {
            color: #000000;
        }

        /* Inputs in Modals */
        .admin-input {
            border: 1px solid #333333;
            background: #18181b;
            color: #f4f4f5;
            outline: none;
            transition: border-color 0.2s, background-color 0.2s;
            width: 100%;
            padding: 0.65rem 0.9rem;
            font-size: 13px;
            font-family: 'Roboto', sans-serif;
            border-radius: 2px;
        }
        .admin-input:focus {
            border-color: #ffffff;
            background: #202024;
        }
        .admin-input::placeholder {
            color: #71717a;
            font-size: 12px;
        }

        /* Modal backdrop */
        .modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(4px);
            z-index: 70;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden;
            transition: opacity 0.25s, visibility 0.25s;
        }
        .modal-backdrop.open { opacity: 1; visibility: visible; }
        .modal-box {
            background: #141414;
            border: 1px solid #2a2a2a;
            color: #f4f4f5;
            max-width: 680px; width: 92%;
            max-height: 90vh; overflow-y: auto;
            padding: 2.2rem;
            transform: translateY(20px);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border-radius: 2px;
        }
        .modal-backdrop.open .modal-box { transform: translateY(0); }

        /* Table styles */
        .admin-tr { transition: background 0.15s; }
        .admin-tr:hover { background: #191919; }

        /* Tab Buttons */
        .tab-btn {
            border-bottom: 2px solid transparent;
            transition: border-color 0.2s, color 0.2s;
        }
        .tab-btn.active {
            border-color: #ffffff;
            color: #ffffff;
            font-weight: 700;
        }
    </style>
</head>
<body class="min-h-screen">

    {{-- SIDEBAR --}}
    <aside id="admin-sidebar">
        <!-- Logo & Brand Header -->
        <div class="px-6 py-6 border-b border-[#222222]">
            <a href="/" class="block group">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 bg-white text-black flex items-center justify-center font-black font-montserrat text-sm tracking-wider">
                        NT
                    </div>
                    <div>
                        <p class="font-montserrat font-extrabold text-base tracking-[0.25em] uppercase text-white group-hover:text-gray-300 transition-colors">NOTISSE</p>
                        <p class="font-roboto text-[9px] text-gray-500 tracking-widest uppercase">Atelier & Admin Panel</p>
                    </div>
                </div>
            </a>
            
            <div class="mt-4 flex items-center space-x-2 text-[10px] font-montserrat uppercase tracking-wider text-emerald-400 bg-emerald-950/40 border border-emerald-900/60 px-2.5 py-1 rounded-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>System Online (Production)</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 py-5 space-y-1 overflow-y-auto">
            <a href="javascript:void(0)" onclick="showSection('products')" id="nav-products"
               class="nav-item active flex items-center gap-3 px-6 py-3.5 text-xs font-montserrat tracking-widest uppercase text-gray-400">
                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span>Products Catalog</span>
            </a>

            <a href="javascript:void(0)" onclick="showSection('artworks')" id="nav-artworks"
               class="nav-item flex items-center gap-3 px-6 py-3.5 text-xs font-montserrat tracking-widest uppercase text-gray-400">
                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Artworks & Editorial</span>
            </a>

            <a href="javascript:void(0)" onclick="showSection('media')" id="nav-media"
               class="nav-item flex items-center gap-3 px-6 py-3.5 text-xs font-montserrat tracking-widest uppercase text-gray-400">
                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <span>Site Media (Hero & Look)</span>
            </a>

            <a href="javascript:void(0)" onclick="showSection('orders')" id="nav-orders"
               class="nav-item flex items-center gap-3 px-6 py-3.5 text-xs font-montserrat tracking-widest uppercase text-gray-400">
                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span>Orders & Payments</span>
            </a>
        </nav>

        <!-- Bottom Links & Sync -->
        <div class="px-6 py-5 border-t border-[#222222] space-y-3 bg-[#0f0f0f]">
            <form action="/admin/biteship/sync" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-[#1e1e1e] hover:bg-[#2a2a2a] text-white border border-[#333333] px-3.5 py-2.5 text-left flex items-center justify-between text-xs font-montserrat font-semibold tracking-wider uppercase transition-colors cursor-pointer">
                    <span class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                        Sync Biteship
                    </span>
                    <span class="text-[9px] text-gray-500 font-mono">API</span>
                </button>
            </form>

            <a href="/" target="_blank" class="flex items-center justify-between text-xs text-gray-400 hover:text-white tracking-widest uppercase font-montserrat transition-colors pt-1">
                <span>&larr; Lihat Web Toko</span>
                <span>&#8599;</span>
            </a>
        </div>
    </aside>

    {{-- MAIN CONTENT AREA --}}
    <div style="margin-left: 260px; min-height: 100vh;" class="flex flex-col bg-[#0d0d0d]">
        
        <!-- TOP APP BAR -->
        <header class="sticky top-0 z-30 bg-[#121212]/95 backdrop-blur-md border-b border-[#222222] px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="font-montserrat font-extrabold text-sm uppercase tracking-[0.2em] text-white" id="page-title">
                    Products Catalog Management
                </h1>
                <p class="text-[11px] text-gray-500 font-roboto mt-0.5">
                    Notisse E-Commerce Atelier Dashboard &bull; {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

            <!-- Right utilities -->
            <div class="flex items-center gap-4">
                @if(session('success'))
                <div id="toast-success" class="flex items-center gap-2 bg-emerald-500 text-black px-4 py-2 text-xs font-montserrat font-bold uppercase tracking-wider shadow-lg rounded-sm animate-pulse">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
                @endif
                <a href="/" class="text-xs font-montserrat font-bold tracking-wider uppercase border border-[#333] hover:border-white px-3 py-1.5 transition-colors text-gray-300 hover:text-white">
                    Preview Web
                </a>
            </div>
        </header>

        <!-- METRICS / STAT CARDS -->
        <div class="px-8 pt-8 pb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-[#141414] border border-[#242424] p-5 relative overflow-hidden">
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-[0.18em] text-gray-400 block mb-2">Total Produk</span>
                <p class="font-montserrat font-black text-2xl lg:text-3xl text-white">{{ $stats['total_products'] ?? $products->count() }}</p>
                <span class="text-[10px] text-gray-500 font-roboto mt-1 block">Item aktif di katalog</span>
            </div>

            <div class="bg-[#141414] border border-[#242424] p-5 relative overflow-hidden">
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-[0.18em] text-gray-400 block mb-2">Total Stok Fisik</span>
                <p class="font-montserrat font-black text-2xl lg:text-3xl text-white">{{ $stats['total_stock'] ?? 0 }}</p>
                <span class="text-[10px] text-gray-500 font-roboto mt-1 block">Akumulasi size S - XL</span>
            </div>

            <div class="bg-[#141414] border border-[#242424] p-5 relative overflow-hidden">
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-[0.18em] text-gray-400 block mb-2">Artworks & Editorial</span>
                <p class="font-montserrat font-black text-2xl lg:text-3xl text-white">{{ $stats['total_artworks'] ?? $artworks->count() }}</p>
                <span class="text-[10px] text-gray-500 font-roboto mt-1 block">Karya seni & curated look</span>
            </div>

            <div class="bg-[#141414] border border-[#242424] p-5 relative overflow-hidden">
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-[0.18em] text-gray-400 block mb-2">Total Pendapatan (Paid)</span>
                <p class="font-montserrat font-black text-2xl lg:text-3xl text-white">
                    Rp {{ number_format($stats['paid_revenue'] ?? 0, 0, ',', '.') }}
                </p>
                <span class="text-[10px] text-gray-500 font-roboto mt-1 block">{{ $stats['total_orders'] ?? $orders->count() }} Transaksi terdaftar</span>
            </div>
        </div>

        <!-- MAIN VIEW SECTIONS -->
        <main class="flex-1 px-8 pb-16 space-y-10">

            {{-- ─────────────────────────────────────────────────────────────
                 SECTION 1: PRODUCTS
            ───────────────────────────────────────────────────────────── --}}
            <section id="section-products" class="space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#222222]">
                    <div>
                        <h2 class="font-montserrat font-extrabold text-lg uppercase tracking-[0.2em] text-white">Katalog Produk</h2>
                        <p class="text-xs text-gray-400 font-roboto mt-0.5">Kelola item pakaian, deskripsi editorial, spesifikasi bahan/fabric, seniman kolaborator, dan stok per ukuran.</p>
                    </div>
                    <button onclick="openAddProductModal()" class="bg-white hover:bg-neutral-200 text-black font-montserrat font-bold text-xs uppercase tracking-widest px-5 py-3 transition-colors flex items-center gap-2 cursor-pointer shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>Tambah Produk</span>
                    </button>
                </div>

                <div class="bg-[#141414] border border-[#242424] overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="border-b border-[#242424] bg-[#181818]">
                            <tr class="text-[10px] font-montserrat font-bold uppercase tracking-[0.15em] text-gray-400">
                                <th class="px-5 py-3.5">Produk & Seniman</th>
                                <th class="px-5 py-3.5">Harga</th>
                                <th class="px-5 py-3.5">Stok per Size</th>
                                <th class="px-5 py-3.5">Spesifikasi & Deskripsi</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#202020]">
                            @forelse($products as $product)
                            <tr class="admin-tr">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-16 bg-[#1f1f1f] border border-[#333333] shrink-0 overflow-hidden">
                                            @php
                                                $img = $product->image;
                                                if ($img) {
                                                    if (Str::startsWith($img, 'http')) { $imgUrl = $img; }
                                                    elseif (file_exists(public_path('storage/' . $img))) { $imgUrl = asset('storage/' . $img); }
                                                    else { $imgUrl = asset($img); }
                                                } else {
                                                    $imgUrl = asset('footage-baju.jpg');
                                                }
                                            @endphp
                                            <img src="{{ $imgUrl }}" class="w-full h-full object-cover" onerror="this.src='/footage-baju.jpg'">
                                        </div>
                                        <div>
                                            <p class="font-montserrat font-bold text-sm text-white uppercase">{{ $product->name }}</p>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider bg-black text-gray-300 border border-[#333] px-2 py-0.5">
                                                    {{ $product->artist ?: 'In-House Atelier' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-montserrat font-bold text-sm text-white whitespace-nowrap">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($product->stocks as $stock)
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 border text-[11px] font-montserrat font-bold {{ $stock->stock < 5 ? 'border-red-900/60 text-red-400 bg-red-950/40' : 'border-[#333] text-gray-300 bg-[#1e1e1e]' }}">
                                            <span class="text-gray-400">{{ $stock->size }}:</span>
                                            <span>{{ $stock->stock }}</span>
                                        </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-5 py-4 max-w-xs text-xs text-gray-400 font-roboto">
                                    @if($product->specs)
                                    <p class="text-[11px] text-gray-300 font-mono truncate mb-0.5"><strong class="text-white">Specs:</strong> {{ $product->specs }}</p>
                                    @endif
                                    <p class="line-clamp-2 leading-relaxed">{{ $product->description ?: 'Belum ada deskripsi produk.' }}</p>
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-3 font-montserrat text-xs">
                                        <button onclick="openEditProductModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->price }}', '{{ addslashes($product->description ?? '') }}', '{{ addslashes($product->specs ?? '') }}', '{{ addslashes($product->artist ?? '') }}', {{ json_encode($product->stocks->pluck('stock','size')) }})"
                                            class="font-bold uppercase tracking-wider text-white hover:text-gray-300 underline cursor-pointer">
                                            Edit
                                        </button>
                                        <form action="/admin/products/{{ $product->id }}" method="POST" class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ addslashes($product->name) }}?')">
                                            @method('DELETE') @csrf
                                            <button type="submit"
                                                class="font-bold uppercase tracking-wider text-red-500 hover:text-red-400 cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-500 font-roboto">
                                    Belum ada produk yang ditambahkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- ─────────────────────────────────────────────────────────────
                 SECTION 2: ARTWORKS & ARTIST EDITORIAL
            ───────────────────────────────────────────────────────────── --}}
            <section id="section-artworks" class="space-y-5 hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#222222]">
                    <div>
                        <h2 class="font-montserrat font-extrabold text-lg uppercase tracking-[0.2em] text-white">Artworks & Artist Editorial</h2>
                        <p class="text-xs text-gray-400 font-roboto mt-0.5">Kelola karya seni orisinil untuk program penawaran akuisisi kolektor serta editorial seniman kolaborator.</p>
                    </div>
                    <button onclick="openAddArtworkModal()" class="bg-white hover:bg-neutral-200 text-black font-montserrat font-bold text-xs uppercase tracking-widest px-5 py-3 transition-colors flex items-center gap-2 cursor-pointer shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>Tambah Artwork / Editorial</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($artworks as $art)
                    <div class="bg-[#141414] border border-[#242424] overflow-hidden flex flex-col justify-between group">
                        <div>
                            <!-- Artwork Thumbnail -->
                            <div class="aspect-[4/3] bg-black relative overflow-hidden border-b border-[#242424]">
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
                                <img src="{{ $artUrl }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='/footagebaju2.jpg'">
                                <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                    <span class="text-[9px] font-bold font-montserrat tracking-widest uppercase bg-black text-white px-2 py-0.5 border border-[#333]">
                                        {{ $art->status ?: 'Available for Acquisition' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Artwork Info Body -->
                            <div class="p-5 space-y-3">
                                <div>
                                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-gray-500">{{ $art->artist_name ?: 'Curated Artist' }} &bull; {{ $art->year ?: '2024' }}</span>
                                    <h3 class="font-montserrat font-bold text-base text-white uppercase mt-0.5">{{ $art->title }}</h3>
                                </div>

                                <div class="text-xs text-gray-400 font-roboto space-y-1">
                                    <p><strong class="text-gray-300">Medium:</strong> {{ $art->medium ?: 'Oil on Canvas' }}</p>
                                    <p class="line-clamp-2 leading-relaxed text-gray-400">{{ $art->description ?: 'Karya seni eksklusif dalam program kolaborasi seni Notisse.' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer Actions -->
                        <div class="px-5 py-3.5 bg-[#181818] border-t border-[#242424] flex items-center justify-between">
                            <span class="text-[10px] font-mono text-gray-500 uppercase">REF: #NTS-ART-{{ $art->id }}</span>
                            <div class="flex items-center gap-3">
                                <button onclick="openEditArtworkModal({{ $art->id }}, '{{ addslashes($art->title) }}', '{{ addslashes($art->artist_name ?? '') }}', '{{ addslashes($art->medium ?? '') }}', '{{ addslashes($art->year ?? '') }}', '{{ addslashes($art->status ?? '') }}', '{{ addslashes($art->description ?? '') }}')"
                                    class="text-xs font-montserrat font-bold uppercase tracking-wider text-white hover:underline cursor-pointer">
                                    Edit
                                </button>
                                <form action="/admin/artworks/{{ $art->id }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus artwork {{ addslashes($art->title) }}?')">
                                    @method('DELETE') @csrf
                                    <button type="submit" class="text-xs font-montserrat font-bold uppercase tracking-wider text-red-500 hover:text-red-400 cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full p-12 bg-[#141414] border border-[#242424] text-center text-gray-500 font-roboto text-sm">
                        Belum ada data karya seni atau artist editorial. Klik "Tambah Artwork / Editorial" di atas untuk menambahkan.
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- ─────────────────────────────────────────────────────────────
                 SECTION 3: SITE MEDIA
            ───────────────────────────────────────────────────────────── --}}
            <section id="section-media" class="space-y-6 hidden">
                <div class="pb-2 border-b border-[#222222]">
                    <h2 class="font-montserrat font-extrabold text-lg uppercase tracking-[0.2em] text-white">Media Gambar Toko (Hero & Lookbook)</h2>
                    <p class="text-xs text-gray-400 font-roboto mt-0.5">Ubah aset visual foto utama Hero Carousel dan Footage Editorial yang tayang di halaman depan.</p>
                </div>

                <!-- Tabs: Carousel vs Footage -->
                <div class="flex border-b border-[#242424] gap-6">
                    <button onclick="switchMediaTab('carousel')" id="tab-carousel"
                        class="tab-btn active pb-3 text-xs font-montserrat font-bold uppercase tracking-widest text-white cursor-pointer">
                        Hero Carousel Slides
                    </button>
                    <button onclick="switchMediaTab('footage')" id="tab-footage"
                        class="tab-btn pb-3 text-xs font-montserrat font-medium uppercase tracking-widest text-gray-500 hover:text-white transition-colors cursor-pointer">
                        Footage / Lookbook Media
                    </button>
                </div>

                <!-- Grid Carousel -->
                <div id="media-tab-carousel" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($carousels as $media)
                    <div class="bg-[#141414] border border-[#242424] overflow-hidden group">
                        <div class="aspect-[4/3] bg-black relative">
                            @php
                                $mUrl = Str::startsWith($media->image,'http') ? $media->image : (file_exists(public_path('storage/'.$media->image)) ? asset('storage/'.$media->image) : asset($media->image));
                            @endphp
                            <img src="{{ $mUrl }}" alt="{{ $media->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex items-center justify-between border-t border-[#242424]">
                            <div>
                                <p class="text-xs font-montserrat font-bold uppercase text-white">{{ $media->title ?? 'Slide ' . $loop->iteration }}</p>
                                <p class="text-[10px] text-gray-500 font-mono mt-0.5">Order: {{ $media->order }}</p>
                            </div>
                            <button onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}')"
                                class="bg-[#242424] hover:bg-white hover:text-black text-white text-xs font-montserrat font-bold uppercase tracking-wider px-3 py-1.5 transition-colors cursor-pointer">
                                Ganti Gambar
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Grid Footage -->
                <div id="media-tab-footage" class="grid grid-cols-1 md:grid-cols-3 gap-6 hidden">
                    @foreach($footages as $media)
                    <div class="bg-[#141414] border border-[#242424] overflow-hidden group">
                        <div class="aspect-[4/3] bg-black relative">
                            @php
                                $mUrl = Str::startsWith($media->image,'http') ? $media->image : (file_exists(public_path('storage/'.$media->image)) ? asset('storage/'.$media->image) : asset($media->image));
                            @endphp
                            <img src="{{ $mUrl }}" alt="{{ $media->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex items-center justify-between border-t border-[#242424]">
                            <div>
                                <p class="text-xs font-montserrat font-bold uppercase text-white">{{ $media->title ?? 'Footage ' . $loop->iteration }}</p>
                                <p class="text-[10px] text-gray-500 font-mono mt-0.5">Order: {{ $media->order }}</p>
                            </div>
                            <button onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}')"
                                class="bg-[#242424] hover:bg-white hover:text-black text-white text-xs font-montserrat font-bold uppercase tracking-wider px-3 py-1.5 transition-colors cursor-pointer">
                                Ganti Gambar
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- ─────────────────────────────────────────────────────────────
                 SECTION 4: ORDERS
            ───────────────────────────────────────────────────────────── --}}
            <section id="section-orders" class="space-y-5 hidden">
                <div class="pb-2 border-b border-[#222222]">
                    <h2 class="font-montserrat font-extrabold text-lg uppercase tracking-[0.2em] text-white">Daftar Transaksi Pelanggan</h2>
                    <p class="text-xs text-gray-400 font-roboto mt-0.5">Semua data transaksi pesanan yang terintegrasi dengan Payment Gateway Midtrans dan Biteship Courier.</p>
                </div>

                <div class="bg-[#141414] border border-[#242424] overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="border-b border-[#242424] bg-[#181818]">
                            <tr class="text-[10px] font-montserrat font-bold uppercase tracking-[0.15em] text-gray-400">
                                <th class="px-5 py-3.5">Ref. Order</th>
                                <th class="px-5 py-3.5">Nama & Kontak Pembeli</th>
                                <th class="px-5 py-3.5">Status Pembayaran</th>
                                <th class="px-5 py-3.5">Total Belanja</th>
                                <th class="px-5 py-3.5">Kurir Ekspedisi</th>
                                <th class="px-5 py-3.5">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#202020]">
                            @forelse($orders as $order)
                            <tr class="admin-tr">
                                <td class="px-5 py-4 font-mono text-xs font-bold text-gray-300">#NTS-{{ $order->id }}</td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-montserrat font-bold text-white uppercase">{{ $order->customer_name }}</p>
                                    <p class="text-xs text-gray-400 font-roboto mt-0.5">{{ $order->customer_email }} &bull; {{ $order->customer_phone ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @php
                                        $st = strtolower($order->status);
                                        if ($st === 'paid') {
                                            $stClass = 'bg-emerald-950 text-emerald-400 border-emerald-800';
                                        } elseif ($st === 'cancelled') {
                                            $stClass = 'bg-red-950 text-red-400 border-red-800';
                                        } else {
                                            $stClass = 'bg-amber-950 text-amber-400 border-amber-800';
                                        }
                                    @endphp
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-montserrat font-bold uppercase tracking-wider border {{ $stClass }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-montserrat font-bold text-sm text-white whitespace-nowrap">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-xs font-roboto text-gray-300">
                                    {{ $order->courier_name ?: 'Standar' }}
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-400 font-roboto whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-500 font-roboto">
                                    Belum ada transaksi pesanan yang masuk.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         MODAL 1: ADD / EDIT PRODUCT
    ══════════════════════════════════════════════════════════════ --}}
    <div id="modal-product" class="modal-backdrop" onclick="closeProductModal()">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#262626]">
                <div>
                    <span class="text-[9px] font-montserrat font-bold uppercase tracking-[0.2em] text-gray-400 block">Catalog Management</span>
                    <h3 id="modal-product-title" class="font-montserrat font-bold text-lg uppercase tracking-wider text-white">Tambah Produk</h3>
                </div>
                <button onclick="closeProductModal()" class="text-2xl leading-none text-gray-400 hover:text-white transition-colors cursor-pointer">&times;</button>
            </div>

            <form id="product-form" action="/admin/products" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" id="product-form-method" name="_method" value="POST" disabled>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Nama Produk *</label>
                        <input type="text" name="name" id="p-name" required placeholder="Contoh: Heavyweight Boxy T-Shirt" class="admin-input">
                    </div>
                    <div>
                        <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Seniman / Kolaborator</label>
                        <input type="text" name="artist" id="p-artist" placeholder="Contoh: Mustafa Alatas x Notisse" class="admin-input">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Harga (IDR) *</label>
                    <input type="number" name="price" id="p-price" required placeholder="Contoh: 389000" class="admin-input">
                </div>

                <div>
                    <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Spesifikasi Material / Fabric Details</label>
                    <input type="text" name="specs" id="p-specs" placeholder="Contoh: Heavyweight Cotton Fleece 450 GSM, Boxy Drop-Shoulder Fit" class="admin-input">
                    <p class="text-[10px] text-gray-500 font-roboto mt-1">Spesifikasi teknis kain, gramasi, dan model potongan.</p>
                </div>

                <div>
                    <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Deskripsi & Cerita Editorial Produk</label>
                    <textarea name="description" id="p-desc" rows="3" placeholder="Tuliskan deskripsi lengkap, filosofi desain, dan narasi garmen..." class="admin-input resize-none leading-relaxed"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Foto Produk</label>
                    <input type="file" name="image" id="product-img-input" accept="image/*"
                        onchange="previewImage(this,'product-img-preview')"
                        class="admin-input py-2 text-xs cursor-pointer">
                    <img id="product-img-preview" class="mt-3 w-28 h-32 object-cover border border-[#333] hidden" src="" alt="Preview">
                </div>

                <div>
                    <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-2">Stok per Ukuran (S, M, L, XL)</label>
                    <div class="grid grid-cols-4 gap-3">
                        @foreach(['S','M','L','XL'] as $sz)
                        <div class="bg-[#18181b] border border-[#2b2b2b] p-2 text-center">
                            <span class="block text-xs font-montserrat font-black text-gray-400 mb-1">{{ $sz }}</span>
                            <input type="number" name="stocks[{{ $sz }}]" id="p-stock-{{ $sz }}" min="0"
                                class="admin-input text-center font-bold text-sm" placeholder="0">
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 border-t border-[#262626] flex justify-end gap-3">
                    <button type="button" onclick="closeProductModal()" class="px-5 py-2.5 text-xs font-montserrat font-semibold uppercase tracking-wider border border-[#444] text-gray-300 hover:text-white transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="bg-white hover:bg-neutral-200 text-black px-6 py-2.5 text-xs font-montserrat font-bold uppercase tracking-widest transition-colors cursor-pointer">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         MODAL 2: ADD / EDIT ARTWORK & ARTIST EDITORIAL
    ══════════════════════════════════════════════════════════════ --}}
    <div id="modal-artwork" class="modal-backdrop" onclick="closeArtworkModal()">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#262626]">
                <div>
                    <span class="text-[9px] font-montserrat font-bold uppercase tracking-[0.2em] text-gray-400 block">Editorial Curatorial</span>
                    <h3 id="modal-artwork-title" class="font-montserrat font-bold text-lg uppercase tracking-wider text-white">Tambah Artwork / Editorial</h3>
                </div>
                <button onclick="closeArtworkModal()" class="text-2xl leading-none text-gray-400 hover:text-white transition-colors cursor-pointer">&times;</button>
            </div>

            <form id="artwork-form" action="/admin/artworks" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" id="artwork-form-method" name="_method" value="POST" disabled>

                <div>
                    <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Judul Karya Seni / Editorial *</label>
                    <input type="text" name="title" id="art-title" required placeholder="Contoh: Mereka Ulang Ungkapan Indah Leila" class="admin-input">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Nama Seniman (Artist)</label>
                        <input type="text" name="artist_name" id="art-artist" placeholder="Contoh: Mustafa Alatas" class="admin-input">
                    </div>
                    <div>
                        <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Tahun Pembuatan (Year)</label>
                        <input type="text" name="year" id="art-year" placeholder="Contoh: 2024" class="admin-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Medium & Dimensi</label>
                        <input type="text" name="medium" id="art-medium" placeholder="Contoh: Oil on linen &bull; 80x100cm" class="admin-input">
                    </div>
                    <div>
                        <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Status Akuisisi</label>
                        <input type="text" name="status" id="art-status" placeholder="Contoh: Available for Acquisition" class="admin-input">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Catatan Kurasi & Deskripsi Karya</label>
                    <textarea name="description" id="art-desc" rows="3" placeholder="Tuliskan latar belakang karya, narasi puitis, dan pesan seniman..." class="admin-input resize-none leading-relaxed"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Foto Karya Seni (High Resolution)</label>
                    <input type="file" name="image" id="artwork-img-input" accept="image/*"
                        onchange="previewImage(this,'artwork-img-preview')"
                        class="admin-input py-2 text-xs cursor-pointer">
                    <img id="artwork-img-preview" class="mt-3 w-40 h-28 object-cover border border-[#333] hidden" src="" alt="Preview">
                </div>

                <div class="pt-4 border-t border-[#262626] flex justify-end gap-3">
                    <button type="button" onclick="closeArtworkModal()" class="px-5 py-2.5 text-xs font-montserrat font-semibold uppercase tracking-wider border border-[#444] text-gray-300 hover:text-white transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="bg-white hover:bg-neutral-200 text-black px-6 py-2.5 text-xs font-montserrat font-bold uppercase tracking-widest transition-colors cursor-pointer">
                        Simpan Artwork
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         MODAL 3: EDIT SITE MEDIA (HERO / FOOTAGE)
    ══════════════════════════════════════════════════════════════ --}}
    <div id="modal-media" class="modal-backdrop" onclick="closeMediaModal()">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#262626]">
                <h3 class="font-montserrat font-bold text-lg uppercase tracking-wider text-white">Ganti Media Visual</h3>
                <button onclick="closeMediaModal()" class="text-2xl leading-none text-gray-400 hover:text-white transition-colors cursor-pointer">&times;</button>
            </div>

            <form id="media-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Judul / Caption</label>
                    <input type="text" name="title" id="m-title" class="admin-input">
                </div>

                <div>
                    <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1">Upload Gambar Baru</label>
                    <input type="file" name="image" id="media-img-input" accept="image/*"
                        onchange="previewImage(this,'media-img-preview')"
                        class="admin-input py-2 text-xs cursor-pointer">
                    <img id="media-img-preview" class="mt-3 w-full max-h-48 object-cover border border-[#333] hidden" src="" alt="Preview">
                </div>

                <div class="pt-4 border-t border-[#262626] flex justify-end gap-3">
                    <button type="button" onclick="closeMediaModal()" class="px-5 py-2.5 text-xs font-montserrat font-semibold uppercase tracking-wider border border-[#444] text-gray-300 hover:text-white transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="bg-white hover:bg-neutral-200 text-black px-6 py-2.5 text-xs font-montserrat font-bold uppercase tracking-widest transition-colors cursor-pointer">
                        Simpan Gambar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        // Section Navigation
        function showSection(name) {
            const sections = ['products', 'artworks', 'media', 'orders'];
            const titles = {
                'products': 'Products Catalog Management',
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

        // Media Tabs (Carousel vs Footage)
        function switchMediaTab(tab) {
            ['carousel','footage'].forEach(t => {
                const pane = document.getElementById('media-tab-' + t);
                const btn  = document.getElementById('tab-' + t);
                if (pane) pane.classList.toggle('hidden', t !== tab);
                if (btn)  btn.classList.toggle('active', t === tab);
            });
        }

        // Image Preview Handler
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

        // Product Modal Handlers
        function openAddProductModal() {
            document.getElementById('modal-product-title').textContent = 'Tambah Produk Baru';
            const form = document.getElementById('product-form');
            form.action = '/admin/products';
            form.reset();
            document.getElementById('product-form-method').disabled = true;
            document.getElementById('product-img-preview').classList.add('hidden');
            document.getElementById('modal-product').classList.add('open');
        }

        function openEditProductModal(id, name, price, desc, specs, artist, stocksObj) {
            document.getElementById('modal-product-title').textContent = 'Edit Produk';
            const form = document.getElementById('product-form');
            form.action = '/admin/products/' + id;
            document.getElementById('product-form-method').value = 'PUT';
            document.getElementById('product-form-method').disabled = false;

            document.getElementById('p-name').value = name;
            document.getElementById('p-price').value = price;
            document.getElementById('p-desc').value = desc || '';
            document.getElementById('p-specs').value = specs || '';
            document.getElementById('p-artist').value = artist || '';
            document.getElementById('product-img-preview').classList.add('hidden');

            ['S','M','L','XL'].forEach(s => {
                const el = document.getElementById('p-stock-' + s);
                if (el) el.value = (stocksObj && stocksObj[s] !== undefined) ? stocksObj[s] : '';
            });

            document.getElementById('modal-product').classList.add('open');
        }

        function closeProductModal() {
            document.getElementById('modal-product').classList.remove('open');
        }

        // Artwork Modal Handlers
        function openAddArtworkModal() {
            document.getElementById('modal-artwork-title').textContent = 'Tambah Artwork / Editorial';
            const form = document.getElementById('artwork-form');
            form.action = '/admin/artworks';
            form.reset();
            document.getElementById('artwork-form-method').disabled = true;
            document.getElementById('artwork-img-preview').classList.add('hidden');
            document.getElementById('modal-artwork').classList.add('open');
        }

        function openEditArtworkModal(id, title, artist, medium, year, status, desc) {
            document.getElementById('modal-artwork-title').textContent = 'Edit Artwork / Editorial';
            const form = document.getElementById('artwork-form');
            form.action = '/admin/artworks/' + id;
            document.getElementById('artwork-form-method').value = 'PUT';
            document.getElementById('artwork-form-method').disabled = false;

            document.getElementById('art-title').value = title;
            document.getElementById('art-artist').value = artist || '';
            document.getElementById('art-medium').value = medium || '';
            document.getElementById('art-year').value = year || '';
            document.getElementById('art-status').value = status || '';
            document.getElementById('art-desc').value = desc || '';
            document.getElementById('artwork-img-preview').classList.add('hidden');

            document.getElementById('modal-artwork').classList.add('open');
        }

        function closeArtworkModal() {
            document.getElementById('modal-artwork').classList.remove('open');
        }

        // Media Modal Handlers
        function openMediaModal(id, title, desc) {
            document.getElementById('media-form').action = '/admin/media/' + id;
            document.getElementById('m-title').value = title || '';
            document.getElementById('media-img-preview').classList.add('hidden');
            document.getElementById('media-img-input').value = '';
            document.getElementById('modal-media').classList.add('open');
        }

        function closeMediaModal() {
            document.getElementById('modal-media').classList.remove('open');
        }

        // ESC Key to dismiss modals
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeProductModal();
                closeArtworkModal();
                closeMediaModal();
            }
        });

        // Auto dismiss toast
        const toast = document.getElementById('toast-success');
        if (toast) {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s';
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }
    </script>
</body>
</html>
