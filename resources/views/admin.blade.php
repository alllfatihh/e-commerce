<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notisse — Atelier Admin Panel</title>
    <!-- Favicon -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function() {
            const originalFetch = window.fetch;
            window.fetch = function(url, init) {
                init = init || {};
                const method = (init.method || 'GET').toUpperCase();
                if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (token) {
                        if (!init.headers) {
                            init.headers = {};
                        }
                        if (init.headers instanceof Headers) {
                            if (!init.headers.has('X-CSRF-TOKEN')) init.headers.set('X-CSRF-TOKEN', token);
                        } else if (Array.isArray(init.headers)) {
                            if (!init.headers.some(([k]) => k.toLowerCase() === 'x-csrf-token')) {
                                init.headers.push(['X-CSRF-TOKEN', token]);
                            }
                        } else {
                            if (!init.headers['X-CSRF-TOKEN'] && !init.headers['x-csrf-token']) {
                                init.headers['X-CSRF-TOKEN'] = token;
                            }
                        }
                    }
                }
                return originalFetch.call(this, url, init);
            };
        })();
    </script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts – same as welcome.blade.php -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Space+Grotesk:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['"Inter"', 'sans-serif'],
                        sans: ['"Inter"', 'sans-serif'],
                        'space-grotesk': ['"Space Grotesk"', 'sans-serif'],
                        spaceGrotesk: ['"Space Grotesk"', 'sans-serif'],
                        'inter': ['"Inter"', 'sans-serif'],
                        inter: ['"Inter"', 'sans-serif'],
                        'montserrat': ['"Space Grotesk"', 'sans-serif'],
                        montserrat: ['"Space Grotesk"', 'sans-serif'],
                        'roboto': ['"Inter"', 'sans-serif'],
                        roboto: ['"Inter"', 'sans-serif'],
                        'helvetica': ['"Inter"', 'sans-serif'],
                        helvetica: ['"Inter"', 'sans-serif'],
                        'neue-montreal': ['"Space Grotesk"', 'sans-serif'],
                        neueMontreal: ['"Space Grotesk"', 'sans-serif'],
                        cursive: ['Caveat', 'cursive'],
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background-color: #f7f7f5;
            color: #111111;
            overflow-x: hidden;
        }

        .font-space-grotesk,
        .font-montserrat,
        .font-neue-montreal,
        [class*="font-space-grotesk"],
        [class*="font-spaceGrotesk"] {
            font-family: 'Space Grotesk', sans-serif !important;
        }

        .font-inter,
        .font-roboto,
        .font-helvetica,
        [class*="font-inter"] {
            font-family: 'Inter', sans-serif !important;
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
            font-family: 'Inter', sans-serif;
            border-radius: 2px;
        }
        .admin-input:focus {
            border-color: #111111;
            box-shadow: 0 0 0 1px #111111;
        }

        /* ── Modal backdrop ────────────────────── */
        .modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(1px);
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
            font-family: 'Space Grotesk', sans-serif;
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
            font-family: 'Space Grotesk', sans-serif;
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
            font-family: 'Space Grotesk', sans-serif;
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

        /* ── Custom Select (Clean SVG Chevron & Native Removed) ── */
        .admin-select {
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
            padding: 0.65rem 2.25rem 0.65rem 0.9rem;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            border-radius: 2px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23111111' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 0.9rem;
            cursor: pointer;
        }
        .admin-select:focus {
            border-color: #111111;
            box-shadow: 0 0 0 1px #111111;
        }

        /* ── Action Buttons ────────────────────── */
        .btn-action-edit {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.65rem;
            font-size: 11px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #f3f4f6;
            color: #1f2937;
            border: 1px solid #e5e7eb;
            border-radius: 2px;
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .btn-action-edit:hover {
            background: #111111;
            color: #ffffff;
            border-color: #111111;
        }

        .btn-action-delete {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.65rem;
            font-size: 11px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 2px;
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .btn-action-delete:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        /* ── Dropzone & Upload Box ──────────────── */
        .admin-dropzone {
            position: relative;
            border: 2px dashed #d1d5db;
            background: #fafafa;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border-radius: 2px;
        }
        .admin-dropzone:hover,
        .admin-dropzone.dragover {
            border-color: #111111;
            background: #ffffff;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
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
            <img src="{{ asset('logo.png') }}" alt="Notisse" class="h-6 w-auto object-contain brightness-0 invert opacity-95 group-hover:opacity-100 transition-opacity">
            <p class="font-inter text-[10px] text-white/70 tracking-widest mt-1.5 uppercase">Atelier Admin Panel</p>
        </a>
    </div>

    <!-- Nav -->
    <nav class="flex-1 py-6 space-y-1 overflow-y-auto">
        <a href="#section-products" onclick="showSection('products')"
           class="nav-link active flex items-center gap-3 px-6 py-3.5 text-xs font-space-grotesk tracking-wider uppercase text-white/90"
           id="nav-products">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span>Products</span>
        </a>

        <a href="#section-artists" onclick="showSection('artists')"
           class="nav-link flex items-center gap-3 px-6 py-3.5 text-xs font-space-grotesk tracking-wider uppercase text-white/90"
           id="nav-artists">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Artists (Seniman)</span>
        </a>

        <a href="#section-artworks" onclick="showSection('artworks')"
           class="nav-link flex items-center gap-3 px-6 py-3.5 text-xs font-space-grotesk tracking-wider uppercase text-white/90"
           id="nav-artworks">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>Artworks & Editorial</span>
        </a>

        <a href="#section-media" onclick="showSection('media')"
           class="nav-link flex items-center gap-3 px-6 py-3.5 text-xs font-space-grotesk tracking-wider uppercase text-white/90"
           id="nav-media">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span>Site Media</span>
        </a>

        <a href="#section-orders" onclick="showSection('orders')"
           class="nav-link flex items-center gap-3 px-6 py-3.5 text-xs font-space-grotesk tracking-wider uppercase text-white/90"
           id="nav-orders">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17H5a2 2 0 0 0-2 2v2h14v-2a2 2 0 0 0-2-2h-4z"/><path d="M12 12V3"/><path d="M8 7l4-4 4 4"/></svg>
            <span>Orders</span>
        </a>

        <a href="#section-offerings" onclick="showSection('offerings')"
           class="nav-link flex items-center justify-between px-6 py-3.5 text-xs font-space-grotesk tracking-wider uppercase text-white/90"
           id="nav-offerings">
            <span class="flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>Artwork Offerings</span>
            </span>
            @if(($stats['total_offerings'] ?? 0) > 0)
                <span class="bg-accentYellow text-black font-extrabold text-[10px] px-2 py-0.5 rounded-full font-space-grotesk">
                    {{ $stats['total_offerings'] }}
                </span>
            @endif
        </a>

        <a href="#section-discounts" onclick="showSection('discounts')"
           class="nav-link flex items-center justify-between px-6 py-3.5 text-xs font-space-grotesk tracking-wider uppercase text-white/90"
           id="nav-discounts">
            <span class="flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 14l6-6M9 8h.01M15 14h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Discount Codes</span>
            </span>
            @if(($stats['total_discounts'] ?? 0) > 0)
                <span class="bg-white/20 text-white font-bold text-[10px] px-2 py-0.5 rounded-full font-space-grotesk">
                    {{ $stats['total_discounts'] }}
                </span>
            @endif
        </a>

        <a href="#section-admins" onclick="showSection('admins')"
           class="nav-link flex items-center justify-between px-6 py-3.5 text-xs font-space-grotesk tracking-wider uppercase text-white/90"
           id="nav-admins">
            <span class="flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Kelola Admin</span>
            </span>
            @if(($stats['total_admins'] ?? 0) > 0)
                <span class="bg-white/20 text-white font-bold text-[10px] px-2 py-0.5 rounded-full font-space-grotesk">
                    {{ $stats['total_admins'] }}
                </span>
            @endif
        </a>

        <a href="#section-settings" onclick="showSection('settings')"
           class="nav-link flex items-center gap-3 px-6 py-3.5 text-xs font-space-grotesk tracking-wider uppercase text-white/90"
           id="nav-settings">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1Z"/></svg>
            <span>Site Settings</span>
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
        <a href="/" target="_blank" class="block text-xs text-white/80 hover:text-white tracking-widest uppercase transition-colors pt-1 font-space-grotesk">
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
            <h1 class="font-space-grotesk font-bold text-base uppercase tracking-[0.2em] text-brandDark" id="page-title">
                Products Catalog Management
            </h1>
            <p class="text-xs text-gray-400 font-inter tracking-wide mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="flex items-center gap-4">
            @if(session('success'))
            <div id="toast-success" class="flex items-center gap-2 bg-accentYellow px-4 py-2 text-xs font-space-grotesk font-bold uppercase tracking-wide text-brandDark border border-black/10 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div id="toast-error" class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 text-xs font-space-grotesk font-bold uppercase tracking-wide border border-red-700 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
            @endif
            <a href="/" target="_blank" class="text-xs font-space-grotesk font-semibold tracking-wider uppercase border border-gray-300 hover:border-black px-3.5 py-1.5 transition-colors text-gray-700 hover:text-black">
                Lihat Toko &rarr;
            </a>
        </div>
    </header>

    <!-- ── STAT CARDS ───────────────────────────────────────────── -->
    <div class="px-8 pt-8 pb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm">
            <p class="text-[11px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 mb-1">Total Products</p>
            <p class="font-space-grotesk font-extrabold text-2xl text-brandDark">{{ $stats['total_products'] ?? $products->count() }}</p>
            <span class="text-[10px] text-gray-400 font-inter mt-0.5 block">Katalog item toko</span>
        </div>
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm" style="animation-delay:0.05s">
            <p class="text-[11px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 mb-1">Physical Stock</p>
            <p class="font-space-grotesk font-extrabold text-2xl text-brandDark">{{ $stats['total_stock'] ?? 0 }}</p>
            <span class="text-[10px] text-gray-400 font-inter mt-0.5 block">Total size S - XL</span>
        </div>
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm" style="animation-delay:0.1s">
            <p class="text-[11px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 mb-1">Artists Collab</p>
            <p class="font-space-grotesk font-extrabold text-2xl text-brandDark">{{ $stats['total_artists'] ?? $artists->count() }}</p>
            <span class="text-[10px] text-gray-400 font-inter mt-0.5 block">Seniman kolaborator</span>
        </div>
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm" style="animation-delay:0.15s">
            <p class="text-[11px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 mb-1">Artworks & Editorial</p>
            <p class="font-space-grotesk font-extrabold text-2xl text-brandDark">{{ $stats['total_artworks'] ?? $artworks->count() }}</p>
            <span class="text-[10px] text-gray-400 font-inter mt-0.5 block">Karya seni galeri</span>
        </div>
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm" style="animation-delay:0.2s">
            <p class="text-[11px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 mb-1">Artwork Offers</p>
            <p class="font-space-grotesk font-extrabold text-2xl text-brandDark">{{ $stats['total_offerings'] ?? $offerings->count() }}</p>
            <span class="text-[10px] text-gray-400 font-inter mt-0.5 block">Penawaran kolektor</span>
        </div>
        <div class="bg-white border border-gray-200 p-5 section-card shadow-sm" style="animation-delay:0.25s">
            <p class="text-[11px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 mb-1">Revenue (IDR)</p>
            <p class="font-space-grotesk font-extrabold text-xl text-brandDark">
                Rp {{ number_format($stats['paid_revenue'] ?? $orders->where('status','paid')->sum('total_amount'), 0, ',', '.') }}
            </p>
            <span class="text-[10px] text-gray-400 font-inter mt-0.5 block">{{ $stats['total_orders'] ?? $orders->count() }} Pesanan</span>
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
                    <h2 class="font-space-grotesk font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Products Catalog</h2>
                    <p class="text-xs text-gray-500 font-inter mt-0.5">Kelola produk pakaian, deskripsi editorial, spesifikasi fabric, seniman, foto (Depan, Belakang, Footage, Size Chart), dan stok per ukuran.</p>
                </div>
                <button onclick="openAddProductModal()" class="btn-primary flex items-center gap-2 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>Add Product</span>
                </button>
            </div>

            <div class="bg-white border border-gray-200 overflow-x-auto shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr class="text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-500">
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
                                        <p class="font-space-grotesk font-bold text-sm text-gray-900 uppercase">{{ $product->name }}</p>
                                        @if(empty($product->artist) || $product->artist === 'Notisse Original')
                                            <span class="inline-block text-[10px] font-space-grotesk font-bold uppercase tracking-wider bg-black text-white px-2 py-0.5 mt-1">
                                                Notisse Original
                                            </span>
                                        @else
                                            <span class="inline-block text-[10px] font-space-grotesk font-bold uppercase tracking-wider bg-[#E2FF00] text-black px-2 py-0.5 mt-1 border border-black/10">
                                                Collab: {{ $product->artist }}
                                            </span>
                                        @endif
                                        @if($product->specs)
                                        <p class="text-[10px] text-gray-400 font-mono mt-0.5 line-clamp-1 max-w-xs">{{ $product->specs }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-space-grotesk font-bold text-sm text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($product->stocks as $stock)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 border text-[11px] font-space-grotesk font-semibold {{ $stock->stock < 5 ? 'border-red-200 text-red-600 bg-red-50' : 'border-gray-200 text-gray-700 bg-gray-50' }}">
                                        {{ $stock->size }}:
                                        <span class="font-bold">{{ $stock->stock }}</span>
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1 text-[11px] font-inter">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $product->image ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                        <span class="text-gray-600">Cover Depan: <strong class="font-space-grotesk">{{ $product->image ? 'Ada' : 'Default' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $product->back_image ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                        <span class="text-gray-600">Belakang: <strong class="font-space-grotesk">{{ $product->back_image ? 'Ada' : 'Default' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $product->footage_image ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                        <span class="text-gray-600">On-Model Footage: <strong class="font-space-grotesk">{{ $product->footage_image ? 'Ada' : 'Default' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $product->size_chart_image ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                        <span class="text-gray-600">Size Chart Guide: <strong class="font-space-grotesk">{{ $product->size_chart_image ? 'Ada' : 'Default' }}</strong></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2 font-space-grotesk">
                                    @php
                                        $resImg = function($p) {
                                            if (!$p) return '';
                                            if (\Illuminate\Support\Str::startsWith($p, 'http')) return $p;
                                            if (file_exists(public_path('storage/' . $p))) return asset('storage/' . $p);
                                            return asset($p);
                                        };
                                        $productData = [
                                            'id'               => $product->id,
                                            'name'             => $product->name,
                                            'price'            => $product->price,
                                            'desc'             => $product->description ?? '',
                                            'specs'            => $product->specs ?? '',
                                            'artist'           => $product->artist ?? '',
                                            'stocks'           => $product->stocks->pluck('stock','size'),
                                            'frontImg'         => $resImg($product->image),
                                            'backImg'          => $resImg($product->back_image),
                                            'footageImg'       => $resImg($product->footage_image),
                                            'sizechartImg'     => $resImg($product->size_chart_image),
                                        ];
                                    @endphp
                                    <button type="button"
                                        onclick="openEditProductFromBtn(this)"
                                        data-product="{{ json_encode($productData) }}"
                                        class="btn-action-edit">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit
                                    </button>
                                    <form action="/admin/products/{{ $product->id }}" method="POST" class="inline"
                                          onsubmit="event.preventDefault(); askConfirm('Yakin hapus produk {{ addslashes($product->name) }}?', this, 'Hapus Produk');">
                                        @method('DELETE') @csrf
                                        <button type="submit" class="btn-action-delete">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400 font-inter">
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
                    <h2 class="font-space-grotesk font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Artists & Collaborators (Seniman)</h2>
                    <p class="text-xs text-gray-500 font-inter mt-0.5">Kelola profil seniman kolaborator Notisse (seperti Mustafa Alatas, dll), kota domisili, biografi, dan karya seni yang tampil di halaman 'Artist Collab'.</p>
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
                            @if($art->is_newcomer)
                            <div class="absolute top-2.5 right-2.5">
                                <span class="text-[9px] font-bold font-space-grotesk tracking-widest uppercase bg-amber-400 text-black px-2 py-0.5 shadow-sm">
                                    ★ NEWCOMER
                                </span>
                            </div>
                            @endif
                            @if($art->city)
                            <div class="absolute bottom-2.5 left-2.5">
                                <span class="text-[9px] font-bold font-space-grotesk tracking-widest uppercase bg-black text-white px-2 py-0.5 shadow-sm">
                                    {{ $art->city }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="p-4 space-y-2">
                            <h3 class="font-space-grotesk font-bold text-base text-gray-900 uppercase">{{ $art->name }}</h3>
                            <p class="line-clamp-2 text-xs text-gray-500 font-inter leading-relaxed">
                                {{ $art->bio ?: 'Seniman kolaborator eksklusif lini tekstil dan garmen atelier Notisse.' }}
                            </p>

                            <!-- Linked Creations Badges -->
                            <div class="flex items-center gap-2 pt-2 border-t border-gray-100 text-[10px] font-space-grotesk font-bold uppercase">
                                <span class="px-2 py-0.5 bg-neutral-100 text-neutral-800 rounded-sm">🎨 {{ $art->artworks->count() }} Artworks</span>
                                <span class="px-2 py-0.5 bg-red-50 text-brandRed rounded-sm">📰 {{ $art->editorials->count() }} Editorial</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-3 bg-gray-50 border-t border-gray-100 space-y-2 font-space-grotesk text-xs">
                        <button type="button" onclick="viewArtistCreations({{ $art->id }})"
                                class="w-full text-center py-1.5 px-3 bg-brandDark text-white text-[10px] font-bold uppercase tracking-wider hover:bg-black transition-colors cursor-pointer flex items-center justify-center gap-1.5 shadow-sm">
                            <span>Kelola Karya & Editorial Seniman</span>
                            <span>&rarr;</span>
                        </button>
                        <div class="flex items-center justify-between pt-1">
                            <div class="flex items-center gap-2 text-[10px]">
                                <button type="button" onclick="openAddArtworkModal({{ $art->id }})" class="text-neutral-700 hover:text-black font-bold uppercase underline cursor-pointer">+ Artwork</button>
                                <span class="text-gray-300">&bull;</span>
                                <button type="button" onclick="openAddEditorialModal({{ $art->id }})" class="text-brandRed hover:underline font-bold uppercase cursor-pointer">+ Editorial</button>
                            </div>
                            <div class="flex items-center gap-2">
                                @php
                                    $artistData = [
                                        'id'               => $art->id,
                                        'name'             => $art->name,
                                        'city'             => $art->city ?? '',
                                        'bio'              => $art->bio ?? '',
                                        'photoUrl'         => $art->photo ? asset($art->photo) : '',
                                        'artUrl'           => $art->artwork_preview ? asset($art->artwork_preview) : '',
                                        'is_newcomer'      => (bool)$art->is_newcomer,
                                    ];
                                @endphp
                                <button type="button"
                                    onclick="openEditArtistFromBtn(this)"
                                    data-artist="{{ json_encode($artistData) }}"
                                    class="btn-action-edit">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </button>
                                <form action="/admin/artists/{{ $art->id }}" method="POST" class="inline"
                                      onsubmit="event.preventDefault(); askConfirm('Hapus seniman {{ addslashes($art->name) }} beserta seluruh karya & editorialnya?', this, 'Hapus Seniman');">
                                    @method('DELETE') @csrf
                                    <button type="submit" class="btn-action-delete">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full p-12 bg-white border border-gray-200 text-center text-gray-400 font-inter text-sm">
                    Belum ada data seniman. Klik "Add Artist (Tambah Seniman)" di atas untuk menambahkan.
                </div>
                @endforelse
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────
             SECTION 3: ARTWORKS & ARTIST EDITORIAL
        ───────────────────────────────────────────────────────── --}}
        <section id="section-artworks" class="section-card hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="font-space-grotesk font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Artworks & Artist Editorial</h2>
                    <p class="text-xs text-gray-500 font-inter mt-0.5">Kelola karya seni fisik orisinil (lukisan/mixed media) dan artikel editorial kuratorial yang terhubung secara spesifik per seniman.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <button onclick="openAddArtworkModal()" class="btn-primary flex items-center gap-2 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>+ Tambah Artwork Fisik</span>
                    </button>
                    <button onclick="openAddEditorialModal()" class="btn-accent flex items-center gap-2 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        <span>+ Tambah Editorial Artikel</span>
                    </button>
                </div>
            </div>

            <!-- Filter Toolbar (Per Seniman & Per Tipe) -->
            <div class="bg-gray-50 border border-gray-200 p-4 mb-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <label class="text-[11px] font-space-grotesk font-bold uppercase tracking-wider text-gray-500 shrink-0">Filter Seniman:</label>
                        <select id="creation-filter-artist" onchange="filterCreations()" class="admin-select text-xs py-1.5 max-w-[240px]">
                            <option value="all">Semua Seniman (All Artists - {{ $artists->count() }})</option>
                            @foreach($artists as $artObj)
                                <option value="{{ $artObj->id }}">{{ $artObj->name }} ({{ $artObj->city ?: 'Indonesia' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <label class="text-[11px] font-space-grotesk font-bold uppercase tracking-wider text-gray-500 shrink-0">Tipe:</label>
                        <select id="creation-filter-type" onchange="filterCreations()" class="admin-select text-xs py-1.5">
                            <option value="all">Semua Tipe (Total: {{ $artworks->count() + $editorials->count() }})</option>
                            <option value="artwork">🎨 Hanya Artwork Fisik ({{ $artworks->count() }})</option>
                            <option value="editorial">📰 Hanya Editorial Artikel ({{ $editorials->count() }})</option>
                        </select>
                    </div>
                </div>

                <div class="text-[11px] font-space-grotesk font-semibold text-gray-400 shrink-0">
                    Menampilkan <span id="creation-count-display" class="font-bold text-gray-900">{{ $artworks->count() + $editorials->count() }}</span> konten kurasi
                </div>
            </div>

            <!-- Creation Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="creations-grid">
                {{-- ARTWORKS --}}
                @foreach($artworks as $art)
                @php
                    $aImg = $art->image;
                    if ($aImg) {
                        if (Str::startsWith($aImg, 'http')) { $artUrl = $aImg; }
                        elseif (file_exists(public_path('storage/' . $aImg))) { $artUrl = asset('storage/' . $aImg); }
                        else { $artUrl = asset($aImg); }
                    } else {
                        $artUrl = 'https://placehold.co/600x800/d9d9d9/555555?text=Artwork';
                    }
                    $artistOwner = $art->artist;
                @endphp
                <div class="creation-item bg-white border border-gray-200 overflow-hidden flex flex-col justify-between group shadow-sm transition-all hover:border-black"
                     data-artist-id="{{ $art->artist_id }}" data-type="artwork">
                    <div>
                        <!-- Artist Header Bar -->
                        <div class="px-4 py-2.5 bg-neutral-900 text-white flex items-center justify-between border-b border-neutral-800">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full overflow-hidden bg-white/20 inline-block shrink-0">
                                    <img src="{{ $artistOwner && $artistOwner->photo ? (Str::startsWith($artistOwner->photo, 'http') ? $artistOwner->photo : asset($artistOwner->photo)) : 'https://placehold.co/100x100/333/fff?text=A' }}"
                                         class="w-full h-full object-cover">
                                </span>
                                <span class="font-space-grotesk font-bold text-xs uppercase tracking-wide truncate max-w-[170px]">
                                    {{ $artistOwner ? $artistOwner->name : 'Seniman Kolaborator' }}
                                </span>
                            </div>
                            <span class="text-[9px] font-space-grotesk font-bold uppercase tracking-widest px-2 py-0.5 bg-neutral-800 text-brandAccent border border-brandAccent/30">
                                🎨 Artwork
                            </span>
                        </div>

                        <!-- Thumbnail -->
                        <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden border-b border-gray-200">
                            <img src="{{ $artUrl }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='https://placehold.co/600x800/d9d9d9/555555?text=Artwork'">
                            <div class="absolute top-3 left-3">
                                <span class="text-[9px] font-bold font-space-grotesk tracking-widest uppercase bg-black text-white px-2 py-0.5 shadow-sm">
                                    {{ $art->status ?: 'Available for Acquisition' }}
                                </span>
                            </div>
                            @if($art->year)
                            <div class="absolute bottom-2.5 right-2.5 bg-black/75 backdrop-blur-[1px] text-white text-[9px] font-mono px-2 py-0.5">
                                {{ $art->year }}
                            </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 space-y-2.5">
                            <div>
                                <h3 class="font-space-grotesk font-bold text-base text-gray-900 uppercase leading-snug">{{ $art->title }}</h3>
                                <p class="text-xs text-gray-500 font-inter mt-0.5">
                                    {{ $art->medium ?: 'Oil on Canvas' }} @if($art->dimensions) &bull; {{ $art->dimensions }} @endif
                                </p>
                            </div>
                            @if($art->price)
                            <p class="text-xs font-mono font-bold text-emerald-700">
                                Est. Nilai: Rp {{ number_format($art->price, 0, ',', '.') }}
                            </p>
                            @endif
                            <p class="line-clamp-2 text-xs text-gray-600 font-inter leading-relaxed">
                                {{ $art->description ?: 'Karya seni fisik orisinil oleh seniman kolaborator Notisse.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between font-space-grotesk text-xs">
                        <span class="text-[10px] font-mono text-gray-400 uppercase">#ARTW-{{ $art->id }}</span>
                        <div class="flex items-center gap-3">
                            @php
                                $artworkData = [
                                    'id'         => $art->id,
                                    'artist_id'  => $art->artist_id,
                                    'title'      => $art->title,
                                    'medium'     => $art->medium ?? '',
                                    'dimensions' => $art->dimensions ?? '',
                                    'year'       => $art->year ?? '',
                                    'status'     => $art->status ?? '',
                                    'price'      => $art->price ?? '',
                                    'desc'       => $art->description ?? '',
                                ];
                            @endphp
                            <button type="button"
                                onclick="openEditArtworkFromBtn(this)"
                                data-artwork="{{ json_encode($artworkData) }}"
                                class="btn-action-edit">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </button>
                            <form action="/admin/artworks/{{ $art->id }}" method="POST" class="inline"
                                  onsubmit="event.preventDefault(); askConfirm('Hapus karya seni {{ addslashes($art->title) }}?', this, 'Hapus Artwork');">
                                @method('DELETE') @csrf
                                <button type="submit" class="btn-action-delete">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- EDITORIALS --}}
                @foreach($editorials as $edit)
                @php
                    $eImg = $edit->image;
                    if ($eImg) {
                        if (Str::startsWith($eImg, 'http')) { $editUrl = $eImg; }
                        elseif (file_exists(public_path('storage/' . $eImg))) { $editUrl = asset('storage/' . $eImg); }
                        else { $editUrl = asset($eImg); }
                    } else {
                        $editUrl = 'https://placehold.co/600x600/d9d9d9/555555?text=Editorial';
                    }
                    $artistOwner = $edit->artist;
                @endphp
                <div class="creation-item bg-white border border-gray-200 overflow-hidden flex flex-col justify-between group shadow-sm transition-all hover:border-brandRed"
                     data-artist-id="{{ $edit->artist_id }}" data-type="editorial">
                    <div>
                        <!-- Artist Header Bar -->
                        <div class="px-4 py-2.5 bg-brandRed text-white flex items-center justify-between border-b border-red-700">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full overflow-hidden bg-white/20 inline-block shrink-0">
                                    <img src="{{ $artistOwner && $artistOwner->photo ? (Str::startsWith($artistOwner->photo, 'http') ? $artistOwner->photo : asset($artistOwner->photo)) : 'https://placehold.co/100x100/333/fff?text=A' }}"
                                         class="w-full h-full object-cover">
                                </span>
                                <span class="font-space-grotesk font-bold text-xs uppercase tracking-wide truncate max-w-[170px]">
                                    {{ $artistOwner ? $artistOwner->name : 'Seniman Kolaborator' }}
                                </span>
                            </div>
                            <span class="text-[9px] font-space-grotesk font-bold uppercase tracking-widest px-2 py-0.5 bg-white text-brandRed font-black">
                                📰 Editorial
                            </span>
                        </div>

                        <!-- Thumbnail -->
                        <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden border-b border-gray-200">
                            <img src="{{ $editUrl }}" alt="{{ $edit->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='https://placehold.co/600x600/d9d9d9/555555?text=Editorial'">
                            @if($edit->video_url)
                            <div class="absolute top-3 left-3 bg-black/85 text-white text-[9px] font-space-grotesk uppercase px-2 py-0.5 flex items-center gap-1 font-bold">
                                <svg class="w-3 h-3 text-brandAccent" fill="currentColor" viewBox="0 0 20 20"><polygon points="5 3 19 10 5 17 5 3"/></svg>
                                <span>Includes Video</span>
                            </div>
                            @endif
                            <div class="absolute bottom-2.5 right-2.5 bg-black/75 backdrop-blur-[1px] text-white text-[9px] font-inter px-2 py-0.5">
                                {{ $edit->author ?: 'Editorial Notisse' }}
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 space-y-2.5">
                            <div>
                                <h3 class="font-space-grotesk font-bold text-base text-gray-900 uppercase leading-snug">{{ $edit->title }}</h3>
                                <p class="text-xs text-gray-400 font-inter mt-0.5">Penulis: {{ $edit->author ?: 'Tim Kuratorial' }}</p>
                            </div>
                            <p class="line-clamp-2 text-xs text-gray-600 font-inter leading-relaxed">
                                {{ $edit->excerpt ?: Str::limit($edit->content, 120) }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between font-space-grotesk text-xs">
                        <span class="text-[10px] font-mono text-gray-400 uppercase">#EDIT-{{ $edit->id }}</span>
                        <div class="flex items-center gap-3">
                            @php
                                $editorialData = [
                                    'id'        => $edit->id,
                                    'artist_id' => $edit->artist_id,
                                    'title'     => $edit->title,
                                    'author'    => $edit->author ?? '',
                                    'excerpt'   => $edit->excerpt ?? '',
                                    'video_url' => $edit->video_url ?? '',
                                    'content'   => $edit->content ?? '',
                                ];
                            @endphp
                            <button type="button"
                                onclick="openEditEditorialFromBtn(this)"
                                data-editorial="{{ json_encode($editorialData) }}"
                                class="btn-action-edit">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </button>
                            <form action="/admin/editorials/{{ $edit->id }}" method="POST" class="inline"
                                  onsubmit="event.preventDefault(); askConfirm('Hapus artikel editorial {{ addslashes($edit->title) }}?', this, 'Hapus Editorial');">
                                @method('DELETE') @csrf
                                <button type="submit" class="btn-action-delete">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Empty state if no items or filter zero -->
                <div id="creations-empty-state" class="hidden col-span-full p-12 bg-white border border-gray-200 text-center text-gray-400 font-inter text-sm">
                    Tidak ada karya seni atau artikel editorial yang sesuai dengan filter yang dipilih.
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────
             SECTION 4: SITE MEDIA (CAROUSEL & FOOTAGE)
        ───────────────────────────────────────────────────────── --}}
        <section id="section-media" class="section-card hidden">
            <h2 class="font-space-grotesk font-extrabold text-lg uppercase tracking-[0.18em] mb-2 text-brandDark">Site Media & Lookbook Footage</h2>
            <p class="text-xs text-gray-500 font-inter mb-6">Kelola Hero Carousel slides, Footage On-Model, dan Galeri Foto Lookbook Photoshoot toko Notisse.</p>

            <!-- TAB: Carousel vs Footage vs Lookbook -->
            <div class="flex gap-0 border-b border-gray-200 mb-6">
                <button onclick="switchMediaTab('carousel')" id="tab-carousel"
                    class="tab-btn active px-5 py-2.5 text-xs font-space-grotesk uppercase tracking-widest text-gray-500 cursor-pointer">
                    Hero Carousel
                </button>
                <button onclick="switchMediaTab('footage')" id="tab-footage"
                    class="tab-btn px-5 py-2.5 text-xs font-space-grotesk uppercase tracking-widest text-gray-500 cursor-pointer">
                    Footage / Product Media
                </button>
                <button onclick="switchMediaTab('lookbook')" id="tab-lookbook"
                    class="tab-btn px-5 py-2.5 text-xs font-space-grotesk uppercase tracking-widest text-gray-500 cursor-pointer">
                    Lookbook Photoshoot ({{ $lookbooks->count() }})
                </button>
            </div>

            <!-- Carousel Tab Content -->
            <div id="media-tab-carousel">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 p-4 bg-neutral-50 border border-gray-200 rounded-sm">
                    <div>
                        <h3 class="font-space-grotesk font-bold text-sm uppercase tracking-wider text-black">Hero Slideshow Carousel</h3>
                        <p class="text-xs text-gray-500 font-inter mt-0.5">Gambar utama yang berganti otomatis di bagian atas halaman depan (Shop Hero).</p>
                    </div>
                    <button type="button" onclick="openCreateCarouselModal()" class="btn-primary text-xs px-4 py-2.5 cursor-pointer flex items-center justify-center gap-2 self-start sm:self-auto shrink-0 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Slide Carousel</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @forelse($carousels as $media)
                    <div class="bg-white border border-gray-200 overflow-hidden group shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="media-card relative">
                                @php
                                    $mUrl = Str::startsWith($media->image,'http') ? $media->image : (file_exists(public_path('storage/'.$media->image)) ? asset('storage/'.$media->image) : asset($media->image));
                                @endphp
                                <img src="{{ $mUrl }}" alt="{{ $media->title }}" onerror="this.style.display='none'" class="w-full h-full object-cover">
                                <span class="absolute top-2.5 left-2.5 bg-black/80 text-white text-[10px] font-space-grotesk font-bold px-2 py-0.5 uppercase tracking-wider z-10 backdrop-blur-[1px]">
                                    Slide #{{ $loop->iteration }}
                                </span>
                                <div class="media-card-overlay">
                                    <button type="button" onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}', '{{ $media->product_id ?? '' }}', '{{ $media->order ?? 1 }}')"
                                        class="btn-accent text-xs px-4 py-2 cursor-pointer">
                                        Ganti Gambar
                                    </button>
                                </div>
                            </div>
                            <div class="px-4 py-3">
                                <p class="text-xs font-space-grotesk font-bold uppercase tracking-wide text-gray-800">{{ $media->title ?? 'Slide ' . $loop->iteration }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5 font-inter">Urutan: <strong>{{ $media->order }}</strong></p>
                            </div>
                        </div>
                        <div class="px-4 py-2.5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
                            <button type="button" onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}', '{{ $media->product_id ?? '' }}', '{{ $media->order ?? 1 }}')"
                                class="btn-action-edit">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit Detail
                            </button>
                            <form action="/admin/media/{{ $media->id }}" method="POST"
                                  onsubmit="event.preventDefault(); askConfirm('Apakah Anda yakin ingin menghapus slide carousel ini?', this, 'Hapus Slide Carousel');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full p-12 bg-white border border-dashed border-gray-300 text-center space-y-3">
                        <p class="text-xs text-gray-400 font-inter">Belum ada slide carousel yang ditambahkan.</p>
                        <button type="button" onclick="openCreateCarouselModal()" class="btn-primary text-xs px-4 py-2 cursor-pointer">
                            + Tambah Slide Pertama
                        </button>
                    </div>
                    @endforelse
                </div>
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
                            <p class="text-xs font-space-grotesk font-bold uppercase tracking-wide text-gray-800">{{ $media->title ?? 'Footage ' . $loop->iteration }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">
                                Order {{ $media->order }} 
                                @if($media->product_id)
                                    &bull; <span class="text-emerald-600 font-semibold">Terkait Produk #{{ $media->product_id }}</span>
                                @endif
                            </p>
                        </div>
                        <button onclick="openMediaModal({{ $media->id }}, '{{ addslashes($media->title ?? '') }}', '{{ addslashes($media->description ?? '') }}', '{{ $media->product_id ?? '' }}')"
                            class="text-xs font-space-grotesk font-semibold uppercase tracking-wider text-blue-600 hover:underline">
                            Edit
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Lookbook Collections Cards -->
            <div id="media-tab-lookbook" class="hidden space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-white border border-gray-200 shadow-sm">
                    <div>
                        <h3 class="font-space-grotesk font-bold text-sm uppercase tracking-wider text-black">Lookbook Collections & Campaigns</h3>
                        <p class="text-xs text-gray-500 font-inter mt-0.5">Kelola album Lookbook. Klik salah satu card Lookbook untuk mengunggah video kampanye dan foto-foto editorialnya.</p>
                    </div>
                    <button type="button" onclick="openCreateLookbookModal()" class="btn-primary flex items-center gap-2 shrink-0 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>+ Tambah Album Lookbook</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($lookbooks as $lb)
                    @php
                        $coverUrl = $lb->cover_image ? (Str::startsWith($lb->cover_image, 'http') ? $lb->cover_image : (file_exists(public_path('storage/' . $lb->cover_image)) ? asset('storage/' . $lb->cover_image) : asset($lb->cover_image))) : ($lb->fotoshoots->first() ? (Str::startsWith($lb->fotoshoots->first()->image, 'http') ? $lb->fotoshoots->first()->image : (file_exists(public_path('storage/' . $lb->fotoshoots->first()->image)) ? asset('storage/' . $lb->fotoshoots->first()->image) : asset($lb->fotoshoots->first()->image))) : 'https://placehold.co/600x400/222/fff?text=' . urlencode($lb->title));
                    @endphp
                    <div class="bg-white border border-gray-200 overflow-hidden group shadow-sm flex flex-col justify-between hover:border-black transition-colors">
                        <div>
                            <!-- Card Preview Container -->
                            <div class="relative w-full aspect-[16/9] bg-neutral-900 overflow-hidden cursor-pointer" onclick="openManageLookbook({{ $lb->id }})">
                                <img src="{{ $coverUrl }}" alt="{{ $lb->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80 group-hover:opacity-100">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                
                                <!-- Top Badges -->
                                <div class="absolute top-3 left-3 flex items-center gap-2">
                                    <span class="bg-black/80 backdrop-blur-[1px] text-white text-[10px] font-space-grotesk font-bold uppercase tracking-wider px-2.5 py-0.5 border border-white/20">
                                        {{ $lb->year ?: '2025' }}
                                    </span>
                                </div>
                                <div class="absolute top-3 right-3">
                                    @if($lb->video_url)
                                        <span class="bg-emerald-600/90 text-white text-[10px] font-space-grotesk font-bold uppercase tracking-wider px-2 py-0.5 rounded-sm flex items-center gap-1 shadow">
                                            <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            <span>Video Aktif</span>
                                        </span>
                                    @else
                                        <span class="bg-neutral-800/80 text-gray-300 text-[10px] font-space-grotesk uppercase tracking-wider px-2 py-0.5">
                                            Tanpa Video
                                        </span>
                                    @endif
                                </div>

                                <!-- Bottom Info Overlay -->
                                <div class="absolute bottom-3 left-3 right-3 flex items-end justify-between text-white">
                                    <div>
                                        <h4 class="font-space-grotesk font-bold text-sm uppercase tracking-wider line-clamp-1">{{ $lb->title }}</h4>
                                        <p class="text-[11px] text-gray-300 font-inter mt-0.5">{{ $lb->fotoshoots->count() }} Foto Photoshoot</p>
                                    </div>
                                    <span class="text-xs bg-white text-black px-2.5 py-1 font-space-grotesk font-bold uppercase tracking-wider shrink-0 shadow">
                                        Buka &rarr;
                                    </span>
                                </div>
                            </div>

                            <div class="p-4">
                                @if($lb->description)
                                    <p class="text-xs text-gray-600 font-inter line-clamp-2 mb-3">{{ $lb->description }}</p>
                                @endif
                                <div class="flex items-center gap-2 text-[11px] font-mono text-gray-500">
                                    <span>Tahun: {{ $lb->year }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $lb->fotoshoots->count() }} foto photoshoot</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Footer -->
                        <div class="p-4 pt-3 border-t border-gray-100 flex items-center justify-between bg-neutral-50">
                            <button type="button" onclick="openManageLookbook({{ $lb->id }})" class="btn-action-edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Kelola Isi & Video</span>
                            </button>
                            <form action="/admin/lookbooks/{{ $lb->id }}" method="POST"
                                  onsubmit="event.preventDefault(); askConfirm('Hapus album Lookbook {{ addslashes($lb->title) }} beserta seluruh fotonya?', this, 'Hapus Album Lookbook');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-16 text-center text-gray-400 text-sm font-inter bg-white border border-gray-200">
                        Belum ada album Lookbook. Klik tombol "+ Tambah Album Lookbook" untuk membuat lookbook baru.
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────
             SECTION 5: ORDERS
        ───────────────────────────────────────────────────────── --}}
        <section id="section-orders" class="section-card hidden">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h2 class="font-space-grotesk font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Recent Orders & Fulfillment</h2>
                    <p class="text-xs text-gray-500 font-inter mt-0.5">Kelola pesanan pelanggan, input nomor resi (AWB), perbarui status pengiriman (Processing, Shipped, Delivered), dan request pickup kurir.</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 overflow-x-auto shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr class="text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-500">
                            <th class="px-5 py-3.5">Order ID & Date</th>
                            <th class="px-5 py-3.5">Customer & Shipping Address</th>
                            <th class="px-5 py-3.5">Items</th>
                            <th class="px-5 py-3.5">Total Amount</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Courier & AWB (Resi)</th>
                            <th class="px-5 py-3.5 text-right">Update Status & Resi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                        @php
                            $statusClasses = [
                                'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'shipped' => 'bg-purple-100 text-purple-800 border-purple-300',
                                'delivered' => 'bg-teal-100 text-teal-800 border-teal-300',
                                'completed' => 'bg-emerald-600 text-white border-emerald-700 shadow-sm',
                                'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                                'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
                            ];
                            $badgeCls = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                        @endphp
                        <tr class="admin-tr align-top hover:bg-neutral-50 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="font-space-grotesk font-bold text-sm text-gray-900">#{{ $order->id }}</p>
                                <p class="text-[11px] text-gray-400 font-inter mt-0.5">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                                </p>
                                @if($order->midtrans_order_id)
                                <p class="text-[9px] font-mono text-gray-400 mt-1" title="Midtrans ID">MD: {{ Str::limit($order->midtrans_order_id, 16) }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 min-w-[220px]">
                                <p class="text-sm font-inter font-bold text-gray-900">{{ $order->customer_name }}</p>
                                <p class="text-xs text-gray-500 font-inter">{{ $order->customer_phone }}</p>
                                <p class="text-xs text-gray-400 font-inter mt-0.5 line-clamp-2" title="{{ $order->shipping_address }}">{{ $order->shipping_address }}</p>
                            </td>
                            <td class="px-5 py-4 min-w-[180px]">
                                <div class="space-y-1">
                                    @foreach($order->items as $it)
                                    <div class="text-xs font-inter text-gray-700">
                                        <span class="font-bold text-black font-space-grotesk uppercase">{{ $it->product_name }}</span>
                                        <span class="text-gray-500">({{ $it->size }} x{{ $it->quantity }})</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="font-space-grotesk font-bold text-sm text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                @if($order->subtotal > 0)
                                <div class="text-[10px] text-gray-500 font-inter mt-1 space-y-0.5">
                                    <p>Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                                    <p>Ongkir: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                                    @if($order->discount_amount > 0)
                                    <p class="text-emerald-600 font-semibold">Diskon: -Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-block px-2.5 py-1 text-[10px] font-space-grotesk font-bold uppercase tracking-wider rounded border {{ $badgeCls }}">
                                    @if($order->status === 'completed')
                                        ✓ Selesai (Diterima)
                                    @elseif($order->status === 'delivered')
                                        Pesanan Tiba
                                    @elseif($order->status === 'shipped')
                                        Sedang Dikirim
                                    @elseif($order->status === 'processing')
                                        Diproses
                                    @elseif($order->status === 'paid')
                                        Sudah Dibayar
                                    @else
                                        {{ $order->status }}
                                    @endif
                                </span>
                                @if($order->received_at)
                                <p class="text-[10px] text-emerald-700 font-inter font-medium mt-1">
                                    Diterima: {{ \Carbon\Carbon::parse($order->received_at)->format('d M Y, H:i') }}
                                </p>
                                @elseif(in_array($order->status, ['shipped', 'delivered']))
                                <p class="text-[10px] text-purple-600 font-inter italic mt-1">
                                    Menunggu konfirmasi pembeli
                                </p>
                                @endif
                            </td>
                            <td class="px-5 py-4 min-w-[190px]">
                                <p class="text-xs font-bold font-space-grotesk text-gray-800 uppercase">{{ $order->courier_name ?? '—' }}</p>
                                @if($order->tracking_number)
                                <div class="mt-1 flex items-center gap-1.5">
                                    <span class="font-mono text-xs font-bold bg-gray-100 text-gray-900 px-2 py-0.5 rounded border border-gray-300">
                                        {{ $order->tracking_number }}
                                    </span>
                                </div>
                                @else
                                <p class="text-[11px] text-gray-400 italic mt-0.5">Resi belum diinput</p>
                                @endif

                                @if($order->biteship_order_id)
                                <p class="text-[9px] text-emerald-600 font-mono mt-1">Biteship: {{ Str::limit($order->biteship_order_id, 18) }}</p>
                                @endif

                                @if(in_array($order->status, ['paid', 'processing']) && empty($order->biteship_order_id))
                                <form action="/admin/orders/{{ $order->id }}/dispatch" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Kirim permintaan pickup ke Biteship sekarang?')" class="text-[10px] bg-[#E2FF00] hover:bg-yellow-300 text-black font-space-grotesk font-bold uppercase tracking-wider px-2 py-1 rounded border border-black/10 transition-colors">
                                        ⚡ Dispatch Biteship
                                    </button>
                                </form>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <form action="/admin/orders/{{ $order->id }}/status" method="POST" class="inline-flex flex-col gap-1.5 items-end">
                                    @csrf
                                    <div class="flex items-center gap-1.5">
                                        <select name="status" class="text-xs border border-gray-300 rounded px-2 py-1 font-space-grotesk font-semibold bg-white text-gray-800 focus:outline-none focus:border-black">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="px-2.5 py-1 bg-black text-white rounded text-[11px] font-space-grotesk font-bold uppercase tracking-wider hover:bg-neutral-800 transition-colors">
                                            Simpan
                                        </button>
                                    </div>
                                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Input No. Resi..." class="text-xs border border-gray-300 rounded px-2 py-1 font-mono text-gray-800 w-44 placeholder-gray-400 focus:border-black focus:outline-none">
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400 font-inter">Belum ada pesanan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ==============================
             SECTION 6: OFFERINGS
        =============================== -->
        <section id="section-offerings" class="section-card hidden">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h2 class="font-space-grotesk font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Artwork Offerings</h2>
                    <p class="text-xs text-gray-500 font-inter mt-0.5">Kelola dan tinjau semua penawaran karya seni fisik dari kolektor dan pelanggan.</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 overflow-x-auto shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr class="text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-500">
                            <th class="px-5 py-3.5 w-16 text-center">ID</th>
                            <th class="px-5 py-3.5">Reference / Tanggal</th>
                            <th class="px-5 py-3.5">User (Pembeli)</th>
                            <th class="px-5 py-3.5">Karya & Artis</th>
                            <th class="px-5 py-3.5">Harga Tawaran</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($offerings as $offer)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-5 py-4 text-center font-bold text-xs">{{ $offer->id }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-black text-xs font-space-grotesk">{{ $offer->reference_number }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5 uppercase font-inter">{{ $offer->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-xs text-gray-900">{{ $offer->user->name ?? 'Guest' }}</div>
                                    <div class="text-[11px] text-gray-400 font-inter">{{ $offer->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-xs text-gray-900">{{ $offer->artwork->title ?? 'Deleted Artwork' }}</div>
                                    <div class="text-[11px] text-gray-500 font-inter">{{ $offer->artwork->artist->name ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 font-space-grotesk font-bold text-xs text-brandDark">
                                    Rp {{ number_format($offer->offering_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                        {{ $offer->status == 'Diterima' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' :
                                           ($offer->status == 'Ditolak' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-amber-100 text-amber-800 border border-amber-200') }}">
                                        {{ $offer->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <form action="/admin/offerings/{{ $offer->id }}/status" method="POST" class="inline-flex items-center gap-2 justify-end">
                                        @csrf
                                        <select name="status" class="admin-select py-1 text-xs" style="padding-top: 0.35rem; padding-bottom: 0.35rem; width: auto;">
                                            <option value="Dalam Tinjauan Kurasi" {{ $offer->status == 'Dalam Tinjauan Kurasi' ? 'selected' : '' }}>Dalam Tinjauan</option>
                                            <option value="Diterima" {{ $offer->status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                            <option value="Ditolak" {{ $offer->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                        <button type="submit" class="btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.72rem;">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400 font-inter">Belum ada penawaran karya seni.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────
             SECTION 6.5: DISCOUNT & PROMO CODES MANAGEMENT
        ───────────────────────────────────────────────────────── --}}
        <section id="section-discounts" class="section-card hidden">
            <div class="mb-6 pb-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-space-grotesk font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Discount & Promo Codes Management</h2>
                    <p class="text-xs text-gray-500 font-inter mt-0.5">Kelola kupon promo, diskon persentase maupun potongan harga tetap untuk pelanggan toko Notisse.</p>
                </div>
                <div>
                    <button onclick="openCreateDiscountModal()" class="btn-primary flex items-center gap-2 cursor-pointer shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Kode Diskon</span>
                    </button>
                </div>
            </div>

            <!-- Table of Discount Codes -->
            <div class="overflow-x-auto border border-gray-200">
                <table class="w-full text-left text-xs font-inter border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200 font-space-grotesk font-bold uppercase tracking-wider text-[11px] text-gray-600">
                        <tr>
                            <th class="px-5 py-3.5">Kode Kupon</th>
                            <th class="px-5 py-3.5">Deskripsi</th>
                            <th class="px-5 py-3.5">Nilai Diskon</th>
                            <th class="px-5 py-3.5">Ketentuan</th>
                            <th class="px-5 py-3.5">Penggunaan</th>
                            <th class="px-5 py-3.5">Masa Berlaku</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($discounts as $discount)
                            <tr class="hover:bg-neutral-50/70 transition-colors">
                                <td class="px-5 py-4">
                                    <span class="font-space-grotesk font-bold text-sm tracking-widest text-black bg-neutral-100 px-2.5 py-1 border border-neutral-300 rounded-xs select-all">
                                        {{ $discount->code }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-600 max-w-xs truncate">
                                    {{ $discount->description ?: '-' }}
                                </td>
                                <td class="px-5 py-4 font-space-grotesk font-bold text-black text-sm">
                                    @if($discount->type === 'percentage')
                                        <span class="text-emerald-700">{{ (int)$discount->value }}% OFF</span>
                                        @if($discount->max_discount_amount)
                                            <p class="text-[10px] font-normal text-gray-400 font-inter">Maks. Rp {{ number_format($discount->max_discount_amount, 0, ',', '.') }}</p>
                                        @endif
                                    @else
                                        <span class="text-blue-700">Rp {{ number_format($discount->value, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-600 text-[11px]">
                                    @if($discount->min_order_amount > 0)
                                        <span>Min. Belanja: Rp {{ number_format($discount->min_order_amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-400">Tanpa min. belanja</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-space-grotesk text-xs">
                                    <span class="font-bold text-black">{{ $discount->used_count }}</span>
                                    <span class="text-gray-400">/ {{ $discount->usage_limit ?: '∞' }} dipakai</span>
                                </td>
                                <td class="px-5 py-4 text-[11px] text-gray-500">
                                    @if($discount->start_date || $discount->end_date)
                                        <div>{{ $discount->start_date ? $discount->start_date->format('d/m/Y') : 'Mulai Sekarang' }}</div>
                                        <div class="text-gray-400">&darr; {{ $discount->end_date ? $discount->end_date->format('d/m/Y') : 'Tanpa batas' }}</div>
                                    @else
                                        <span class="text-emerald-600 font-medium">Selalu Aktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <form action="/admin/discounts/{{ $discount->id }}/toggle" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="Klik untuk ubah status"
                                            class="cursor-pointer px-2.5 py-1 text-[10px] font-space-grotesk font-bold uppercase tracking-wider border rounded-xs transition-colors {{ $discount->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 border-gray-300 hover:bg-gray-200' }}">
                                            {{ $discount->is_active ? '● Aktif' : '○ Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button onclick='openEditDiscountModal(@json($discount))'
                                            class="btn-action-edit">
                                            Edit
                                        </button>
                                        <form action="/admin/discounts/{{ $discount->id }}" method="POST" onsubmit="return confirm('Hapus kode diskon {{ $discount->code }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-sm text-gray-400 font-inter">
                                    Belum ada kode diskon yang dibuat. Klik tombol "Tambah Kode Diskon" untuk membuat kupon pertama.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────
             SECTION: ADMIN & STAFF MANAGEMENT
        ───────────────────────────────────────────────────────── --}}
        <section id="section-admins" class="section-card hidden">
            <div class="mb-6 pb-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-space-grotesk font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Admin & Staff Management</h2>
                    <p class="text-xs text-gray-500 font-inter mt-0.5">Kelola akun administrator sistem Notisse. Tambah akun pengelola baru, perbarui nama/email/password, atau hapus akses admin.</p>
                </div>
                <div>
                    <button onclick="openCreateAdminModal()" class="btn-primary flex items-center gap-2 cursor-pointer shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Admin Baru</span>
                    </button>
                </div>
            </div>

            <!-- Table of Admins -->
            <div class="overflow-x-auto border border-gray-200">
                <table class="w-full text-left text-xs font-inter border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200 font-space-grotesk font-bold uppercase tracking-wider text-[11px] text-gray-600">
                        <tr>
                            <th class="px-5 py-3.5">Administrator</th>
                            <th class="px-5 py-3.5">Email</th>
                            <th class="px-5 py-3.5">No. Telepon</th>
                            <th class="px-5 py-3.5">Role</th>
                            <th class="px-5 py-3.5">Terdaftar</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($admins as $adminItem)
                            <tr class="hover:bg-neutral-50/70 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-black text-white font-space-grotesk font-bold text-xs flex items-center justify-center shrink-0 uppercase">
                                            {{ substr($adminItem->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-space-grotesk font-bold text-sm text-black flex items-center gap-2">
                                                <span>{{ $adminItem->name }}</span>
                                                @if(auth()->id() === $adminItem->id)
                                                    <span class="bg-black text-white text-[9px] font-space-grotesk uppercase px-1.5 py-0.5 tracking-wider rounded-xs font-bold">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-gray-400">ID: #{{ $adminItem->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-700 font-mono text-xs select-all">
                                    {{ $adminItem->email }}
                                </td>
                                <td class="px-5 py-4 text-gray-600 text-xs">
                                    {{ $adminItem->phone ?: '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 text-[10px] font-space-grotesk font-bold uppercase tracking-wider bg-black text-white border border-black rounded-xs">
                                        ADMIN
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-500 text-[11px]">
                                    {{ $adminItem->created_at ? $adminItem->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button onclick='openEditAdminModal(@json($adminItem))'
                                            class="btn-action-edit">
                                            Edit
                                        </button>
                                        @if(auth()->id() !== $adminItem->id && count($admins) > 1)
                                            <form action="/admin/admins/{{ $adminItem->id }}" method="POST" onsubmit="return confirm('Hapus hak akses admin untuk {{ $adminItem->name }}? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-delete">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[11px] text-gray-400 italic px-2">Terkunci</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400 font-inter">
                                    Belum ada administrator lain. Klik tombol "Tambah Admin Baru" untuk menambahkan akun admin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────
             SECTION 7: SITE SETTINGS (BRANDING & GLOBAL CONFIG)
        ───────────────────────────────────────────────────────── --}}
        <section id="section-settings" class="section-card hidden">
            <div class="mb-6 pb-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-space-grotesk font-extrabold text-lg uppercase tracking-[0.18em] text-brandDark">Site Settings & Global Branding</h2>
                    <p class="text-xs text-gray-500 font-inter mt-0.5">Kelola teks pengumuman bergerak (Marquee Text), kontak resmi atelier, dan informasi footer toko Notisse.</p>
                </div>
            </div>

            <form action="/admin/settings" method="POST" class="max-w-3xl space-y-6">
                @csrf

                <!-- Marquee Text -->
                <div class="p-5 bg-neutral-50 border border-gray-200 space-y-2">
                    <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black">
                        Teks Marquee (Announcement Bar / Running Text) *
                    </label>
                    <input type="text" name="marquee_text" value="{{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }}" required
                        placeholder="Contoh: NOTISSE WORLDWIDE SHIPPING" class="admin-input">
                    <p class="text-[11px] text-gray-400 font-inter">Teks ini akan berjalan secara otomatis di bagian paling bawah halaman depan (global marquee banner).</p>
                </div>

                <!-- Kontak Informasi -->
                <div class="p-5 bg-neutral-50 border border-gray-200 space-y-4">
                    <h3 class="text-xs font-space-grotesk font-bold uppercase tracking-wider text-black border-b border-gray-200 pb-2">Kontak & Media Sosial</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Official Contact Email</label>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'hello@notisse.com' }}" placeholder="hello@notisse.com" class="admin-input">
                        </div>
                        <div>
                            <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">WhatsApp / Phone Number</label>
                            <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+62 811 2233 4455' }}" placeholder="+62 811 2233 4455" class="admin-input">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Instagram URL</label>
                        <input type="text" name="instagram_link" value="{{ $settings['instagram_link'] ?? '#' }}" placeholder="https://instagram.com/notisse" class="admin-input">
                    </div>
                </div>

                <!-- Footer Description -->
                <div class="p-5 bg-neutral-50 border border-gray-200 space-y-2">
                    <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black">
                        Deskripsi Singkat Footer (Brand Narrative)
                    </label>
                    <textarea name="footer_description" rows="3" class="admin-input resize-none leading-relaxed">{{ $settings['footer_description'] ?? 'Notisse is a concept brand exploring the intersection of modern aesthetics and raw street culture. Based in Indonesia, curating global narratives.' }}</textarea>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="btn-primary px-8 py-3 cursor-pointer">
                        Simpan Pengaturan Situs
                    </button>
                </div>
            </form>
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
                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 block">Catalog Management</span>
                <h3 id="modal-product-title" class="font-space-grotesk font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Add Product</h3>
            </div>
            <button onclick="closeProductModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form id="product-form" action="/admin/products" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST" disabled>

            <!-- SECTION A: Basic Info -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold font-space-grotesk uppercase tracking-wider text-black border-b border-gray-100 pb-1">1. Informasi Pokok & Kepemilikan Produk</h4>
                
                <!-- Opsi Pemilik Produk: Notisse Original vs Artist Collab -->
                <div>
                    <label class="block text-[11px] font-space-grotesk font-bold uppercase tracking-widest text-gray-800 mb-1.5">
                        Pemilik / Asal Produk (Product Ownership) *
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-2">
                        <label class="flex items-center gap-3 p-3 border border-gray-300 rounded cursor-pointer hover:border-black transition-colors bg-gray-50" id="label-origin-notisse">
                            <input type="radio" name="product_origin_type" value="notisse" id="origin-type-notisse" onchange="toggleProductOriginType('notisse')" checked class="text-black focus:ring-black">
                            <div>
                                <span class="font-space-grotesk font-bold text-xs uppercase block text-black">Notisse Original</span>
                                <span class="text-[10px] text-gray-500 font-inter block">Produk asli in-house label Notisse</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-300 rounded cursor-pointer hover:border-black transition-colors bg-gray-50" id="label-origin-collab">
                            <input type="radio" name="product_origin_type" value="collab" id="origin-type-collab" onchange="toggleProductOriginType('collab')" class="text-black focus:ring-black">
                            <div>
                                <span class="font-space-grotesk font-bold text-xs uppercase block text-black">Artist Collaboration</span>
                                <span class="text-[10px] text-gray-500 font-inter block">Karya rilis kolaborasi seniman</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Nama Produk *</label>
                        <input type="text" name="name" id="p-name" required placeholder="Contoh: Raja Diamuk Massa Tee V1" class="admin-input">
                    </div>
                    <div id="p-artist-container" class="opacity-50 pointer-events-none transition-opacity">
                        <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Seniman / Kolaborator</label>
                        <div class="relative">
                            <input list="artists-datalist" name="artist" id="p-artist" value="Notisse Original" placeholder="Pilih atau ketik nama seniman..." class="admin-input">
                            <datalist id="artists-datalist">
                                @foreach($artists as $art)
                                    <option value="{{ $art->name }}">
                                @endforeach
                            </datalist>
                        </div>
                        <p class="text-[10px] text-gray-400 font-inter mt-1" id="p-artist-hint">Produk in-house label resmi Notisse.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Harga Produk (IDR) *</label>
                    <input type="number" name="price" id="p-price" required placeholder="Contoh: 250000" class="admin-input">
                </div>

                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Spesifikasi Garmen & Fabric Details</label>
                    <input type="text" name="specs" id="p-specs" placeholder="Contoh: 100% Heavyweight 24s Cotton Combed (220 GSM) • Boxy Drop Shoulder Fit • High-Density Screenprint" class="admin-input">
                    <p class="text-[10px] text-gray-400 font-inter mt-1">Detail gramasi kain, model potongan, dan teknik sablon/jahitan.</p>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600">Deskripsi Editorial Produk</label>
                        <button type="button" onclick="setDefaultProductDescription()" class="text-[10px] font-space-grotesk font-bold uppercase text-brandRed hover:underline cursor-pointer">
                            + Gunakan Default Notisse Spec
                        </button>
                    </div>
                    <textarea name="description" id="p-desc" rows="4" placeholder="Cotton Combed 16s&#10;Raster Plastisol Screen Printing&#10;Digitized Painting" class="admin-input resize-none leading-relaxed font-mono text-xs"></textarea>
                    <p class="text-[10px] text-gray-400 font-inter mt-1">Format default 3 baris: Cotton Combed 16s, Raster Plastisol Screen Printing, Digitized Painting.</p>
                </div>
            </div>

            <!-- SECTION B: Structured Visual Media Uploads -->
            <div class="space-y-4 pt-2">
                <div>
                    <h4 class="text-xs font-bold font-space-grotesk uppercase tracking-wider text-black">2. Pengaturan Foto & Media Produk</h4>
                    <p class="text-[11px] text-gray-500 font-inter mt-0.5">Admin dapat mengupload foto spesifik untuk katalog, bagian belakang, model photoshoot, dan panduan size chart agar tidak tertukar.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- 1. Foto Depan / Katalog Utama -->
                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-space-grotesk font-bold uppercase tracking-wider text-black">Foto Depan (Katalog Utama)</label>
                            <span class="text-[9px] font-space-grotesk font-bold uppercase bg-black text-white px-1.5 py-0.5">Shop Grid</span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-inter leading-normal">Foto tampak depan dengan latar bersih untuk cover katalog toko.</p>
                        
                        <div class="admin-dropzone p-3.5 text-center group" onclick="document.getElementById('p-img-front').click()">
                            <input type="file" name="image" id="p-img-front" accept="image/*" onchange="handleFileSelect(this,'preview-front','filename-front')" class="hidden">
                            <div class="flex flex-col items-center justify-center gap-1.5 py-1">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih / Drag Foto Depan</span>
                                <span class="text-[9px] text-gray-400 font-inter">PNG, JPG, WEBP maks 8MB</span>
                            </div>
                            <span id="filename-front" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                            <img id="preview-front" class="w-full h-28 object-cover border border-gray-200 hidden mt-2 rounded-sm" src="" alt="Front Preview">
                        </div>
                    </div>

                    <!-- 2. Foto Belakang Produk -->
                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-space-grotesk font-bold uppercase tracking-wider text-black">Foto Belakang (Back View)</label>
                            <span class="text-[9px] font-space-grotesk font-bold uppercase bg-neutral-700 text-white px-1.5 py-0.5">Product Detail</span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-inter leading-normal">Foto tampak belakang untuk memperlihatkan grafis/detail punggung.</p>
                        
                        <div class="admin-dropzone p-3.5 text-center group" onclick="document.getElementById('p-img-back').click()">
                            <input type="file" name="back_image" id="p-img-back" accept="image/*" onchange="handleFileSelect(this,'preview-back','filename-back')" class="hidden">
                            <div class="flex flex-col items-center justify-center gap-1.5 py-1">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih / Drag Foto Belakang</span>
                                <span class="text-[9px] text-gray-400 font-inter">PNG, JPG, WEBP maks 8MB</span>
                            </div>
                            <span id="filename-back" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                            <img id="preview-back" class="w-full h-28 object-cover border border-gray-200 hidden mt-2 rounded-sm" src="" alt="Back Preview">
                        </div>
                    </div>

                    <!-- 3. Foto On-Model Footage -->
                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-space-grotesk font-bold uppercase tracking-wider text-black">Foto On-Model Footage</label>
                            <span class="text-[9px] font-space-grotesk font-bold uppercase bg-neutral-700 text-white px-1.5 py-0.5">Photoshoot</span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-inter leading-normal">Foto produk saat dipakai model photoshoot / lookbook di halaman detail.</p>
                        
                        <div class="admin-dropzone p-3.5 text-center group" onclick="document.getElementById('p-img-footage').click()">
                            <input type="file" name="footage_image" id="p-img-footage" accept="image/*" onchange="handleFileSelect(this,'preview-footage','filename-footage')" class="hidden">
                            <div class="flex flex-col items-center justify-center gap-1.5 py-1">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih / Drag Foto Footage</span>
                                <span class="text-[9px] text-gray-400 font-inter">PNG, JPG, WEBP maks 8MB</span>
                            </div>
                            <span id="filename-footage" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                            <img id="preview-footage" class="w-full h-28 object-cover border border-gray-200 hidden mt-2 rounded-sm" src="" alt="Footage Preview">
                        </div>
                    </div>

                    <!-- 4. Foto Size Chart -->
                    <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-space-grotesk font-bold uppercase tracking-wider text-black">Foto Panduan Size Chart</label>
                            <span class="text-[9px] font-space-grotesk font-bold uppercase bg-neutral-700 text-white px-1.5 py-0.5">Size Guide</span>
                        </div>
                        <p class="text-[10px] text-gray-500 font-inter leading-normal">Tabel ukuran spesifik garmen ini yang tampil saat popup Size Guide diklik.</p>
                        
                        <div class="admin-dropzone p-3.5 text-center group" onclick="document.getElementById('p-img-sizechart').click()">
                            <input type="file" name="size_chart_image" id="p-img-sizechart" accept="image/*" onchange="handleFileSelect(this,'preview-sizechart','filename-sizechart')" class="hidden">
                            <div class="flex flex-col items-center justify-center gap-1.5 py-1">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih / Drag Foto Size Chart</span>
                                <span class="text-[9px] text-gray-400 font-inter">PNG, JPG, WEBP maks 8MB</span>
                            </div>
                            <span id="filename-sizechart" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                            <img id="preview-sizechart" class="w-full h-28 object-cover border border-gray-200 hidden mt-2 rounded-sm" src="" alt="Size Chart Preview">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION C: Stock per Size -->
            <div class="space-y-3 pt-2">
                <h4 class="text-xs font-bold font-space-grotesk uppercase tracking-wider text-black border-b border-gray-100 pb-1">3. Jumlah Stok Fisik per Ukuran</h4>
                <div class="grid grid-cols-4 gap-3">
                    @foreach(['S','M','L','XL'] as $sz)
                    <div class="bg-gray-50 border border-gray-200 p-2 text-center rounded-sm">
                        <span class="block text-[11px] font-space-grotesk font-bold text-gray-500 mb-1 text-center">{{ $sz }}</span>
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
                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 block">Artist Collaborations</span>
                <h3 id="modal-artist-title" class="font-space-grotesk font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Add Artist</h3>
            </div>
            <button onclick="closeArtistModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form id="artist-form" action="/admin/artists" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" id="artist-form-method" name="_method" value="POST" disabled>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Nama Seniman *</label>
                    <input type="text" name="name" id="artist-name" required placeholder="Contoh: Mustafa Alatas" class="admin-input">
                </div>
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Kota Asal / Domisili</label>
                    <input type="text" name="city" id="artist-city" placeholder="Contoh: Temanggung" class="admin-input">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Biografi & Profil Cerita Seniman</label>
                <textarea name="bio" id="artist-bio" rows="4" placeholder="Tuliskan latar belakang artistik, gaya melukis, dan narasi kolaborasi dengan Notisse..." class="admin-input resize-none leading-relaxed"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Foto Profil / Portrait Seniman</label>
                    <div class="admin-dropzone p-4 text-center group" onclick="document.getElementById('artist-photo-input').click()">
                        <input type="file" name="photo" id="artist-photo-input" accept="image/*"
                            onchange="handleFileSelect(this,'artist-photo-preview','filename-artist-photo')"
                            class="hidden">
                        <div class="flex flex-col items-center justify-center gap-1.5 py-1">
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Upload Portrait Seniman</span>
                            <span class="text-[9px] text-gray-400 font-inter">PNG, JPG, WEBP maks 8MB</span>
                        </div>
                        <span id="filename-artist-photo" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                        <img id="artist-photo-preview" class="mt-2 mx-auto w-28 h-28 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Preview">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Foto Karya Seni Unggulan (Preview)</label>
                    <div class="admin-dropzone p-4 text-center group" onclick="document.getElementById('artist-art-input').click()">
                        <input type="file" name="artwork_preview" id="artist-art-input" accept="image/*"
                            onchange="handleFileSelect(this,'artist-art-preview','filename-artist-art')"
                            class="hidden">
                        <div class="flex flex-col items-center justify-center gap-1.5 py-1">
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Upload Preview Karya</span>
                            <span class="text-[9px] text-gray-400 font-inter">PNG, JPG, WEBP maks 8MB</span>
                        </div>
                        <span id="filename-artist-art" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                        <img id="artist-art-preview" class="mt-2 mx-auto w-28 h-28 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Preview">
                    </div>
                </div>
            </div>

            <div class="bg-amber-50/70 border border-amber-200/80 rounded p-3 flex items-start gap-3">
                <input type="checkbox" name="is_newcomer" id="artist-is-newcomer" value="1" class="mt-0.5 rounded border-gray-300 text-black focus:ring-black cursor-pointer">
                <label for="artist-is-newcomer" class="text-xs text-gray-700 cursor-pointer">
                    <span class="font-bold text-gray-900 block font-space-grotesk uppercase tracking-wider text-[11px]">Jadikan Newcomer Utama (Homepage Feature)</span>
                    Jika dicentang, seniman ini akan menjadi sorotan utama seniman baru ("Newcomer") di homepage. Menandai seniman ini akan otomatis menonaktifkan status newcomer seniman lain.
                </label>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeArtistModal()" class="btn-outline cursor-pointer">Cancel</button>
                <button type="submit" class="btn-primary cursor-pointer">Save Artist</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL 3: ADD / EDIT ARTWORK (KARYA SENI FISIK)
══════════════════════════════════════════════════════════════ --}}
<div id="modal-artwork" class="modal-backdrop" onclick="closeArtworkModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <div>
                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 block">Karya Seni Fisik Seniman</span>
                <h3 id="modal-artwork-title" class="font-space-grotesk font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Tambah Artwork Fisik</h3>
            </div>
            <button onclick="closeArtworkModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form id="artwork-form" action="/admin/artworks" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" id="artwork-form-method" name="_method" value="POST" disabled>

            <!-- PILIH SENIMAN (WAJIB & JELAS) -->
            <div class="p-3 bg-neutral-50 border border-neutral-200 rounded-sm">
                <label class="block text-[11px] font-space-grotesk font-bold uppercase tracking-widest text-black mb-1.5">
                    Seniman Pemilik Karya *
                </label>
                <select name="artist_id" id="art-artist-id" required class="admin-select bg-white font-medium">
                    <option value="">-- Pilih Seniman Kolaborator --</option>
                    @foreach($artists as $artObj)
                        <option value="{{ $artObj->id }}">{{ $artObj->name }} &bull; {{ $artObj->city ?: 'Indonesia' }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-500 font-inter mt-1">Karya ini akan langsung terhubung ke profil seniman yang Anda pilih dan tampil di galeri storefront.</p>
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Judul Karya Seni (Title) *</label>
                <input type="text" name="title" id="art-title" required placeholder="Contoh: Mereka Ulang Ungkapan Indah Leila" class="admin-input">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Medium / Teknik</label>
                    <input type="text" name="medium" id="art-medium" placeholder="Contoh: Oil on canvas" class="admin-input">
                </div>
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Dimensi (Ukuran Kanvas)</label>
                    <input type="text" name="dimensions" id="art-dimensions" placeholder="Contoh: 60x60cm" class="admin-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Tahun Pembuatan</label>
                    <input type="text" name="year" id="art-year" placeholder="Contoh: 2024" class="admin-input">
                </div>
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Status Ketersediaan</label>
                    <input type="text" name="status" id="art-status" placeholder="Contoh: Available for Direct Offering" class="admin-input">
                </div>
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Est. Nilai / Harga (IDR)</label>
                    <input type="number" name="price" id="art-price" placeholder="Contoh: 15000000" class="admin-input">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Catatan Kuratorial & Deskripsi</label>
                <textarea name="description" id="art-desc" rows="3" placeholder="Tuliskan latar belakang karya seni, narasi visual, dan pesan filosofis seniman..." class="admin-input resize-none leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Foto Karya Seni High-Res (Physical Artwork)</label>
                <div class="admin-dropzone p-4 text-center group" onclick="document.getElementById('artwork-img-input').click()">
                    <input type="file" name="image" id="artwork-img-input" accept="image/*"
                        onchange="handleFileSelect(this,'artwork-img-preview','filename-artwork')"
                        class="hidden">
                    <div class="flex flex-col items-center justify-center gap-1.5 py-1">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih / Drag Foto Artwork</span>
                        <span class="text-[9px] text-gray-400 font-inter">Format PNG, JPG, WEBP maks 8MB</span>
                    </div>
                    <span id="filename-artwork" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                    <img id="artwork-img-preview" class="mt-2 mx-auto w-44 h-32 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Preview">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeArtworkModal()" class="btn-outline cursor-pointer">Batal</button>
                <button type="submit" class="btn-primary cursor-pointer">Simpan Artwork</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL 4: ADD / EDIT EDITORIAL (ARTIKEL / WAWANCARA SENIMAN)
══════════════════════════════════════════════════════════════ --}}
<div id="modal-editorial" class="modal-backdrop" onclick="closeEditorialModal()">
    <div class="modal-box max-w-2xl" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <div>
                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-widest text-brandRed block">Editorial & Curatorial Writing</span>
                <h3 id="modal-editorial-title" class="font-space-grotesk font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Tambah Artikel Editorial</h3>
            </div>
            <button onclick="closeEditorialModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form id="editorial-form" action="/admin/editorials" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" id="editorial-form-method" name="_method" value="POST" disabled>

            <!-- PILIH SENIMAN (WAJIB & JELAS) -->
            <div class="p-3 bg-red-50 border border-red-200 rounded-sm">
                <label class="block text-[11px] font-space-grotesk font-bold uppercase tracking-widest text-brandRed mb-1.5">
                    Seniman Terkait Artikel Ini *
                </label>
                <select name="artist_id" id="edit-artist-id" required class="admin-select bg-white font-medium">
                    <option value="">-- Pilih Seniman Yang Ditampilkan --</option>
                    @foreach($artists as $artObj)
                        <option value="{{ $artObj->id }}">{{ $artObj->name }} &bull; {{ $artObj->city ?: 'Indonesia' }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-500 font-inter mt-1">Artikel editorial ini akan muncul di tab "Editorial" pada profil seniman yang dipilih.</p>
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Judul Artikel Editorial *</label>
                <input type="text" name="title" id="edit-title" required placeholder="Contoh: Menafsir Kuasa dan Sosial dalam Lukisan Figuratif" class="admin-input">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Penulis / Kurator</label>
                    <input type="text" name="author" id="edit-author" placeholder="Contoh: Laksa Dawantara / Tim Notisse" class="admin-input">
                </div>
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Video File / URL (Opsional)</label>
                    <input type="text" name="video_url" id="edit-video" placeholder="Contoh: video/editorial.mp4 atau URL video" class="admin-input">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Ringkasan / Excerpt Singkat</label>
                <textarea name="excerpt" id="edit-excerpt" rows="2" placeholder="Ringkasan 1-2 kalimat pengantar artikel untuk preview kartu..." class="admin-input resize-none leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Isi Lengkap Tulisan Editorial</label>
                <textarea name="content" id="edit-content" rows="6" placeholder="Tuliskan esai lengkap, kutipan wawancara seniman, dan refleksi karya seni..." class="admin-input resize-none leading-relaxed font-inter"></textarea>
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Foto Sampul / Featured Article Image</label>
                <div class="admin-dropzone p-4 text-center group" onclick="document.getElementById('editorial-img-input').click()">
                    <input type="file" name="image" id="editorial-img-input" accept="image/*"
                        onchange="handleFileSelect(this,'editorial-img-preview','filename-editorial')"
                        class="hidden">
                    <div class="flex flex-col items-center justify-center gap-1.5 py-1">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih / Drag Foto Sampul</span>
                        <span class="text-[9px] text-gray-400 font-inter">Format PNG, JPG, WEBP maks 8MB</span>
                    </div>
                    <span id="filename-editorial" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                    <img id="editorial-img-preview" class="mt-2 mx-auto w-44 h-32 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Preview">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeEditorialModal()" class="btn-outline cursor-pointer">Batal</button>
                <button type="submit" class="btn-primary bg-brandRed hover:bg-black cursor-pointer">Simpan Artikel</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL 5: EDIT SITE MEDIA (With Product Link Option)
══════════════════════════════════════════════════════════════ --}}
<div id="modal-media" class="modal-backdrop" onclick="closeMediaModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <h3 class="font-space-grotesk font-bold text-xl uppercase tracking-[0.15em] text-gray-900">Ganti Gambar</h3>
            <button onclick="closeMediaModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form id="media-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Title / Keterangan</label>
                <input type="text" name="title" id="m-title" class="admin-input">
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Tautkan ke Produk (Opsional)</label>
                <select name="product_id" id="m-product-id" class="admin-select">
                    <option value="">-- Tidak Terhubung ke Produk --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}">{{ $prod->name }} (#{{ $prod->id }})</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-400 mt-1 font-inter">Pilih produk jika foto footage ini merupakan foto katalog garmen tertentu.</p>
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Urutan Tampil (Order)</label>
                <input type="number" name="order" id="m-order" min="1" class="admin-input">
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Upload Gambar Baru</label>
                <div class="admin-dropzone p-4 text-center group" onclick="document.getElementById('media-img-input').click()">
                    <input type="file" name="image" id="media-img-input" accept="image/*"
                        onchange="handleFileSelect(this,'media-img-preview','filename-media')"
                        class="hidden">
                    <div class="flex flex-col items-center justify-center gap-1.5 py-1">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih / Drag Gambar Baru</span>
                        <span class="text-[9px] text-gray-400 font-inter">Biarkan kosong jika tidak ingin mengganti</span>
                    </div>
                    <span id="filename-media" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                    <img id="media-img-preview" class="mt-2 mx-auto w-full max-h-48 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Preview">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeMediaModal()" class="btn-outline cursor-pointer">Cancel</button>
                <button type="submit" class="btn-primary cursor-pointer">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL: TAMBAH SLIDE HERO CAROUSEL
══════════════════════════════════════════════════════════════ --}}
<div id="modal-create-carousel" class="modal-backdrop" onclick="closeCreateCarouselModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <div>
                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 block">Site Media &bull; Hero Carousel</span>
                <h3 class="font-space-grotesk font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Tambah Slide Carousel Baru</h3>
            </div>
            <button onclick="closeCreateCarouselModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form action="/admin/media" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="section" value="carousel">

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Pilih File Gambar Slide *</label>
                <div class="admin-dropzone p-5 text-center group" onclick="document.getElementById('create-carousel-img-input').click()">
                    <input type="file" name="image" id="create-carousel-img-input" required accept="image/*"
                        onchange="handleFileSelect(this,'create-carousel-img-preview','filename-carousel-create')"
                        class="hidden">
                    <div class="flex flex-col items-center justify-center gap-1.5 py-1">
                        <svg class="w-7 h-7 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        <span class="text-xs font-space-grotesk font-bold uppercase tracking-wider text-gray-800 group-hover:text-black">Upload Slide Hero (16:9 Landscape)</span>
                        <span class="text-[10px] text-gray-400 font-inter">Klik atau seret file gambar resolusi tinggi ke sini</span>
                    </div>
                    <span id="filename-carousel-create" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                    <img id="create-carousel-img-preview" class="mt-2 mx-auto w-full max-h-48 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Preview">
                </div>
                <p class="text-[10px] text-gray-400 mt-1 font-inter">Disarankan gambar beresolusi tinggi (rasio 16:9 atau landscape, misal 1920x1080 px).</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Judul / Keterangan (Opsional)</label>
                    <input type="text" name="title" placeholder="Contoh: Winter Campaign 2026" class="admin-input">
                </div>
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-500 mb-1.5">Urutan Tampil (Order)</label>
                    <input type="number" name="order" value="{{ ($carousels->max('order') ?? 0) + 1 }}" min="1" class="admin-input">
                    <p class="text-[10px] text-gray-400 mt-1 font-inter">Nomor urutan rotasi slideshow.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeCreateCarouselModal()" class="btn-outline cursor-pointer">Batal</button>
                <button type="submit" class="btn-primary cursor-pointer">Simpan Slide</button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     MODAL 6: CREATE & MANAGE LOOKBOOKS
══════════════════════════════════════════════════════════════ --}}
<!-- Modal Create Lookbook -->
<div id="modal-create-lookbook" class="modal-backdrop" onclick="closeCreateLookbookModal()">
    <div class="modal-box max-w-lg" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <div>
                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 block">Lookbook Collections</span>
                <h3 class="font-space-grotesk font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Buat Album Lookbook Baru</h3>
            </div>
            <button onclick="closeCreateLookbookModal()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <form action="/admin/lookbooks" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Judul Lookbook *</label>
                <input type="text" name="title" required placeholder="Contoh: 2025 LOOKBOOK atau SUMMER 2025" class="admin-input">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Tahun Kampanye</label>
                    <input type="text" name="year" value="{{ date('Y') }}" placeholder="2025" class="admin-input">
                </div>
                <div>
                    <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Cover Image (Opsional)</label>
                    <div class="admin-dropzone p-3 text-center group" onclick="document.getElementById('lb-cover-input').click()">
                        <input type="file" name="cover_image" id="lb-cover-input" accept="image/*" onchange="handleFileSelect(this,'lb-cover-preview','filename-lb-cover')" class="hidden">
                        <div class="flex items-center justify-center gap-1.5 py-0.5">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                            <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih Cover Image</span>
                        </div>
                        <span id="filename-lb-cover" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                        <img id="lb-cover-preview" class="mt-2 mx-auto w-full h-20 object-cover border border-gray-200 hidden rounded-sm" src="" alt="Cover Preview">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-space-grotesk font-semibold uppercase tracking-widest text-gray-600 mb-1.5">Deskripsi / Konsep Kampanye</label>
                <textarea name="description" rows="2" placeholder="Konsep visual, inspirasi koleksi lookbook..." class="admin-input resize-none"></textarea>
            </div>

            <!-- Upload Video Kampanye Section -->
            <div class="p-3.5 bg-neutral-50 border border-gray-200 rounded-sm space-y-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                    <span class="text-xs font-space-grotesk font-bold uppercase tracking-wider text-black">Video Kampanye Lookbook (Wide Header)</span>
                </div>
                <p class="text-[11px] text-gray-500 font-inter">Video ini akan tampil sebagai video player melebar di paling atas halaman Lookbook pengunjung.</p>
                <div>
                    <label class="block text-[10px] font-space-grotesk font-semibold uppercase tracking-wider text-gray-600 mb-1">Upload File Video (.mp4 / .mov)</label>
                    <div class="admin-dropzone p-3 text-center group" onclick="document.getElementById('lb-create-video-input').click()">
                        <input type="file" name="video" id="lb-create-video-input" accept="video/mp4,video/quicktime,video/webm" onchange="handleFileSelect(this,null,'filename-lb-create-video')" class="hidden">
                        <div class="flex items-center justify-center gap-1.5 py-0.5">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                            <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih File Video</span>
                        </div>
                        <span id="filename-lb-create-video" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-space-grotesk font-semibold uppercase tracking-wider text-gray-600 mb-1">Atau Path / Link Video</label>
                    <input type="text" name="video_url" placeholder="Contoh: video/editorial.mp4" class="admin-input text-xs">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeCreateLookbookModal()" class="btn-outline cursor-pointer">Batal</button>
                <button type="submit" class="btn-primary cursor-pointer">Buat Album Lookbook</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Manage Lookbook (Per-Album Content & Video) -->
<div id="modal-manage-lookbook" class="modal-backdrop" onclick="closeManageLookbook()">
    <div class="modal-box max-w-4xl" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-100">
            <div>
                <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-widest text-gray-400 block" id="manage-lb-year-badge">2025 LOOKBOOK</span>
                <h3 class="font-space-grotesk font-extrabold text-xl sm:text-2xl uppercase tracking-[0.12em] text-gray-900" id="manage-lb-title">Lookbook Manager</h3>
            </div>
            <button onclick="closeManageLookbook()" class="text-2xl leading-none text-gray-400 hover:text-black transition-colors cursor-pointer">&times;</button>
        </div>

        <div class="space-y-8 max-h-[75vh] overflow-y-auto pr-1">
            <!-- 1. VIDEO KAMPANYE SECTION -->
            <div class="border border-gray-200 bg-white p-5 rounded-sm shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                    <div>
                        <h4 class="font-space-grotesk font-bold text-sm uppercase tracking-wider text-black flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                            <span>Video Kampanye Lookbook (Tampil di Bagian Atas Melebar)</span>
                        </h4>
                        <p class="text-xs text-gray-500 font-inter mt-0.5">Video kampanye yang berputar di banner atas halaman Lookbook ini.</p>
                    </div>
                    <span id="manage-lb-video-status" class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider px-2.5 py-1 rounded bg-gray-100 text-gray-600 self-start sm:self-auto">
                        Status Video
                    </span>
                </div>

                <!-- Video Player Preview -->
                <div id="manage-lb-video-preview-box" class="w-full aspect-[21/9] bg-black overflow-hidden relative shadow-inner">
                    <video id="manage-lb-video-player" class="w-full h-full object-cover" controls playsinline>
                        <source id="manage-lb-video-source" src="" type="video/mp4">
                        Browser tidak mendukung video player.
                    </video>
                </div>

                <!-- Form Ganti / Upload Video -->
                <form id="manage-lb-video-form" action="" method="POST" enctype="multipart/form-data" class="space-y-3 pt-2">
                    @csrf
                    <input type="hidden" name="title" id="manage-lb-input-title" value="">
                    <input type="hidden" name="year" id="manage-lb-input-year" value="">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-space-grotesk font-semibold uppercase tracking-wider text-gray-600 mb-1">Upload File Video Baru (.mp4)</label>
                            <div class="admin-dropzone p-3 text-center group" onclick="document.getElementById('manage-lb-video-file').click()">
                                <input type="file" name="video" id="manage-lb-video-file" accept="video/mp4,video/quicktime,video/webm" onchange="handleFileSelect(this,null,'filename-manage-lb-video')" class="hidden">
                                <div class="flex items-center justify-center gap-1.5 py-0.5">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                                    <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih Video Baru</span>
                                </div>
                                <span id="filename-manage-lb-video" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-space-grotesk font-semibold uppercase tracking-wider text-gray-600 mb-1">Atau Masukkan Path / URL Video</label>
                            <input type="text" name="video_url" id="manage-lb-input-videourl" placeholder="video/editorial.mp4" class="admin-input text-xs">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary text-xs py-2 px-4 cursor-pointer">
                            Simpan Perubahan Video
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. UPLOAD & LIST FOTO PHOTOSHOOT UNTUK LOOKBOOK INI -->
            <div class="border border-gray-200 bg-white p-5 rounded-sm shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3">
                    <div>
                        <h4 class="font-space-grotesk font-bold text-sm uppercase tracking-wider text-black flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                            <span>Foto-Foto Photoshoot di Album Ini</span>
                        </h4>
                        <p class="text-xs text-gray-500 font-inter mt-0.5">Unggah foto-foto photoshoot eksklusif yang menjadi isi dari album Lookbook ini.</p>
                    </div>
                </div>

                <!-- Form Upload Foto Baru ke Lookbook ini -->
                <form id="manage-lb-photo-form" action="" method="POST" enctype="multipart/form-data" class="p-4 bg-neutral-50 border border-gray-200 space-y-3">
                    @csrf
                    <span class="text-[11px] font-space-grotesk font-bold uppercase tracking-wider text-black block">+ Upload Foto Photoshoot ke Lookbook Ini</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                        <div>
                            <label class="block text-[10px] font-space-grotesk font-semibold uppercase tracking-wider text-gray-600 mb-1">Pilih File Foto *</label>
                            <div class="admin-dropzone p-3 text-center group" onclick="document.getElementById('manage-lb-photo-input').click()">
                                <input type="file" name="image" id="manage-lb-photo-input" required accept="image/*" onchange="handleFileSelect(this,null,'filename-manage-lb-photo')" class="hidden">
                                <div class="flex items-center justify-center gap-1.5 py-0.5">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                    <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-wider text-gray-700 group-hover:text-black">Pilih Foto Photoshoot</span>
                                </div>
                                <span id="filename-manage-lb-photo" class="text-[9px] font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded hidden truncate max-w-full block my-1"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-space-grotesk font-semibold uppercase tracking-wider text-gray-600 mb-1">Judul / Tema Foto (Opsional)</label>
                            <input type="text" name="title" placeholder="Contoh: Look 01 - Leather Jacket" class="admin-input text-xs">
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="btn-primary text-xs py-2 px-5 cursor-pointer">
                            Upload Foto ke Album Ini
                        </button>
                    </div>
                </form>

                <!-- List Foto yang sudah ada di Lookbook ini -->
                <div>
                    <h5 class="text-xs font-space-grotesk font-bold uppercase tracking-wider text-gray-600 mb-3">
                        Daftar Foto Terunggah (<span id="manage-lb-photo-count">0</span> foto):
                    </h5>
                    <div id="manage-lb-photos-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 mt-4 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeManageLookbook()" class="btn-outline cursor-pointer">Tutup</button>
        </div>
    </div>
</div>

{{-- ── MODAL: DISCOUNT CODE (CREATE / EDIT) ──────────────────────── --}}
<div id="modal-discount" class="modal-backdrop" onclick="closeDiscountModal()">
    <div class="modal-box max-w-xl" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modal-discount-title" class="font-space-grotesk font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Tambah Kode Diskon</h3>
            <button onclick="closeDiscountModal()" class="modal-close-btn">&times;</button>
        </div>

        <form id="form-discount" action="/admin/discounts" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="discount-form-method" value="POST">

            <div>
                <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Kode Kupon / Promo *</label>
                <input type="text" name="code" id="discount-code" required placeholder="Contoh: FLASH10, MINGGUHEMAT" class="admin-input uppercase font-mono tracking-wider font-bold">
            </div>

            <div>
                <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Deskripsi Singkat</label>
                <input type="text" name="description" id="discount-description" placeholder="Contoh: Diskon akhir pekan untuk semua apparel" class="admin-input">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Tipe Diskon *</label>
                    <select name="type" id="discount-type" class="admin-input" onchange="handleDiscountTypeChange(this.value)">
                        <option value="percentage">Persentase (%)</option>
                        <option value="fixed">Potongan Tetap (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5" id="discount-value-label">Nilai Diskon (%) *</label>
                    <input type="number" step="any" min="0" name="value" id="discount-value" required placeholder="Contoh: 15" class="admin-input font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Min. Belanja Subtotal (Rp)</label>
                    <input type="number" min="0" name="min_order_amount" id="discount-min-order" placeholder="0 (Tanpa minimum)" class="admin-input font-mono">
                </div>
                <div id="discount-max-cap-container">
                    <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Maks. Potongan (Rp)</label>
                    <input type="number" min="0" name="max_discount_amount" id="discount-max-discount" placeholder="Kosongkan jika tanpa batas" class="admin-input font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Batas Kuota</label>
                    <input type="number" min="1" name="usage_limit" id="discount-usage-limit" placeholder="Kosong = Bebas" class="admin-input font-mono">
                </div>
                <div>
                    <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Mulai Tanggal</label>
                    <input type="date" name="start_date" id="discount-start-date" class="admin-input text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="discount-end-date" class="admin-input text-xs font-mono">
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="discount-is-active" value="1" checked class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black">
                <label for="discount-is-active" class="text-xs font-space-grotesk font-bold uppercase tracking-wider text-gray-800 cursor-pointer">Aktifkan Kode Diskon Ini Sekarang</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeDiscountModal()" class="btn-outline px-4 py-2 text-xs">Batal</button>
                <button type="submit" id="discount-submit-btn" class="btn-primary">Simpan Kode Diskon</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL: ADMIN MANAGEMENT (CREATE / EDIT) ────────────────────── --}}
<div id="modal-admin" class="modal-backdrop" onclick="closeAdminModal()">
    <div class="modal-box max-w-lg" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modal-admin-title" class="font-space-grotesk font-extrabold text-xl uppercase tracking-[0.15em] text-gray-900">Tambah Admin Baru</h3>
            <button onclick="closeAdminModal()" class="modal-close-btn">&times;</button>
        </div>

        <form id="form-admin" action="/admin/admins" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="admin-form-method" value="POST">

            <div>
                <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" id="admin-name" required placeholder="Contoh: Budi Santoso" class="admin-input">
            </div>

            <div>
                <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Alamat Email *</label>
                <input type="email" name="email" id="admin-email" required placeholder="admin@notisse.com" class="admin-input font-mono">
            </div>

            <div>
                <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" id="admin-phone" placeholder="Contoh: 08123456789" class="admin-input font-mono">
            </div>

            <div>
                <label class="block text-xs font-space-grotesk font-bold uppercase tracking-wider text-black mb-1.5">
                    <span id="admin-password-label">Password *</span>
                </label>
                <input type="password" name="password" id="admin-password" placeholder="Minimal 6 karakter" class="admin-input font-mono">
                <p id="admin-password-hint" class="text-[11px] text-gray-400 font-inter mt-1 hidden">Kosongkan kolom password jika tidak ingin mengubah password akun admin ini.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAdminModal()" class="btn-outline px-4 py-2 text-xs">Batal</button>
                <button type="submit" id="admin-submit-btn" class="btn-primary">Simpan Admin</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL CONFIRM (Custom Luxury Confirmation Modal - No Native OS Dialogs)
══════════════════════════════════════════════════════════════ --}}
<div id="modal-confirm" class="modal-backdrop" onclick="closeConfirmModal()">
    <div class="modal-box max-w-md p-6 text-center shadow-2xl" onclick="event.stopPropagation()">
        <!-- Danger Warning Icon -->
        <div class="mx-auto w-12 h-12 rounded-full bg-red-50 border border-red-100 flex items-center justify-center mb-4 text-red-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 id="confirm-modal-title" class="font-space-grotesk font-extrabold text-lg uppercase tracking-wider text-gray-900 mb-2">
            Konfirmasi Penghapusan
        </h3>
        <p id="confirm-modal-message" class="text-xs text-gray-600 font-inter leading-relaxed mb-6">
            Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex items-center justify-center gap-3">
            <button type="button" onclick="closeConfirmModal()" class="btn-outline px-5 py-2.5 text-xs">
                Batal
            </button>
            <button type="button" id="confirm-modal-btn" onclick="executeConfirmedAction()" class="bg-red-600 hover:bg-red-700 text-white font-space-grotesk font-bold uppercase tracking-wider px-5 py-2.5 text-xs transition-colors rounded-xs shadow-sm cursor-pointer">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════════════════ --}}
<script>
    const adminLookbooksData = @json($lookbooks);

    // ── Section switcher ────────────────────────────────────────
    function showSection(name) {
        const sections = ['products', 'artists', 'artworks', 'media', 'orders', 'offerings', 'discounts', 'admins', 'settings'];
        const titles = {
            'products':  'Products Catalog Management',
            'artists':   'Artists & Collaborators Management',
            'artworks':  'Artworks & Artist Editorial Management',
            'media':     'Site Visual Media (Hero & Lookbook)',
            'orders':    'Customer Orders & Transactions',
            'offerings': 'Artwork Offerings Management',
            'discounts': 'Discount & Promo Codes Management',
            'admins':    'Admin & Staff Management',
            'settings':  'Site Settings & Global Branding'
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
        ['carousel','footage','lookbook'].forEach(t => {
            const pane = document.getElementById('media-tab-' + t);
            const btn  = document.getElementById('tab-' + t);
            if (pane) pane.classList.toggle('hidden', t !== tab);
            if (btn)  btn.classList.toggle('active', t === tab);
        });
    }

    // ── Lookbook modal helpers ──────────────────────────────────
    function openCreateLookbookModal() {
        document.getElementById('modal-create-lookbook').classList.add('open');
    }

    function closeCreateLookbookModal() {
        document.getElementById('modal-create-lookbook').classList.remove('open');
    }

    function openManageLookbook(id) {
        const lb = (adminLookbooksData || []).find(l => l.id == id);
        if (!lb) return;

        // Populate basic headers
        document.getElementById('manage-lb-title').innerText = lb.title;
        document.getElementById('manage-lb-year-badge').innerText = (lb.year || '2025') + ' LOOKBOOK';
        document.getElementById('manage-lb-input-title').value = lb.title;
        document.getElementById('manage-lb-input-year').value = lb.year || '2025';
        document.getElementById('manage-lb-input-videourl').value = lb.video_url || '';

        // Form actions
        document.getElementById('manage-lb-video-form').action = '/admin/lookbooks/' + lb.id;
        document.getElementById('manage-lb-photo-form').action = '/admin/lookbooks/' + lb.id + '/photos';

        // Video Player
        const videoPlayer = document.getElementById('manage-lb-video-player');
        const videoSource = document.getElementById('manage-lb-video-source');
        const statusBadge = document.getElementById('manage-lb-video-status');

        if (lb.video_url) {
            statusBadge.innerText = 'Video Aktif';
            statusBadge.className = 'text-[10px] font-space-grotesk font-bold uppercase tracking-wider px-2.5 py-1 rounded bg-emerald-100 text-emerald-800';
            let vSrc = lb.video_url;
            if (!vSrc.startsWith('http') && !vSrc.startsWith('/')) {
                vSrc = vSrc.startsWith('video/') ? '/' + vSrc : '/storage/' + vSrc;
            }
            videoSource.src = vSrc;
            videoPlayer.load();
        } else {
            statusBadge.innerText = 'Belum Ada Video';
            statusBadge.className = 'text-[10px] font-space-grotesk font-bold uppercase tracking-wider px-2.5 py-1 rounded bg-gray-100 text-gray-600';
            videoSource.src = '';
            videoPlayer.load();
        }

        // Photos Grid
        const grid = document.getElementById('manage-lb-photos-grid');
        const countEl = document.getElementById('manage-lb-photo-count');
        const photos = lb.fotoshoots || [];
        countEl.innerText = photos.length;

        grid.innerHTML = '';
        if (photos.length === 0) {
            grid.innerHTML = '<div class="col-span-full py-8 text-center text-xs text-gray-400 font-inter bg-gray-50 border border-gray-100">Belum ada foto yang diunggah di album ini.</div>';
        } else {
            photos.forEach(p => {
                const pSrc = p.image.startsWith('http') || p.image.startsWith('/') ? p.image : (p.image.startsWith('lookbooks') ? '/storage/' + p.image : '/' + p.image);
                grid.innerHTML += `
                    <div class="bg-neutral-50 border border-gray-200 overflow-hidden flex flex-col justify-between group">
                        <div class="aspect-[3/4] bg-neutral-200 overflow-hidden relative">
                            <img src="${pSrc}" alt="${p.title || ''}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2 text-center">
                                <span class="text-white text-[10px] font-space-grotesk font-bold uppercase tracking-wider">${p.title || 'Photoshoot'}</span>
                            </div>
                        </div>
                        <div class="p-2 border-t border-gray-200 flex items-center justify-between bg-white">
                            <span class="text-[9px] text-gray-400 font-mono truncate max-w-[80px]">${p.title || '#'+p.id}</span>
                            <form action="/admin/lookbooks/photos/${p.id}" method="POST" onsubmit="event.preventDefault(); askConfirm('Hapus foto ini dari album?', this, 'Hapus Foto Lookbook');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete" style="font-size: 9px; padding: 2px 6px;">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                `;
            });
        }

        document.getElementById('modal-manage-lookbook').classList.add('open');
    }

    function closeManageLookbook() {
        const videoPlayer = document.getElementById('manage-lb-video-player');
        if (videoPlayer) videoPlayer.pause();
        document.getElementById('modal-manage-lookbook').classList.remove('open');
    }

    // ── Product Origin Switcher (Notisse Original vs Artist Collab) ─
    function toggleProductOriginType(type) {
        const isCollab = (type === 'collab');
        const container = document.getElementById('p-artist-container');
        const artistInput = document.getElementById('p-artist');
        const hint = document.getElementById('p-artist-hint');
        const radNotisse = document.getElementById('origin-type-notisse');
        const radCollab = document.getElementById('origin-type-collab');

        if (radNotisse) radNotisse.checked = !isCollab;
        if (radCollab) radCollab.checked = isCollab;

        if (isCollab) {
            if (container) {
                container.classList.remove('opacity-50', 'pointer-events-none');
            }
            if (artistInput) {
                if (artistInput.value === 'Notisse Original') artistInput.value = '';
                artistInput.placeholder = 'Pilih atau ketik nama seniman kolaborator...';
                artistInput.focus();
            }
            if (hint) hint.innerText = 'Pilih dari seniman yang terdaftar atau ketik nama baru.';
        } else {
            if (container) {
                container.classList.add('opacity-50', 'pointer-events-none');
            }
            if (artistInput) {
                artistInput.value = 'Notisse Original';
            }
            if (hint) hint.innerText = 'Produk in-house label resmi Notisse.';
        }
    }

    function setDefaultProductDescription() {
        const descEl = document.getElementById('p-desc');
        if (descEl) {
            descEl.value = "Cotton Combed 16s\nRaster Plastisol Screen Printing\nDigitized Painting";
        }
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

        toggleProductOriginType('notisse');
        setDefaultProductDescription();

        ['preview-front','preview-back','preview-footage','preview-sizechart'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { el.classList.add('hidden'); el.src = ''; }
        });
        ['filename-front','filename-back','filename-footage','filename-sizechart'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { el.classList.add('hidden'); el.textContent = ''; }
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
        document.getElementById('p-desc').value   = desc || "Cotton Combed 16s\nRaster Plastisol Screen Printing\nDigitized Painting";
        document.getElementById('p-specs').value  = specs || '';

        if (!artist || artist === 'Notisse Original') {
            toggleProductOriginType('notisse');
        } else {
            toggleProductOriginType('collab');
            document.getElementById('p-artist').value = artist;
        }

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

    function openEditProductFromBtn(btn) {
        try {
            const p = JSON.parse(btn.getAttribute('data-product'));
            openEditProductModal(p.id, p.name, p.price, p.desc, p.specs, p.artist, p.stocks, p.frontImg, p.backImg, p.footageImg, p.sizechartImg);
        } catch(e) {
            console.error("Gagal membuka modal edit produk:", e);
        }
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
        ['filename-artist-photo', 'filename-artist-art'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { el.classList.add('hidden'); el.textContent = ''; }
        });
        const newcomerCheckbox = document.getElementById('artist-is-newcomer');
        if (newcomerCheckbox) newcomerCheckbox.checked = false;
        document.getElementById('modal-artist').classList.add('open');
    }

    function openEditArtistModal(id, name, city, bio, photoUrl, artUrl, isNewcomer) {
        document.getElementById('modal-artist-title').textContent = 'Edit Artist (Edit Seniman)';
        document.getElementById('artist-form').action = '/admin/artists/' + id;
        document.getElementById('artist-form-method').value = 'PUT';
        document.getElementById('artist-form-method').disabled = false;

        document.getElementById('artist-name').value = name;
        document.getElementById('artist-city').value = city || '';
        document.getElementById('artist-bio').value  = bio || '';

        setPreviewImage('artist-photo-preview', photoUrl);
        setPreviewImage('artist-art-preview', artUrl);

        const newcomerCheckbox = document.getElementById('artist-is-newcomer');
        if (newcomerCheckbox) newcomerCheckbox.checked = !!isNewcomer;

        document.getElementById('modal-artist').classList.add('open');
    }

    function openEditArtistFromBtn(btn) {
        try {
            const a = JSON.parse(btn.getAttribute('data-artist'));
            openEditArtistModal(a.id, a.name, a.city, a.bio, a.photoUrl, a.artUrl, a.is_newcomer);
        } catch(e) {
            console.error("Gagal membuka modal edit seniman:", e);
        }
    }

    function closeArtistModal() {
        document.getElementById('modal-artist').classList.remove('open');
    }

    // ── Filter Creations (Artwork & Editorial per Seniman) ──────
    function filterCreations() {
        const artistFilter = document.getElementById('creation-filter-artist').value;
        const typeFilter   = document.getElementById('creation-filter-type').value;
        const items = document.querySelectorAll('#creations-grid .creation-item');
        let visibleCount = 0;

        items.forEach(item => {
            const itemArtist = item.getAttribute('data-artist-id');
            const itemType   = item.getAttribute('data-type');

            const matchArtist = (artistFilter === 'all' || itemArtist === artistFilter);
            const matchType   = (typeFilter === 'all' || itemType === typeFilter);

            if (matchArtist && matchType) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        const emptyState = document.getElementById('creations-empty-state');
        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount > 0);
        }

        const countDisplay = document.getElementById('creation-count-display');
        if (countDisplay) {
            countDisplay.innerText = visibleCount;
        }
    }

    function viewArtistCreations(artistId) {
        showSection('artworks');
        const select = document.getElementById('creation-filter-artist');
        if (select) {
            select.value = String(artistId);
            filterCreations();
        }
    }

    // ── Artwork modal ───────────────────────────────────────────
    function openAddArtworkModal(artistId = null) {
        document.getElementById('modal-artwork-title').textContent = 'Tambah Artwork Fisik';
        document.getElementById('artwork-form').action = '/admin/artworks';
        document.getElementById('artwork-form').reset();
        document.getElementById('artwork-form-method').disabled = true;

        if (artistId) {
            document.getElementById('art-artist-id').value = String(artistId);
        }

        document.getElementById('artwork-img-preview').classList.add('hidden');
        const fArt = document.getElementById('filename-artwork');
        if (fArt) { fArt.classList.add('hidden'); fArt.textContent = ''; }
        document.getElementById('modal-artwork').classList.add('open');
    }

    function openEditArtworkModal(id, artistId, title, medium, dimensions, year, status, price, desc) {
        document.getElementById('modal-artwork-title').textContent = 'Edit Artwork Fisik';
        document.getElementById('artwork-form').action = '/admin/artworks/' + id;
        document.getElementById('artwork-form-method').value = 'PUT';
        document.getElementById('artwork-form-method').disabled = false;

        document.getElementById('art-artist-id').value = artistId || '';
        document.getElementById('art-title').value      = title || '';
        document.getElementById('art-medium').value     = medium || '';
        document.getElementById('art-dimensions').value = dimensions || '';
        document.getElementById('art-year').value       = year || '';
        document.getElementById('art-status').value     = status || '';
        document.getElementById('art-price').value      = price || '';
        document.getElementById('art-desc').value       = desc || '';
        document.getElementById('artwork-img-preview').classList.add('hidden');

        document.getElementById('modal-artwork').classList.add('open');
    }

    function openEditArtworkFromBtn(btn) {
        try {
            const art = JSON.parse(btn.getAttribute('data-artwork'));
            openEditArtworkModal(art.id, art.artist_id, art.title, art.medium, art.dimensions, art.year, art.status, art.price, art.desc);
        } catch(e) {
            console.error("Gagal membuka modal edit artwork:", e);
        }
    }

    function closeArtworkModal() {
        document.getElementById('modal-artwork').classList.remove('open');
    }

    // ── Editorial modal ──────────────────────────────────────────
    function openAddEditorialModal(artistId = null) {
        document.getElementById('modal-editorial-title').textContent = 'Tambah Artikel Editorial';
        document.getElementById('editorial-form').action = '/admin/editorials';
        document.getElementById('editorial-form').reset();
        document.getElementById('editorial-form-method').disabled = true;

        if (artistId) {
            document.getElementById('edit-artist-id').value = String(artistId);
        }

        document.getElementById('editorial-img-preview').classList.add('hidden');
        const fEd = document.getElementById('filename-editorial');
        if (fEd) { fEd.classList.add('hidden'); fEd.textContent = ''; }
        document.getElementById('modal-editorial').classList.add('open');
    }

    function openEditEditorialModal(id, artistId, title, author, excerpt, videoUrl, content) {
        document.getElementById('modal-editorial-title').textContent = 'Edit Artikel Editorial';
        document.getElementById('editorial-form').action = '/admin/editorials/' + id;
        document.getElementById('editorial-form-method').value = 'PUT';
        document.getElementById('editorial-form-method').disabled = false;

        document.getElementById('edit-artist-id').value = artistId || '';
        document.getElementById('edit-title').value     = title || '';
        document.getElementById('edit-author').value    = author || '';
        document.getElementById('edit-excerpt').value   = excerpt || '';
        document.getElementById('edit-video').value     = videoUrl || '';
        document.getElementById('edit-content').value   = content || '';
        document.getElementById('editorial-img-preview').classList.add('hidden');

        document.getElementById('modal-editorial').classList.add('open');
    }

    function openEditEditorialFromBtn(btn) {
        try {
            const ed = JSON.parse(btn.getAttribute('data-editorial'));
            openEditEditorialModal(ed.id, ed.artist_id, ed.title, ed.author, ed.excerpt, ed.video_url, ed.content);
        } catch(e) {
            console.error("Gagal membuka modal edit editorial:", e);
        }
    }

    function closeEditorialModal() {
        document.getElementById('modal-editorial').classList.remove('open');
    }

    // ── Media modal ─────────────────────────────────────────────
    function openMediaModal(id, title, desc, productId, order) {
        document.getElementById('media-form').action = '/admin/media/' + id;
        document.getElementById('m-title').value     = title || '';
        document.getElementById('m-product-id').value = productId || '';
        if (document.getElementById('m-order')) {
            document.getElementById('m-order').value = order || 1;
        }
        document.getElementById('media-img-preview').classList.add('hidden');
        document.getElementById('media-img-input').value = '';
        document.getElementById('modal-media').classList.add('open');
    }

    function closeMediaModal() {
        document.getElementById('modal-media').classList.remove('open');
    }

    // ── Create Carousel Modal ───────────────────────────────────
    function openCreateCarouselModal() {
        const preview = document.getElementById('create-carousel-img-preview');
        if (preview) {
            preview.classList.add('hidden');
            preview.src = '';
        }
        const fCc = document.getElementById('filename-carousel-create');
        if (fCc) { fCc.classList.add('hidden'); fCc.textContent = ''; }
        document.getElementById('modal-create-carousel').classList.add('open');
    }

    function closeCreateCarouselModal() {
        document.getElementById('modal-create-carousel').classList.remove('open');
    }

    // ── Universal Custom Confirmation Modal ───────────────────────
    let pendingActionForm = null;

    function askConfirm(message, formOrAction, title = 'Konfirmasi Penghapusan', btnText = 'Ya, Hapus') {
        pendingActionForm = formOrAction;
        const titleEl = document.getElementById('confirm-modal-title');
        const msgEl   = document.getElementById('confirm-modal-message');
        const btnEl   = document.getElementById('confirm-modal-btn');
        if (titleEl) titleEl.textContent = title;
        if (msgEl) msgEl.textContent = message;
        if (btnEl) btnEl.textContent = btnText;
        document.getElementById('modal-confirm').classList.add('open');
    }

    function executeConfirmedAction() {
        if (typeof pendingActionForm === 'function') {
            pendingActionForm();
        } else if (pendingActionForm && typeof pendingActionForm.submit === 'function') {
            pendingActionForm.submit();
        }
        closeConfirmModal();
    }

    function closeConfirmModal() {
        const modal = document.getElementById('modal-confirm');
        if (modal) modal.classList.remove('open');
        pendingActionForm = null;
    }

    // ── File Selection & Drag-and-Drop Handler ────────────────────
    function handleFileSelect(input, previewId, filenameId) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (filenameId) {
                const fEl = document.getElementById(filenameId);
                if (fEl) {
                    fEl.textContent = file.name;
                    fEl.classList.remove('hidden');
                }
            }
            if (previewId) {
                previewImage(input, previewId);
            }
        }
    }

    // ── Lookbook Modals ──────────────────────────────────────────
    function closeCreateLookbookModal() {
        document.getElementById('modal-create-lookbook').classList.remove('open');
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.admin-dropzone').forEach(dropzone => {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                }, false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                }, false);
            });
            dropzone.addEventListener('drop', e => {
                const dt = e.dataTransfer;
                const files = dt.files;
                const input = dropzone.querySelector('input[type="file"]');
                if (files.length && input) {
                    input.files = files;
                    const changeEvent = new Event('change', { bubbles: true });
                    input.dispatchEvent(changeEvent);
                }
            }, false);
        });
    });

    // ── Auto-dismiss toasts ───────────────────────────────────────
    const toast = document.getElementById('toast-success');
    if (toast) setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.5s'; setTimeout(() => toast.remove(), 500); }, 4000);
    const toastErr = document.getElementById('toast-error');
    if (toastErr) setTimeout(() => { toastErr.style.opacity = '0'; toastErr.style.transition = 'opacity 0.5s'; setTimeout(() => toastErr.remove(), 500); }, 5000);

    // ── Discount Code Modals ────────────────────────────────────
    function handleDiscountTypeChange(type) {
        const label = document.getElementById('discount-value-label');
        const capContainer = document.getElementById('discount-max-cap-container');
        if (type === 'percentage') {
            if (label) label.textContent = 'Nilai Diskon (%) *';
            if (capContainer) capContainer.classList.remove('hidden');
        } else {
            if (label) label.textContent = 'Nilai Diskon (Rp) *';
            if (capContainer) capContainer.classList.add('hidden');
        }
    }

    function openCreateDiscountModal() {
        const form = document.getElementById('form-discount');
        if (form) form.reset();
        document.getElementById('modal-discount-title').textContent = 'Tambah Kode Diskon';
        document.getElementById('discount-form-method').value = 'POST';
        form.action = '/admin/discounts';
        document.getElementById('discount-is-active').checked = true;
        handleDiscountTypeChange('percentage');
        document.getElementById('modal-discount').classList.add('open');
    }

    function openEditDiscountModal(discount) {
        const form = document.getElementById('form-discount');
        document.getElementById('modal-discount-title').textContent = 'Edit Kode Diskon';
        document.getElementById('discount-form-method').value = 'PUT';
        form.action = '/admin/discounts/' + discount.id;

        document.getElementById('discount-code').value = discount.code || '';
        document.getElementById('discount-description').value = discount.description || '';
        document.getElementById('discount-type').value = discount.type || 'percentage';
        document.getElementById('discount-value').value = discount.value || '';
        document.getElementById('discount-min-order').value = discount.min_order_amount || '';
        document.getElementById('discount-max-discount').value = discount.max_discount_amount || '';
        document.getElementById('discount-usage-limit').value = discount.usage_limit || '';

        if (discount.start_date) {
            document.getElementById('discount-start-date').value = discount.start_date.substring(0, 10);
        } else {
            document.getElementById('discount-start-date').value = '';
        }

        if (discount.end_date) {
            document.getElementById('discount-end-date').value = discount.end_date.substring(0, 10);
        } else {
            document.getElementById('discount-end-date').value = '';
        }

        document.getElementById('discount-is-active').checked = !!discount.is_active;

        handleDiscountTypeChange(discount.type || 'percentage');
        document.getElementById('modal-discount').classList.add('open');
    }

    function closeDiscountModal() {
        document.getElementById('modal-discount').classList.remove('open');
    }

    // ── Admin Modal Helpers ─────────────────────────────────────
    function openCreateAdminModal() {
        document.getElementById('modal-admin-title').innerText = 'Tambah Admin Baru';
        document.getElementById('admin-submit-btn').innerText = 'Simpan Admin';
        document.getElementById('admin-form-method').value = 'POST';

        const form = document.getElementById('form-admin');
        form.action = '/admin/admins';
        form.reset();

        const pwdInput = document.getElementById('admin-password');
        pwdInput.required = true;
        pwdInput.placeholder = 'Minimal 6 karakter';
        document.getElementById('admin-password-label').innerText = 'Password *';
        document.getElementById('admin-password-hint').classList.add('hidden');

        document.getElementById('modal-admin').classList.add('open');
    }

    function openEditAdminModal(admin) {
        document.getElementById('modal-admin-title').innerText = 'Edit Data Admin: ' + (admin.name || '');
        document.getElementById('admin-submit-btn').innerText = 'Perbarui Data Admin';
        document.getElementById('admin-form-method').value = 'PUT';

        const form = document.getElementById('form-admin');
        form.action = '/admin/admins/' + admin.id;

        document.getElementById('admin-name').value = admin.name || '';
        document.getElementById('admin-email').value = admin.email || '';
        document.getElementById('admin-phone').value = admin.phone || '';

        const pwdInput = document.getElementById('admin-password');
        pwdInput.required = false;
        pwdInput.value = '';
        pwdInput.placeholder = 'Kosongkan jika tidak diubah';
        document.getElementById('admin-password-label').innerText = 'Password Baru (Opsional)';
        document.getElementById('admin-password-hint').classList.remove('hidden');

        document.getElementById('modal-admin').classList.add('open');
    }

    function closeAdminModal() {
        document.getElementById('modal-admin').classList.remove('open');
    }

    // ── Check Hash on Load ────────────────────────────────────────
    window.addEventListener('load', () => {
        const hash = window.location.hash.replace('#section-', '').replace('#', '');
        if (hash && ['products', 'artists', 'artworks', 'media', 'orders', 'offerings', 'discounts', 'admins', 'settings'].includes(hash)) {
            showSection(hash);
        }
    });

    // ── Keyboard ESC to close modals ─────────────────────────────
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeConfirmModal();
            closeProductModal();
            closeArtistModal();
            closeArtworkModal();
            closeEditorialModal();
            closeMediaModal();
            closeCreateCarouselModal();
            closeCreateLookbookModal();
            closeManageLookbook();
            closeDiscountModal();
            closeAdminModal();
        }
    });
</script>

</body>
</html>
