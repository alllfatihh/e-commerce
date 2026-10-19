<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTISSE — Express Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Space+Grotesk:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background-color: #ffffff;
            color: #111111;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
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

        .input-luxury {
            border: 1px solid #e5e7eb;
            background-color: #fafafa;
            border-radius: 2px;
            padding: 13px 16px;
            width: 100%;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
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
            font-family: 'Space Grotesk', sans-serif;
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

        /* CUSTOM BRUSH BUTTON WITH ICON.PNG BACKGROUND */
        .btn-brush {
            font-family: 'Space Grotesk', sans-serif !important;
            background-image: url('{{ asset('icon.png') }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
            background-color: transparent;
            color: #ffffff !important;
            font-weight: 700;
            letter-spacing: 0.08em;
            padding: 14px 38px;
            min-height: 48px;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            user-select: none;
            cursor: pointer;
            border: none;
            outline: none;
        }

        .btn-brush:hover {
            transform: scale(1.03) rotate(-0.5deg);
            opacity: 0.95;
            color: #ffffff !important;
        }

        .btn-brush:active {
            transform: scale(0.96) rotate(0.4deg);
            color: #ffffff !important;
        }

        .btn-brush:disabled {
            cursor: not-allowed;
            transform: none !important;
            opacity: 0.65;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-white">

    <!-- TOP HEADER -->
    <header class="border-b border-gray-200 bg-white sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-4 relative flex items-center justify-between">
            <!-- Logo Notisse (Left) -->
            <div class="flex items-center z-10">
                <a href="/" class="flex items-center space-x-2">
                    <img src="{{ asset('logo.png') }}" alt="Notisse Logo" class="h-6 sm:h-7 object-contain">
                </a>
            </div>

            <!-- Step Indicator (Centered) -->
            <div class="hidden md:flex absolute inset-0 items-center justify-center pointer-events-none">
                <div class="pointer-events-auto flex items-center space-x-3 text-xs font-space-grotesk uppercase tracking-wider">
                    <a href="/?open_checkout=true" class="text-gray-400 hover:text-black transition-colors">01 Cart</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-black font-bold border-b-2 border-black pb-0.5">02 Information & Shipping</span>
                    <span class="text-gray-300">/</span>
                    <span class="text-gray-400">03 Payment</span>
                </div>
            </div>

            <!-- Right Placeholder for Symmetry -->
            <div class="w-16 hidden md:block"></div>
        </div>
    </header>

    <!-- MAIN TWO COLUMN CHECKOUT LAYOUT -->
    <div class="flex-1 flex flex-col lg:flex-row max-w-7xl w-full mx-auto">
        
        <!-- LEFT COLUMN: Forms -->
        <main class="w-full lg:w-7/12 px-4 sm:px-8 md:px-12 py-8 sm:py-12 border-b lg:border-b-0 lg:border-r border-gray-200 order-2 lg:order-1">
            <div class="max-w-xl mx-auto lg:mr-auto lg:ml-0">
                
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="/?open_checkout=true" class="inline-flex items-center space-x-2 text-xs font-space-grotesk font-bold text-gray-400 hover:text-black uppercase tracking-widest transition-colors group">
                        <span class="group-hover:-translate-x-1 transition-transform">&larr;</span>
                        <span>Back to Cart</span>
                    </a>
                </div>

                <!-- Express Checkout Banner -->
                @if(!auth()->check())
                <div class="mb-8 p-5 bg-neutral-50 border border-neutral-200 rounded-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-space-grotesk font-bold tracking-[0.2em] uppercase text-gray-500">Express Social Checkout</span>
                        <span class="text-[10px] text-gray-400 font-inter">1-Click Connect</span>
                    </div>
                    <a href="/auth/google?from=checkout" id="google-checkout-btn" onclick="window.location.href='/auth/google?from=checkout'" class="w-full flex items-center justify-center space-x-3 bg-white hover:bg-neutral-100 border border-gray-300 py-3 px-4 transition-all shadow-sm group cursor-pointer relative z-10">
                        <svg class="w-4 h-4 pointer-events-none" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span class="font-space-grotesk font-bold text-xs uppercase tracking-wider text-gray-800 group-hover:text-black pointer-events-none">Continue with Google</span>
                    </a>
                </div>

                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white px-4 text-gray-400 font-space-grotesk font-semibold tracking-widest text-[10px]">Or Fill In Order Details</span>
                    </div>
                </div>
                @else
                <div class="mb-8 p-4 bg-neutral-50 border border-black flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        @if(auth()->check() && auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 object-cover border border-black shrink-0" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                            <div class="w-9 h-9 bg-black text-white hidden items-center justify-center font-bold text-xs font-space-grotesk shrink-0">
                                {{ strtoupper(substr(auth()->user()->name ?? 'NT', 0, 2)) }}
                            </div>
                        @else
                            <div class="w-9 h-9 bg-black text-white flex items-center justify-center font-bold text-xs font-space-grotesk shrink-0">
                                {{ strtoupper(substr(auth()->user()->name ?? 'NT', 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-xs font-space-grotesk uppercase text-black">{{ auth()->user()->name }}</span>
                                <span class="text-[9px] font-bold font-space-grotesk tracking-widest uppercase bg-black text-white px-1.5 py-0.2">LOGGED IN</span>
                            </div>
                            <span class="text-xs text-gray-500 font-inter">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    <button type="button" onclick="handleLogoutCheckout()" class="text-[11px] font-space-grotesk font-bold text-gray-500 hover:text-red-600 uppercase underline cursor-pointer">
                        Switch Account
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-600 text-red-800 p-4 mb-6 text-xs font-inter font-medium">
                    {{ session('error') }}
                </div>
                @endif
                @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-600 text-emerald-800 p-4 mb-6 text-xs font-inter font-medium">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Notification Alert -->
                <div id="error-message" class="hidden bg-red-50 border-l-4 border-red-600 text-red-800 p-4 mb-6 text-xs font-inter"></div>

                <!-- MAIN CHECKOUT FORM -->
                <form id="seamless-form" class="space-y-8">
                    <!-- SECTION 1: CONTACT -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                            <h2 class="text-sm font-bold font-space-grotesk uppercase tracking-[0.15em] text-black">1. Contact Information</h2>
                            <span class="text-[11px] text-gray-400 font-inter">* Required</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label-luxury">Email Address *</label>
                                <input type="email" id="email" class="input-luxury" placeholder="name@email.com"
                                       value="{{ auth()->user()->email ?? '' }}" required {{ auth()->check() ? 'readonly' : '' }}>
                            </div>
                            <div>
                                <label class="label-luxury">WhatsApp / Phone Number *</label>
                                <input type="tel" id="phone" class="input-luxury" placeholder="e.g. 081234567890"
                                       value="{{ auth()->user()->phone ?? '' }}" required>
                            </div>
                        </div>

                        @if(!auth()->check())
                        <div>
                            <label class="label-luxury">Create Account Password (Min 6 Characters) *</label>
                            <div class="relative">
                                <input type="password" id="password" class="input-luxury pr-10" placeholder="Password to track your order" required minlength="6">
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black text-xs font-space-grotesk cursor-pointer">
                                    <span id="pw-toggle-text">SHOW</span>
                                </button>
                            </div>
                            <p class="text-[11px] text-gray-400 font-inter mt-1">A Notisse account is automatically created to track shipment & orders.</p>
                        </div>
                        @else
                        <input type="hidden" id="password" value="logged-in-client">
                        @endif
                    </div>

                    <!-- SECTION 2: SHIPPING DESTINATION -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                            <h2 class="text-sm font-bold font-space-grotesk uppercase tracking-[0.15em] text-black">2. Shipping Address</h2>
                        </div>

                        @if(isset($addresses) && $addresses->count() > 0)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-luxury mb-0">Saved Addresses (Max 3)</label>
                                <span class="text-[10px] font-space-grotesk text-gray-400 font-semibold uppercase">{{ $addresses->count() }} / 3 Saved</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-{{ min(3, $addresses->count()) }} gap-2.5 mb-2">
                                @foreach($addresses as $sAddr)
                                    <button type="button" 
                                            onclick="selectSavedCheckoutAddress({{ json_encode($sAddr) }})"
                                            id="saved-addr-btn-{{ $sAddr->id }}"
                                            class="saved-addr-card text-left p-3 border {{ $sAddr->is_primary ? 'border-black bg-neutral-50 ring-1 ring-black' : 'border-gray-200 bg-white hover:border-gray-400' }} transition-all cursor-pointer">
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="text-[9px] font-space-grotesk font-bold uppercase tracking-widest px-1.5 py-0.5 bg-neutral-200 text-neutral-800">
                                                {{ $sAddr->label }}
                                            </span>
                                            @if($sAddr->is_primary)
                                                <span class="text-[8px] font-space-grotesk font-bold uppercase tracking-wider text-black">
                                                    DEFAULT
                                                </span>
                                            @endif
                                        </div>
                                        <p class="font-bold text-xs font-space-grotesk uppercase mt-1.5 truncate text-black">{{ $sAddr->recipient_name }}</p>
                                        <p class="text-[11px] text-gray-500 font-inter truncate mt-0.5">{{ $sAddr->address }}</p>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div>
                            <label class="label-luxury">Full Recipient Name *</label>
                            <input type="text" id="name" class="input-luxury" placeholder="Recipient full name"
                                   value="{{ (isset($addresses) && $addresses->firstWhere('is_primary', true)) ? $addresses->firstWhere('is_primary', true)->recipient_name : (auth()->user()->name ?? '') }}" required>
                        </div>

                        <div class="relative">
                            <label class="label-luxury">Search Destination District / City *</label>
                            <div class="relative">
                                <input type="text" id="area-search" class="input-luxury pl-9"
                                       placeholder="Type at least 3 letters of district name (e.g. Tebet, Sukajadi, Menteng)..." autocomplete="off">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span id="area-loading-spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-space-grotesk">
                                    Searching...
                                </span>
                            </div>
                            <!-- Floating Dropdown -->
                            <ul id="area-results" class="absolute z-20 w-full bg-white border border-gray-300 shadow-2xl mt-1 hidden max-h-56 overflow-y-auto"></ul>

                            <!-- Confirmed Selected Area Chip -->
                            <div id="confirmed-area-badge" class="hidden mt-2 p-2.5 bg-neutral-50 border border-gray-200 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                    <span class="text-xs font-space-grotesk font-semibold text-black uppercase" id="confirmed-area-text">Selected District</span>
                                </div>
                                <span class="text-[10px] font-space-grotesk font-bold text-gray-500 uppercase">Confirmed</span>
                            </div>
                        </div>

                        <div>
                            <label class="label-luxury">Full Address & House Details *</label>
                            <textarea id="address" class="input-luxury h-24 resize-none leading-relaxed"
                                      placeholder="Street name, block / house number, landmarks..." required>{{ auth()->user()->address ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- SECTION 3: SHIPPING OPTIONS -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                            <h2 class="text-sm font-bold font-space-grotesk uppercase tracking-[0.15em] text-black">3. Shipping Options</h2>
                        </div>
                        <div id="shipping-options-container" class="space-y-3 mt-4">
                            <div id="shipping-loading" class="text-xs text-gray-500 font-inter hidden">Loading shipping rates...</div>
                            <div id="shipping-options" class="flex flex-col space-y-2 text-sm font-space-grotesk">
                                <span class="text-xs text-gray-400 font-inter">Please select destination district first to view shipping options.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" id="area_id" value="{{ auth()->user()->area_id ?? '' }}" required>
                    <input type="hidden" id="area_name">

                    <!-- SUBMIT BUTTON -->
                    <div class="pt-4">
                        <div class="flex justify-center">
                            <button type="submit" id="submit-btn"
                                    class="btn-brush w-full text-xs sm:text-sm font-bold tracking-[0.15em] uppercase cursor-pointer font-space-grotesk flex items-center justify-center space-x-3">
                                <span id="btn-text">CONTINUE TO PAYMENT</span>
                                <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </button>
                        </div>
                        
                        <div class="mt-4 flex flex-col sm:flex-row items-center justify-between text-[11px] text-gray-400 font-inter gap-2">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                <span>Your information is securely encrypted under Notisse privacy protocol.</span>
                            </span>
                            <span class="font-space-grotesk uppercase tracking-wider text-[10px] text-gray-500">Official Atelier</span>
                        </div>
                    </div>
                </form>

            </div>
        </main>

        <!-- RIGHT COLUMN: Order Summary -->
        <aside class="w-full lg:w-5/12 bg-[#fafafa] px-4 sm:px-8 md:px-10 py-8 sm:py-12 order-1 lg:order-2 border-b lg:border-b-0 border-gray-200">
            <div class="max-w-md mx-auto lg:ml-0 lg:sticky lg:top-24 space-y-6">
                
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <h3 class="text-sm font-bold font-space-grotesk uppercase tracking-[0.15em] text-black">Order Summary</h3>
                    <span id="summary-item-count" class="text-xs font-space-grotesk font-semibold text-gray-500 uppercase">0 Items</span>
                </div>

                <!-- Product list -->
                <div id="checkout-summary-items" class="space-y-4 max-h-[380px] overflow-y-auto pr-1">
                    <!-- Populated by JS -->
                </div>

                <!-- Promo / Discount Code Form (HWBT style) -->
                <div class="border-t border-gray-200 pt-4">
                    <form onsubmit="handleApplyPromo(event)" class="flex gap-2 items-start">
                        <div class="flex-1">
                            <input type="text" id="promo-code-input" placeholder="Discount code or gift card"
                                   class="w-full bg-white border border-gray-300 rounded px-3 py-2.5 text-xs font-inter uppercase placeholder:normal-case tracking-wider focus:outline-none focus:border-black transition-colors"
                                   autocomplete="off">
                            <span id="promo-error-msg" class="hidden text-[11px] text-red-500 font-inter mt-1.5 block"></span>
                        </div>
                        <button type="submit" id="apply-promo-btn"
                                class="bg-gray-100 hover:bg-black text-gray-700 hover:text-white border border-gray-300 hover:border-black px-4 py-2.5 text-xs font-bold font-space-grotesk uppercase tracking-wider transition-all duration-200 cursor-pointer shrink-0">
                            Apply
                        </button>
                    </form>

                    <!-- Applied Promo Tag -->
                    <div id="applied-promo-tag" class="hidden mt-2.5 flex items-center justify-between bg-neutral-100 border border-neutral-300 px-3 py-1.5 text-xs">
                        <div class="flex items-center space-x-2">
                            <svg class="w-3.5 h-3.5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            <span id="applied-promo-code" class="font-space-grotesk font-bold uppercase tracking-wider text-black"></span>
                            <span id="applied-promo-discount-text" class="text-gray-500 font-inter text-[11px]"></span>
                        </div>
                        <button type="button" onclick="removePromoCode()" class="text-gray-400 hover:text-black font-bold text-sm cursor-pointer leading-none">&times;</button>
                    </div>
                </div>

                <!-- Price calculation -->
                <div class="border-t border-gray-200 pt-5 space-y-3 font-inter text-xs">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span id="checkout-subtotal" class="font-medium text-black font-space-grotesk text-sm">Rp 0</span>
                    </div>
                    <!-- Discount row (hidden by default) -->
                    <div id="checkout-discount-row" class="hidden justify-between text-emerald-700 font-medium">
                        <span class="flex items-center space-x-1.5">
                            <span>Discount</span>
                            <span id="checkout-discount-label" class="text-[10px] uppercase font-space-grotesk bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-bold"></span>
                        </span>
                        <span id="checkout-discount" class="font-space-grotesk font-bold">- Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <div class="space-y-0.5">
                            <span>Shipping Fee</span>
                            <p class="text-[10px] text-gray-400">JNE, J&T, SiCepat, Anteraja</p>
                        </div>
                        <span class="text-[11px] font-space-grotesk uppercase tracking-wider text-gray-500 italic">Calculated Automatically</span>
                    </div>
                </div>

                <!-- Grand Total -->
                <div class="border-t-2 border-black pt-4 flex justify-between items-baseline">
                    <div>
                        <span class="text-xs font-bold font-space-grotesk uppercase tracking-wider text-black block">Total Due</span>
                        <span class="text-[10px] text-gray-400 font-inter">Includes VAT & Shipping Insurance</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-inter text-gray-400 mr-1">IDR</span>
                        <span id="checkout-total" class="text-xl sm:text-2xl font-bold font-space-grotesk text-black tracking-tight">Rp 0</span>
                    </div>
                </div>

                <!-- Trust Badges Section -->
                <div class="p-4 bg-white border border-gray-200 space-y-3 rounded-sm">
                    <div class="flex items-center space-x-3 text-xs text-gray-600">
                        <svg class="w-5 h-5 text-black shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span>100% officially curated by Notisse</span>
                    </div>
                    <div class="flex items-center space-x-3 text-xs text-gray-600">
                        <svg class="w-5 h-5 text-black shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span><strong>Payment:</strong> QRIS, Virtual Account (BCA, Mandiri, BNI, BRI), Credit Card.</span>
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
        let discountAmount = 0;
        let appliedPromoCode = null;

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
                confirmedText.innerText = 'Registered District (ID: ' + existingAreaId + ')';
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
                        <p class="text-xs text-gray-500 font-inter">Your cart is empty.</p>
                        <a href="/?open_checkout=true" class="inline-block text-xs font-space-grotesk font-bold uppercase underline tracking-wider text-black">
                            Explore Collection
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
                    const itemName = item.product?.name || item.name || 'Product';
                    const itemSize = item.size || 'All Size';

                    summaryContainer.innerHTML += `
                        <div class="flex items-center space-x-3.5 bg-white p-3 border border-gray-200">
                            <div class="relative w-14 h-16 bg-neutral-100 border border-gray-200 shrink-0 overflow-hidden">
                                <img src="${imgUrl || '/footage-baju.jpg'}" alt="${itemName}" class="w-full h-full object-cover" onerror="this.src='/footage-baju.jpg'">
                                <span class="absolute top-0 right-0 bg-black text-white text-[9px] font-bold font-space-grotesk px-1.5 py-0.5 leading-none">x${itemQty}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-space-grotesk font-bold text-xs uppercase text-black truncate">${itemName}</h4>
                                <p class="text-[11px] text-gray-500 font-inter mt-0.5">Size: <strong class="text-black font-space-grotesk">${itemSize}</strong></p>
                                <p class="text-xs font-space-grotesk font-bold text-black mt-1">${formattedPrice}</p>
                            </div>
                        </div>
                    `;
                });

                document.getElementById('summary-item-count').innerText = `${totalCount} Items`;
                updateTotalDisplay();
            }
        }

        function updateTotalDisplay() {
            const formattedSubtotal = 'Rp ' + subtotal.toLocaleString('id-ID');
            const grandTotal = Math.max(0, subtotal - discountAmount + selectedShippingCost);
            const formattedTotal = 'Rp ' + grandTotal.toLocaleString('id-ID');

            document.getElementById('checkout-subtotal').innerText = formattedSubtotal;
            document.getElementById('checkout-total').innerText = formattedTotal;

            const discountRow = document.getElementById('checkout-discount-row');
            if (discountRow) {
                if (discountAmount > 0) {
                    discountRow.classList.remove('hidden');
                    discountRow.classList.add('flex');
                    document.getElementById('checkout-discount').innerText = '- Rp ' + discountAmount.toLocaleString('id-ID');
                    document.getElementById('checkout-discount-label').innerText = appliedPromoCode || '';
                } else {
                    discountRow.classList.add('hidden');
                    discountRow.classList.remove('flex');
                }
            }
        }

        function handleApplyPromo(e) {
            e.preventDefault();
            const input = document.getElementById('promo-code-input');
            const errorMsg = document.getElementById('promo-error-msg');
            const code = (input.value || '').trim().toUpperCase();

            errorMsg.classList.add('hidden');
            errorMsg.innerText = '';

            if (!code) {
                errorMsg.innerText = 'Enter a valid discount code';
                errorMsg.classList.remove('hidden');
                return;
            }

            if (subtotal <= 0) {
                errorMsg.innerText = 'Add items to your cart first';
                errorMsg.classList.remove('hidden');
                return;
            }

            // Supported demo codes: NOTISSE (10%), NOTISSE10 (10%), NOTISSE50 (50%)
            if (code === 'NOTISSE' || code === 'NOTISSE10') {
                discountAmount = Math.round(subtotal * 0.10);
                appliedPromoCode = code;
            } else if (code === 'NOTISSE50') {
                discountAmount = Math.round(subtotal * 0.50);
                appliedPromoCode = code;
            } else {
                errorMsg.innerText = 'Enter a valid discount code';
                errorMsg.classList.remove('hidden');
                return;
            }

            input.value = '';
            document.getElementById('applied-promo-tag').classList.remove('hidden');
            document.getElementById('applied-promo-code').innerText = appliedPromoCode;
            document.getElementById('applied-promo-discount-text').innerText = `(-Rp ${discountAmount.toLocaleString('id-ID')})`;
            updateTotalDisplay();
        }

        function removePromoCode() {
            discountAmount = 0;
            appliedPromoCode = null;
            document.getElementById('applied-promo-tag').classList.add('hidden');
            const errorMsg = document.getElementById('promo-error-msg');
            if (errorMsg) errorMsg.classList.add('hidden');
            updateTotalDisplay();
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
                name: item.product?.name || item.name || 'Product',
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
                    shippingOptions.innerHTML = '<span class="text-xs text-red-500">No courier available for this location.</span>';
                }
            } catch (err) {
                loadingIndicator.classList.add('hidden');
                shippingOptions.innerHTML = '<span class="text-xs text-red-500">Failed to retrieve shipping rates.</span>';
            }

            isFetchingShipping = false;
        }

        window.selectShipping = function(name, cost) {
            selectedCourierName = name;
            selectedShippingCost = parseInt(cost);
            updateTotalDisplay();
        };

        window.selectSavedCheckoutAddress = function(addr) {
            if (!addr) return;

            document.querySelectorAll('.saved-addr-card').forEach(card => {
                card.classList.remove('border-black', 'bg-neutral-50', 'ring-1', 'ring-black');
                card.classList.add('border-gray-200', 'bg-white');
            });
            const selectedBtn = document.getElementById(`saved-addr-btn-${addr.id}`);
            if (selectedBtn) {
                selectedBtn.classList.remove('border-gray-200', 'bg-white');
                selectedBtn.classList.add('border-black', 'bg-neutral-50', 'ring-1', 'ring-black');
            }

            const nameEl = document.getElementById('name');
            const phoneEl = document.getElementById('phone');
            const addrEl = document.getElementById('address');
            const areaIdEl = document.getElementById('area_id');
            const areaNameEl = document.getElementById('area_name');
            const areaSearchEl = document.getElementById('area-search');

            if (nameEl && addr.recipient_name) nameEl.value = addr.recipient_name;
            if (phoneEl && addr.phone) phoneEl.value = addr.phone;
            if (addrEl && addr.address) addrEl.value = addr.address;
            if (areaIdEl && addr.area_id) areaIdEl.value = addr.area_id;
            if (areaNameEl) areaNameEl.value = addr.area_name || '';
            if (areaSearchEl) areaSearchEl.value = addr.area_name || '';

            const badge = document.getElementById('confirmed-area-badge');
            const badgeText = document.getElementById('confirmed-area-text');
            if (badge && badgeText) {
                badgeText.innerText = addr.area_name || `Area (${addr.area_id})`;
                badge.classList.remove('hidden');
            }

            if (addr.area_id && typeof fetchShippingRates === 'function') {
                fetchShippingRates(addr.area_id);
            }
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
            confirmedText.innerText = 'Registered District (ID: ' + existingAreaId + ')';
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
                                li.className = 'px-4 py-2.5 hover:bg-neutral-100 cursor-pointer text-xs font-inter border-b border-gray-100 last:border-0 text-black flex justify-between items-center transition-colors';
                                li.innerHTML = `
                                    <span class="font-medium">${area.name}</span>
                                    <span class="text-[10px] text-gray-400 font-space-grotesk uppercase">SELECT &rarr;</span>
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
                            areaResultsBox.innerHTML = '<li class="px-4 py-3 text-xs text-gray-400 font-inter text-center">District not found. Try searching with another keyword.</li>';
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
                toggleText.innerText = 'HIDE';
            } else {
                pwInput.type = 'password';
                toggleText.innerText = 'SHOW';
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

            if (!cart || cart.length === 0) {
                errorBox.innerHTML = '<strong>Cart is Empty:</strong> Please add items to your cart before proceeding to checkout.';
                errorBox.classList.remove('hidden');
                return;
            }

            if (!areaId) {
                errorBox.innerHTML = '<strong>Select Shipping District:</strong> Please type your district name in the search box and select an option from the list.';
                errorBox.classList.remove('hidden');
                areaSearchInput.focus();
                return;
            }

            if (!selectedCourierName) {
                errorBox.innerHTML = '<strong>Select Shipping Courier:</strong> Please choose one of the available courier options.';
                errorBox.classList.remove('hidden');
                return;
            }

            const btn = document.getElementById('submit-btn');
            const originalText = document.getElementById('btn-text').innerText;
            document.getElementById('btn-text').innerText = 'VERIFYING DETAILS...';
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
                    document.getElementById('btn-text').innerText = 'CREATING ORDER...';
                    
                    const items = cart.map(item => ({
                        id: item.product_id || item.id,
                        name: item.product?.name || item.name || 'Product',
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
                        promo_code: appliedPromoCode || null,
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
                        throw new Error(checkoutData.error || 'Failed to retrieve Midtrans token');
                    }

                } else {
                    errorBox.innerText = authData.message || 'System error occurred. Please check your information.';
                    errorBox.classList.remove('hidden');
                    document.getElementById('btn-text').innerText = originalText;
                    btn.disabled = false;
                    btn.classList.remove('opacity-75');
                }
            } catch (err) {
                errorBox.innerText = 'Server connection failed. ' + err.message;
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
