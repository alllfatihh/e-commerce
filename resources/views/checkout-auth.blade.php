<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTISSE — Express Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'],
                        roboto: ['Roboto', 'sans-serif'],
                    },
                    colors: {
                        brandDark: '#111111',
                        brandAccent: '#d52c2b',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff;
            color: #111111;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .input-luxury {
            border: 1px solid #e5e7eb;
            background-color: #fafafa;
            border-radius: 2px;
            padding: 13px 16px;
            width: 100%;
            font-size: 13px;
            font-family: 'Roboto', sans-serif;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            outline: none;
            color: #111111;
        }
        .input-luxury:focus {
            border-color: #111111;
            background-color: #ffffff;
            box-shadow: 0 0 0 1px #111111;
        }
        .input-luxury::placeholder {
            color: #9ca3af;
            font-size: 12px;
        }

        .label-luxury {
            display: block;
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #4b5563;
            margin-bottom: 6px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #f9f9f9; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-white">

    <!-- TOP HEADER -->
    <header class="border-b border-gray-200 bg-white sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-2">
                <img src="{{ asset('logo.png') }}" alt="Notisse Logo" class="h-6 sm:h-7 object-contain">
            </a>

            <!-- Step Indicator -->
            <div class="hidden md:flex items-center space-x-3 text-xs font-montserrat uppercase tracking-wider">
                <a href="/?open_checkout=true" class="text-gray-400 hover:text-black transition-colors">01 Keranjang</a>
                <span class="text-gray-300">/</span>
                <span class="text-black font-bold border-b-2 border-black pb-0.5">02 Data & Alamat</span>
                <span class="text-gray-300">/</span>
                <span class="text-gray-400">03 Pembayaran Midtrans</span>
            </div>

            <!-- SSL Secure Indicator -->
            <div class="flex items-center space-x-1.5 text-xs text-gray-500 font-montserrat">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0110 0v4"></path>
                </svg>
                <span class="text-[11px] font-semibold tracking-wider uppercase text-gray-600">256-Bit SSL Encrypted</span>
            </div>
        </div>
    </header>

    <!-- MAIN TWO COLUMN CHECKOUT LAYOUT -->
    <div class="flex-1 flex flex-col lg:flex-row max-w-7xl w-full mx-auto">
        
        <!-- LEFT COLUMN: Forms -->
        <main class="w-full lg:w-7/12 px-4 sm:px-8 md:px-12 py-8 sm:py-12 border-b lg:border-b-0 lg:border-r border-gray-200 order-2 lg:order-1">
            <div class="max-w-xl mx-auto lg:mr-auto lg:ml-0">
                
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="/?open_checkout=true" class="inline-flex items-center space-x-2 text-xs font-montserrat font-bold text-gray-400 hover:text-black uppercase tracking-widest transition-colors group">
                        <span class="group-hover:-translate-x-1 transition-transform">&larr;</span>
                        <span>Kembali ke Keranjang</span>
                    </a>
                </div>

                <!-- Express Checkout Banner -->
                @if(!auth()->check())
                <div class="mb-8 p-5 bg-neutral-50 border border-neutral-200 rounded-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-montserrat font-bold tracking-[0.2em] uppercase text-gray-500">Express Social Checkout</span>
                        <span class="text-[10px] text-gray-400 font-roboto">1-Klik Terhubung</span>
                    </div>
                    <a href="/auth/google" class="w-full flex items-center justify-center space-x-3 bg-white hover:bg-neutral-100 border border-gray-300 py-3 px-4 transition-all shadow-sm group cursor-pointer">
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span class="font-montserrat font-bold text-xs uppercase tracking-wider text-gray-800 group-hover:text-black">Lanjutkan dengan Google</span>
                    </a>
                </div>

                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white px-4 text-gray-400 font-montserrat font-semibold tracking-widest text-[10px]">Atau Isi Formulir Pemesanan</span>
                    </div>
                </div>
                @else
                <div class="mb-8 p-4 bg-neutral-50 border border-black flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 bg-black text-white flex items-center justify-center font-bold text-xs font-montserrat">
                            {{ strtoupper(substr(auth()->user()->name ?? 'NT', 0, 2)) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-xs font-montserrat uppercase text-black">{{ auth()->user()->name }}</span>
                                <span class="text-[9px] font-bold font-montserrat tracking-widest uppercase bg-black text-white px-1.5 py-0.2">LOGGED IN</span>
                            </div>
                            <span class="text-xs text-gray-500 font-roboto">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    <button type="button" onclick="handleLogoutCheckout()" class="text-[11px] font-montserrat font-bold text-gray-500 hover:text-red-600 uppercase underline cursor-pointer">
                        Ganti Akun
                    </button>
                </div>
                @endif

                <!-- Notification Alert -->
                <div id="error-message" class="hidden bg-red-50 border-l-4 border-red-600 text-red-800 p-4 mb-6 text-xs font-roboto"></div>

                <!-- MAIN CHECKOUT FORM -->
                <form id="seamless-form" class="space-y-8">
                    <!-- SECTION 1: CONTACT -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                            <h2 class="text-sm font-bold font-montserrat uppercase tracking-[0.15em] text-black">1. Informasi Kontak</h2>
                            <span class="text-[11px] text-gray-400 font-roboto">* Wajib diisi</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label-luxury">Alamat Email *</label>
                                <input type="email" id="email" class="input-luxury" placeholder="nama@email.com"
                                       value="{{ auth()->user()->email ?? '' }}" required {{ auth()->check() ? 'readonly' : '' }}>
                            </div>
                            <div>
                                <label class="label-luxury">No. WhatsApp / Handphone *</label>
                                <input type="tel" id="phone" class="input-luxury" placeholder="Contoh: 081234567890"
                                       value="{{ auth()->user()->phone ?? '' }}" required>
                            </div>
                        </div>

                        @if(!auth()->check())
                        <div>
                            <label class="label-luxury">Buat Password Akun (Opsional / Min 6 Karakter) *</label>
                            <div class="relative">
                                <input type="password" id="password" class="input-luxury pr-10" placeholder="Password untuk melacak pesanan Anda" required minlength="6">
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black text-xs font-montserrat cursor-pointer">
                                    <span id="pw-toggle-text">LIHAT</span>
                                </button>
                            </div>
                            <p class="text-[11px] text-gray-400 font-roboto mt-1">Akun Notisse dibuat otomatis agar Anda bisa melacak status resi & pengiriman.</p>
                        </div>
                        @else
                        <input type="hidden" id="password" value="logged-in-client">
                        @endif
                    </div>

                    <!-- SECTION 2: SHIPPING DESTINATION -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                            <h2 class="text-sm font-bold font-montserrat uppercase tracking-[0.15em] text-black">2. Alamat Pengiriman (Biteship)</h2>
                            <span class="text-[11px] text-emerald-600 font-montserrat font-bold uppercase tracking-wider">Ekspedisi Terintegrasi</span>
                        </div>

                        <div>
                            <label class="label-luxury">Nama Penerima Lengkap *</label>
                            <input type="text" id="name" class="input-luxury" placeholder="Nama lengkap penerima paket"
                                   value="{{ auth()->user()->name ?? '' }}" required>
                        </div>

                        <div class="relative">
                            <label class="label-luxury">Cari Kecamatan / Kota Tujuan *</label>
                            <div class="relative">
                                <input type="text" id="area-search" class="input-luxury pl-9"
                                       placeholder="Ketik minimal 3 huruf nama kecamatan (contoh: Tebet, Sukajadi, Menteng)..." autocomplete="off">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span id="area-loading-spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-montserrat">
                                    Mencari...
                                </span>
                            </div>
                            <!-- Floating Dropdown -->
                            <ul id="area-results" class="absolute z-20 w-full bg-white border border-gray-300 shadow-2xl mt-1 hidden max-h-56 overflow-y-auto"></ul>

                            <!-- Confirmed Selected Area Chip -->
                            <div id="confirmed-area-badge" class="hidden mt-2 p-2.5 bg-neutral-50 border border-gray-200 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                    <span class="text-xs font-montserrat font-semibold text-black uppercase" id="confirmed-area-text">Kecamatan Terpilih</span>
                                </div>
                                <span class="text-[10px] font-montserrat font-bold text-gray-500 uppercase">Terkonfirmasi</span>
                            </div>
                        </div>

                        <div>
                            <label class="label-luxury">Alamat Lengkap & Patokan Rumah *</label>
                            <textarea id="address" class="input-luxury h-24 resize-none leading-relaxed"
                                      placeholder="Nama Jalan, Blok / No. Rumah, RT/RW, Kelurahan, Patokan detail..." required>{{ auth()->user()->address ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- SECTION 3: SHIPPING OPTIONS -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                            <h2 class="text-sm font-bold font-montserrat uppercase tracking-[0.15em] text-black">3. Pilihan Pengiriman</h2>
                        </div>
                        <div id="shipping-options-container" class="space-y-3 mt-4">
                            <div id="shipping-loading" class="text-xs text-gray-500 font-roboto hidden">Memuat estimasi pengiriman...</div>
                            <div id="shipping-options" class="flex flex-col space-y-2 text-sm font-montserrat">
                                <span class="text-xs text-gray-400 font-roboto">Pilih kecamatan tujuan terlebih dahulu untuk melihat opsi pengiriman.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" id="area_id" value="{{ auth()->user()->area_id ?? '' }}" required>
                    <input type="hidden" id="area_name">

                    <!-- SUBMIT BUTTON -->
                    <div class="pt-2">
                        <button type="submit" id="submit-btn"
                                class="w-full bg-black text-white hover:bg-neutral-800 transition-all duration-200 py-4 px-6 font-montserrat text-xs sm:text-sm font-bold tracking-[0.2em] uppercase flex items-center justify-center space-x-3 shadow-md cursor-pointer group">
                            <span id="btn-text">LANJUT KE PENGIRIMAN & PEMBAYARAN</span>
                            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </button>
                        
                        <div class="mt-4 flex flex-col sm:flex-row items-center justify-between text-[11px] text-gray-400 font-roboto gap-2">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                <span>Informasi tersimpan aman sesuai protokol privasi Notisse.</span>
                            </span>
                            <span class="font-montserrat uppercase tracking-wider text-[10px] text-gray-500">Official Atelier</span>
                        </div>
                    </div>
                </form>

            </div>
        </main>

        <!-- RIGHT COLUMN: Order Summary -->
        <aside class="w-full lg:w-5/12 bg-[#fafafa] px-4 sm:px-8 md:px-10 py-8 sm:py-12 order-1 lg:order-2 border-b lg:border-b-0 border-gray-200">
            <div class="max-w-md mx-auto lg:ml-0 lg:sticky lg:top-24 space-y-6">
                
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <h3 class="text-sm font-bold font-montserrat uppercase tracking-[0.15em] text-black">Ringkasan Pesanan</h3>
                    <span id="summary-item-count" class="text-xs font-montserrat font-semibold text-gray-500 uppercase">0 Produk</span>
                </div>

                <!-- Product list -->
                <div id="checkout-summary-items" class="space-y-4 max-h-[380px] overflow-y-auto pr-1">
                    <!-- Populated by JS -->
                </div>

                <!-- Price calculation -->
                <div class="border-t border-gray-200 pt-5 space-y-3 font-roboto text-xs">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Belanja</span>
                        <span id="checkout-subtotal" class="font-medium text-black font-montserrat text-sm">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <div class="space-y-0.5">
                            <span>Ongkos Kirim (Biteship)</span>
                            <p class="text-[10px] text-gray-400">JNE, J&T, SiCepat, Anteraja, GoSend</p>
                        </div>
                        <span class="text-[11px] font-montserrat uppercase tracking-wider text-gray-500 italic">Dihitung Otomatis</span>
                    </div>
                </div>

                <!-- Grand Total -->
                <div class="border-t-2 border-black pt-4 flex justify-between items-baseline">
                    <div>
                        <span class="text-xs font-bold font-montserrat uppercase tracking-wider text-black block">Total Pembayaran</span>
                        <span class="text-[10px] text-gray-400 font-roboto">Termasuk PPN & Asuransi Pengiriman</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-roboto text-gray-400 mr-1">IDR</span>
                        <span id="checkout-total" class="text-xl sm:text-2xl font-bold font-montserrat text-black tracking-tight">Rp 0</span>
                    </div>
                </div>

                <!-- Trust Badges Section -->
                <div class="p-4 bg-white border border-gray-200 space-y-3 rounded-sm">
                    <div class="flex items-center space-x-3 text-xs text-gray-600">
                        <svg class="w-5 h-5 text-black shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span><strong>100% Produk Original & Terkurasi</strong> langsung dari atelier resmi Notisse.</span>
                    </div>
                    <div class="flex items-center space-x-3 text-xs text-gray-600">
                        <svg class="w-5 h-5 text-black shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span><strong>Payment Gateway Midtrans:</strong> QRIS, Virtual Account (BCA, Mandiri, BNI, BRI), Kartu Kredit.</span>
                    </div>
                </div>

            </div>
        </aside>

    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        let cart = [];
        const summaryContainer = document.getElementById('checkout-summary-items');
        let subtotal = 0;
        let totalCount = 0;
        let selectedShippingCost = 0;
        let selectedCourierName = "";
        let isFetchingShipping = false;

        async function initCart() {
            try {
                const res = await fetch('/api/cart');
                const data = await res.json();
                cart = data || [];
            } catch(e) {
                console.error("Gagal mengambil cart dari backend", e);
                cart = [];
            }
            renderCart();

            // Pre-fill existing user area if already saved
            const existingAreaId = document.getElementById('area_id').value;
            if (existingAreaId && cart.length > 0) {
                const confirmedBadge = document.getElementById('confirmed-area-badge');
                const confirmedText = document.getElementById('confirmed-area-text');
                confirmedBadge.classList.remove('hidden');
                confirmedText.innerText = 'Kecamatan Terdaftar (ID: ' + existingAreaId + ')';
                fetchShippingRates(existingAreaId);
            }
        }

        function renderCart() {
            subtotal = 0;
            totalCount = 0;
            
            // Render Cart Items in Summary
            if (cart.length === 0) {
                summaryContainer.innerHTML = `
                    <div class="p-6 bg-white border border-dashed border-gray-300 text-center space-y-3">
                        <p class="text-xs text-gray-500 font-roboto">Keranjang Anda masih kosong.</p>
                        <a href="/?open_checkout=true" class="inline-block text-xs font-montserrat font-bold uppercase underline tracking-wider text-black">
                            Pilih Produk di Toko
                        </a>
                    </div>
                `;
                document.getElementById('checkout-subtotal').innerText = 'Rp 0';
                document.getElementById('checkout-total').innerText = 'Rp 0';
            } else {
                summaryContainer.innerHTML = '';
                cart.forEach(item => {
                    const itemPrice = parseInt(item.price || item.unitPrice || 0);
                    const itemQty = parseInt(item.quantity || item.qty || 1);
                    subtotal += (itemPrice * itemQty);
                    totalCount += itemQty;

                    let imgUrl = item.product?.image || item.image || '';
                    if (imgUrl && !imgUrl.startsWith('http') && !imgUrl.startsWith('/')) {
                        imgUrl = '/' + imgUrl;
                    }

                    const formattedPrice = 'Rp ' + (itemPrice * itemQty).toLocaleString('id-ID');
                    const itemName = item.product?.name || item.name || 'Produk';
                    const itemSize = item.size || 'All Size';

                    summaryContainer.innerHTML += `
                        <div class="flex items-center space-x-3.5 bg-white p-3 border border-gray-200">
                            <div class="relative w-14 h-16 bg-neutral-100 border border-gray-200 shrink-0 overflow-hidden">
                                <img src="${imgUrl || '/footage-baju.jpg'}" alt="${itemName}" class="w-full h-full object-cover" onerror="this.src='/footage-baju.jpg'">
                                <span class="absolute top-0 right-0 bg-black text-white text-[9px] font-bold font-montserrat px-1.5 py-0.5 leading-none">x${itemQty}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-montserrat font-bold text-xs uppercase text-black truncate">${itemName}</h4>
                                <p class="text-[11px] text-gray-500 font-roboto mt-0.5">Size: <strong class="text-black font-montserrat">${itemSize}</strong></p>
                                <p class="text-xs font-montserrat font-bold text-black mt-1">${formattedPrice}</p>
                            </div>
                        </div>
                    `;
                });

                document.getElementById('summary-item-count').innerText = `${totalCount} Produk`;
                updateTotalDisplay();
            }
        }

        function updateTotalDisplay() {
            const formattedSubtotal = 'Rp ' + subtotal.toLocaleString('id-ID');
            const grandTotal = subtotal + selectedShippingCost;
            const formattedTotal = 'Rp ' + grandTotal.toLocaleString('id-ID');

            document.getElementById('checkout-subtotal').innerText = formattedSubtotal;
            document.getElementById('checkout-total').innerText = formattedTotal;
        }

        async function fetchShippingRates(areaId) {
            if (isFetchingShipping) return;
            isFetchingShipping = true;

            const shippingOptions = document.getElementById('shipping-options');
            const loadingIndicator = document.getElementById('shipping-loading');
            
            shippingOptions.innerHTML = '';
            loadingIndicator.classList.remove('hidden');

            const items = cart.map(item => ({
                id: item.product_id || item.id,
                name: item.product?.name || item.name || 'Produk',
                price: item.price || item.unitPrice,
                quantity: item.quantity || item.qty || 1,
                size: item.size || 'All Size'
            }));

            try {
                const res = await fetch('/shipping-rates', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        destination_area_id: areaId,
                        items: items
                    })
                });

                const data = await res.json();
                loadingIndicator.classList.add('hidden');

                if (data.pricing && data.pricing.length > 0) {
                    data.pricing.forEach((rate, index) => {
                        const name = rate.courier_name + ' ' + rate.courier_service_name;
                        const cost = rate.price;

                        const id = `courier-${index}`;
                        const div = document.createElement('div');
                        div.className = 'flex items-center justify-between p-3 border border-gray-200 cursor-pointer hover:bg-neutral-50 transition-colors';
                        
                        div.innerHTML = `
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="shipping_courier" id="${id}" value="${cost}" 
                                       class="w-4 h-4 text-black focus:ring-black border-gray-300"
                                       onchange="selectShipping('${name}', ${cost})">
                                <label for="${id}" class="cursor-pointer">
                                    <span class="block font-bold text-xs uppercase text-black">${name}</span>
                                </label>
                            </div>
                            <span class="font-bold text-xs">Rp ${cost.toLocaleString('id-ID')}</span>
                        `;
                        
                        // Click on div to select radio
                        div.addEventListener('click', (e) => {
                            if (e.target.tagName !== 'INPUT') {
                                const radio = div.querySelector('input');
                                radio.checked = true;
                                selectShipping(name, cost);
                            }
                        });

                        shippingOptions.appendChild(div);
                    });
                } else {
                    shippingOptions.innerHTML = '<span class="text-xs text-red-500">Kurir tidak tersedia untuk lokasi ini.</span>';
                }
            } catch (err) {
                loadingIndicator.classList.add('hidden');
                shippingOptions.innerHTML = '<span class="text-xs text-red-500">Gagal mengambil tarif pengiriman.</span>';
            }

            isFetchingShipping = false;
        }

        window.selectShipping = function(name, cost) {
            selectedCourierName = name;
            selectedShippingCost = parseInt(cost);
            updateTotalDisplay();
        };

        // Biteship Area Autocomplete Logic
        const areaSearchInput = document.getElementById('area-search');
        const areaResultsBox = document.getElementById('area-results');
        const areaLoadingSpinner = document.getElementById('area-loading-spinner');
        const confirmedBadge = document.getElementById('confirmed-area-badge');
        const confirmedText = document.getElementById('confirmed-area-text');
        let searchTimeout = null;

        // Pre-fill existing user area if already saved
        const existingAreaId = document.getElementById('area_id').value;
        if (existingAreaId) {
            confirmedBadge.classList.remove('hidden');
            confirmedText.innerText = 'Kecamatan Terdaftar (ID: ' + existingAreaId + ')';
            // fetchShippingRates is now called in initCart after cart items are loaded
        }

        areaSearchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const keyword = e.target.value.trim();

            if (keyword.length < 3) {
                areaResultsBox.classList.add('hidden');
                areaLoadingSpinner.classList.add('hidden');
                return;
            }

            areaLoadingSpinner.classList.remove('hidden');

            searchTimeout = setTimeout(() => {
                fetch(`/shipping-areas?keyword=${encodeURIComponent(keyword)}`)
                    .then(res => res.json())
                    .then(data => {
                        areaLoadingSpinner.classList.add('hidden');
                        areaResultsBox.innerHTML = '';
                        if (data.areas && data.areas.length > 0) {
                            data.areas.forEach(area => {
                                const li = document.createElement('li');
                                li.className = 'px-4 py-2.5 hover:bg-neutral-100 cursor-pointer text-xs font-roboto border-b border-gray-100 last:border-0 text-black flex justify-between items-center transition-colors';
                                li.innerHTML = `
                                    <span class="font-medium">${area.name}</span>
                                    <span class="text-[10px] text-gray-400 font-montserrat uppercase">PILIH &rarr;</span>
                                `;
                                li.onclick = () => {
                                    areaSearchInput.value = area.name;
                                    document.getElementById('area_id').value = area.id;
                                    document.getElementById('area_name').value = area.name;
                                    areaResultsBox.classList.add('hidden');
                                    
                                    confirmedBadge.classList.remove('hidden');
                                    confirmedText.innerText = area.name;
                                    
                                    // Fetch shipping rates for the new area
                                    fetchShippingRates(area.id);
                                };
                                areaResultsBox.appendChild(li);
                            });
                            areaResultsBox.classList.remove('hidden');
                        } else {
                            areaResultsBox.innerHTML = '<li class="px-4 py-3 text-xs text-gray-400 font-roboto text-center">Kecamatan tidak ditemukan. Coba ketik kata kunci lain.</li>';
                            areaResultsBox.classList.remove('hidden');
                        }
                    })
                    .catch(() => {
                        areaLoadingSpinner.classList.add('hidden');
                    });
            }, 400);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!areaSearchInput.contains(e.target) && !areaResultsBox.contains(e.target)) {
                areaResultsBox.classList.add('hidden');
            }
        });

        // Toggle Password Visibility
        function togglePasswordVisibility() {
            const pwInput = document.getElementById('password');
            const toggleText = document.getElementById('pw-toggle-text');
            if (pwInput.type === 'password') {
                pwInput.type = 'text';
                toggleText.innerText = 'SEMBUNYIKAN';
            } else {
                pwInput.type = 'password';
                toggleText.innerText = 'LIHAT';
            }
        }

        // Logout from Checkout
        async function handleLogoutCheckout() {
            try {
                await fetch('/logout', { method: 'POST', headers: { 'Content-Type': 'application/json' } });
                localStorage.removeItem('notisse_user');
                window.location.reload();
            } catch (e) {
                window.location.reload();
            }
        }

        // Submit Checkout Form
        document.getElementById('seamless-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const areaId = document.getElementById('area_id').value;
            const errorBox = document.getElementById('error-message');
            errorBox.classList.add('hidden');

            if (!areaId) {
                errorBox.innerHTML = '<strong>Pilih Kecamatan Pengiriman:</strong> Silakan ketik nama kecamatan pada kotak pencarian dan klik salah satu opsi dari daftar.';
                errorBox.classList.remove('hidden');
                areaSearchInput.focus();
                return;
            }

            if (!selectedCourierName) {
                errorBox.innerHTML = '<strong>Pilih Pengiriman:</strong> Silakan pilih salah satu opsi kurir yang tersedia.';
                errorBox.classList.remove('hidden');
                return;
            }

            const btn = document.getElementById('submit-btn');
            const originalText = document.getElementById('btn-text').innerText;
            document.getElementById('btn-text').innerText = 'MEMPROSES VERIFIKASI...';
            btn.disabled = true;
            btn.classList.add('opacity-75');

            const authPayload = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                area_id: areaId,
                _token: csrfToken
            };

            try {
                // 1. Authenticate or Register Seamlessly
                const authResponse = await fetch('/auth/seamless', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(authPayload)
                });

                const authData = await authResponse.json();

                if (authResponse.ok && authData.success) {
                    if (authData.user) {
                        localStorage.setItem('notisse_user', JSON.stringify(authData.user));
                    }
                    
                    // 2. Process Midtrans Checkout
                    document.getElementById('btn-text').innerText = 'MEMBUAT PESANAN...';
                    
                    const items = cart.map(item => ({
                        id: item.product_id || item.id,
                        name: item.product?.name || item.name || 'Produk',
                        price: item.price || item.unitPrice,
                        quantity: item.quantity || item.qty || 1,
                        size: item.size || 'All Size'
                    }));

                    const checkoutPayload = {
                        customer_name: authPayload.name,
                        customer_email: authPayload.email,
                        customer_phone: authPayload.phone,
                        shipping_address: authPayload.address, 
                        destination_area_id: authPayload.area_id,
                        items: items,
                        shipping_cost: selectedShippingCost,
                        courier_name: selectedCourierName,
                        _token: csrfToken
                    };

                    const checkoutResponse = await fetch('/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(checkoutPayload)
                    });

                    const checkoutData = await checkoutResponse.json();

                    if (checkoutResponse.status === 403 && checkoutData.pending_order) {
                        alert(checkoutData.error);
                        window.location.href = '/';
                        return;
                    }

                    if (checkoutData.snap_token) {
                        snap.pay(checkoutData.snap_token, {
                            onSuccess: async function(result) {
                                await fetch('/midtrans/local-success', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ order_id: result.order_id })
                                });
                                await fetch('/api/cart/clear', { method: 'POST' });
                                localStorage.removeItem('notisse_cart');
                                window.location.href = '/?order_success=true';
                            },
                            onPending: async function(result) {
                                await fetch('/midtrans/local-success', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ order_id: result.order_id })
                                });
                                await fetch('/api/cart/clear', { method: 'POST' });
                                localStorage.removeItem('notisse_cart');
                                window.location.href = '/?order_pending=true';
                            },
                            onError: function(result) {
                                alert("Payment failed!");
                                document.getElementById('btn-text').innerText = originalText;
                                btn.disabled = false;
                                btn.classList.remove('opacity-75');
                            },
                            onClose: function() {
                                document.getElementById('btn-text').innerText = originalText;
                                btn.disabled = false;
                                btn.classList.remove('opacity-75');
                            }
                        });
                    } else {
                        throw new Error(checkoutData.error || 'Gagal mengambil token Midtrans');
                    }

                } else {
                    errorBox.innerText = authData.message || 'Terjadi kesalahan sistem. Periksa kembali format isian Anda.';
                    errorBox.classList.remove('hidden');
                    document.getElementById('btn-text').innerText = originalText;
                    btn.disabled = false;
                    btn.classList.remove('opacity-75');
                }
            } catch (err) {
                errorBox.innerText = 'Koneksi ke server gagal. ' + err.message;
                errorBox.classList.remove('hidden');
                document.getElementById('btn-text').innerText = originalText;
                btn.disabled = false;
                btn.classList.remove('opacity-75');
            }
        });

        // Initialize cart on page load
        document.addEventListener('DOMContentLoaded', initCart);
    </script>
</body>
</html>
