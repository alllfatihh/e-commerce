<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notisse - Fashion & Art Brand</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts Preconnect & Imports (Montserrat, Roboto, Caveat) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'],
                        roboto: ['Roboto', 'sans-serif'],
                        helvetica: ['"Helvetica Neue"', 'Helvetica', 'Arial', 'sans-serif'],
                        neueMontreal: ['"Neue Montreal"', '"Helvetica Neue"', 'Helvetica', 'Arial', 'sans-serif'],
                        cursive: ['Caveat', 'cursive'],
                    },
                    colors: {
                        brandDark: '#1c1c1c',
                        taupeBanner: '#A29892',
                        accentYellow: '#E2FF00',
                        placeholderBg: '#d9d9d9'
                    }
                }
            }
        }
    </script>

    <style>
        @font-face {
            font-family: 'Neue Montreal';
            src: local('Neue Montreal'), local('Helvetica Neue'), local('Arial');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff;
            color: #111111;
            overflow-x: hidden;
        }

        .font-montserrat {
            font-family: 'Montserrat', sans-serif !important;
        }

        .font-roboto {
            font-family: 'Roboto', sans-serif !important;
        }

        .font-helvetica {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
        }

        .font-neue-montreal {
            font-family: 'Neue Montreal', 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
        }

        .font-cursive {
            font-family: 'Caveat', cursive !important;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            animation: marquee 15s linear infinite;
        }

        .view-page {
            display: none !important;
            width: 100%;
        }

        .view-page.active-view {
            display: block !important;
            animation: fadeInUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* CUSTOM BRUSH BUTTON WITH ICON.PNG BACKGROUND */
        .btn-brush {
            font-family: 'Montserrat', sans-serif !important;
            background-image: url('{{ asset('icon.png') }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
            background-color: transparent;
            color: #E2FF00;
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
            transform: scale(1.05) rotate(-0.8deg);
            opacity: 0.95;
        }

        .btn-brush:active {
            transform: scale(0.95) rotate(0.5deg);
        }

        .accordion-content {
            display: grid;
            grid-template-rows: 0fr;
            overflow: hidden !important;
            opacity: 0;
            visibility: hidden;
            transition: grid-template-rows 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease, visibility 0.25s ease;
        }

        .accordion-content.expanded {
            grid-template-rows: 1fr;
            opacity: 1;
            visibility: visible;
        }

        .accordion-inner {
            overflow: hidden !important;
            min-height: 0 !important;
        }

        .custom-dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.25s ease;
        }

        .custom-dropdown-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .chevron-icon {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .chevron-icon.rotated {
            transform: rotate(180deg);
        }

        .overlay-slide {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
        }

        .overlay-backdrop {
            transition: opacity 0.3s ease;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888888;
        }

        /* Dynamic Morphing Cart */
        #cart-trigger {
            position: relative;
            width: 80px;
            height: 40px;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #cart-trigger.is-scrolled {
            width: 20px;
            height: 112px;
        }

        .cart-letter {
            position: absolute;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .cart-line-h, .cart-line-v {
            position: absolute;
            background-color: currentColor;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .cart-arrow {
            position: absolute;
            width: 12px;
            height: 12px;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .cart-badge {
            position: absolute;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* State: Unscrolled (Horizontal) */
        .l-c { top: 0px; left: 0px; }
        .l-a { top: 0px; left: 10px; }
        .l-r { top: 0px; left: 20px; }
        .l-t { top: 0px; left: 30px; }

        .cart-line-h { top: 7px; left: 42px; width: 18px; height: 1.5px; opacity: 1; }
        .cart-line-v { top: 7px; left: 60px; width: 1.5px; height: 12px; }
        
        .cart-arrow { top: 15px; left: 54.5px; }
        .cart-badge { top: 25px; left: 57px; }

        /* State: Scrolled (Vertical) */
        .is-scrolled .l-c { top: 0px; left: 4px; }
        .is-scrolled .l-a { top: 14px; left: 4px; }
        .is-scrolled .l-r { top: 28px; left: 4px; }
        .is-scrolled .l-t { top: 42px; left: 4px; }

        .is-scrolled .cart-line-h { top: 62px; left: 8px; width: 0px; height: 1.5px; opacity: 0; }
        .is-scrolled .cart-line-v { top: 62px; left: 8px; width: 1.5px; height: 24px; }
        
        .is-scrolled .cart-arrow { top: 80px; left: 2.5px; }
        .is-scrolled .cart-badge { top: 92px; left: 4.5px; }
    </style>
</head>

<body
    class="min-h-screen flex flex-col justify-between relative bg-white text-black antialiased selection:bg-black selection:text-white">

    <!-- GLOBAL PERSISTENT HEADER -->
    <header
        class="fixed top-0 left-0 w-full z-50 bg-transparent px-6 py-4 flex items-start justify-between transition-all duration-300">
        <!-- Left: Hamburger Icon -->
        <button id="menu-trigger" aria-label="Open Navigation Menu"
            class="p-1 mt-1 focus:outline-none hover:scale-110 active:scale-90 transition-all duration-300 cursor-pointer opacity-0 pointer-events-none">
            <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Center: Original Logo Image (logo.png) -->
        <div class="absolute left-1/2 transform -translate-x-1/2 cursor-pointer hover:opacity-80 transition-opacity flex items-start"
            onclick="showPage('view-shop')">
            <img id="header-logo" src="{{ asset('logo.png') }}" alt="Notisse Logo"
                class="h-6 sm:h-8 md:h-10 w-auto object-contain select-none transition-all duration-300 mt-1">
        </div>

        <!-- Right: ACCOUNT, SEARCH & CART Buttons -->
        <div id="header-right-menu"
            class="flex items-start space-x-2 sm:space-x-5 text-[10px] sm:text-xs font-semibold tracking-widest uppercase transition-all duration-300 mt-1">
            <button id="account-header-btn" onclick="handleAccountButtonClick()"
                class="hidden md:inline-block font-helvetica font-medium hover:opacity-60 hover:scale-105 active:scale-95 transition-all focus:outline-none p-1 cursor-pointer">
                ACCOUNT
            </button>
            <button id="search-trigger"
                class="font-helvetica font-medium hover:opacity-60 hover:scale-105 active:scale-95 transition-all focus:outline-none p-1 cursor-pointer">
                SEARCH
            </button>
            <button id="cart-trigger"
                class="font-helvetica font-medium hover:opacity-60 active:scale-95 transition-all focus:outline-none p-1 cursor-pointer">
                <!-- Individual letters for morphing -->
                <span class="cart-letter l-c">C</span>
                <span class="cart-letter l-a">A</span>
                <span class="cart-letter l-r">R</span>
                <span class="cart-letter l-t">T</span>

                <!-- Animated Lines & Arrow -->
                <div class="cart-line-h"></div>
                <div class="cart-line-v"></div>
                <svg class="cart-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="7 10 12 15 17 10"></polyline>
                </svg>

                <span id="header-cart-badge" class="cart-badge">2</span>
            </button>
        </div>
    </header>

    <!-- OVERLAY 1: 1/4 SCREEN WIDTH NAVIGATION SIDEBAR -->
    <div id="overlay-menu-backdrop"
        class="fixed inset-0 bg-black/50 z-50 hidden opacity-0 overlay-backdrop transition-opacity duration-300"
        onclick="closeMenu()">
        <div id="overlay-menu-sidebar"
            class="absolute left-0 top-0 bottom-0 w-full sm:w-80 md:w-1/4 bg-[#d52c2b] text-white p-6 md:p-10 transform -translate-x-full overlay-slide flex flex-col justify-between shadow-2xl"
            onclick="event.stopPropagation()">
            <div class="flex justify-end">
                <button id="menu-close" aria-label="Close Menu"
                    class="text-2xl font-light hover:rotate-90 hover:text-gray-300 transition-transform duration-300 focus:outline-none cursor-pointer">
                    &#10005;
                </button>
            </div>

            <nav class="flex flex-col space-y-6 md:space-y-8 my-auto font-roboto">
                <a href="javascript:void(0)" onclick="navigateTo('view-shop')"
                    class="font-roboto text-xl md:text-2xl font-normal tracking-wider hover:translate-x-2 hover:text-gray-300 transition-all duration-300 w-fit">SHOP</a>
                <a href="javascript:void(0)" onclick="navigateTo('view-artist-collab')"
                    class="font-roboto text-xl md:text-2xl font-normal tracking-wider hover:translate-x-2 hover:text-gray-300 transition-all duration-300 w-fit">ARTIST
                    COLLAB</a>
                <a href="javascript:void(0)" onclick="navigateTo('view-lookbook-list')"
                    class="font-roboto text-xl md:text-2xl font-normal tracking-wider hover:translate-x-2 hover:text-gray-300 transition-all duration-300 w-fit">LOOKBOOK</a>
                <a href="javascript:void(0)" onclick="navigateTo('view-about')"
                    class="font-roboto text-xl md:text-2xl font-normal tracking-wider hover:translate-x-2 hover:text-gray-300 transition-all duration-300 w-fit">ABOUT</a>
            </nav>

            <div class="flex flex-col space-y-4 font-roboto mb-2">
                <a href="javascript:void(0)" onclick="navigateTo('view-contact')"
                    class="text-sm md:text-base font-normal tracking-widest text-gray-400 hover:text-white transition-all duration-300 w-fit">CONTACT</a>
                <a href="javascript:void(0)" onclick="closeMenu(); handleAccountButtonClick();"
                    class="text-sm md:text-base font-normal tracking-widest text-gray-400 hover:text-white transition-all duration-300 w-fit">ACCOUNT</a>
            </div>

        </div>
    </div>

    <!-- OVERLAY 2: TOP-BAR LIVE SEARCH DROPDOWN -->
    <div id="overlay-search"
        class="fixed top-0 left-0 right-0 bg-white border-b border-gray-300 shadow-2xl z-50 transform -translate-y-full overlay-slide px-6 py-6 md:px-12 transition-transform duration-300 max-h-[85vh] overflow-y-auto">
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Search Header Bar -->
            <div class="flex items-center justify-between gap-4">
                <div class="relative flex-grow flex items-center border-b border-black pb-1">
                    <input type="text" id="search-input" oninput="handleLiveSearch(this.value)"
                        placeholder="Type to search products (e.g. 'O', 'Tee', 'Black')..."
                        class="w-full text-base md:text-xl bg-transparent focus:outline-none placeholder-gray-400 font-light font-roboto py-1">
                    <button aria-label="Submit Search"
                        class="ml-2 text-xl md:text-2xl hover:translate-x-1 transition-transform focus:outline-none cursor-pointer">
                        &rarr;
                    </button>
                </div>
                <button id="search-close" aria-label="Close Search"
                    class="text-2xl font-light hover:rotate-90 hover:text-gray-600 transition-transform duration-300 focus:outline-none cursor-pointer p-1">
                    &#10005;
                </button>
            </div>

            <!-- Dynamic Search Result Header -->
            <div id="search-results-meta"
                class="text-xs uppercase tracking-widest text-gray-400 font-montserrat font-semibold hidden">
                PRODUCTS (<span id="search-results-count">0</span>)
            </div>

            <!-- Dynamic Live Results Grid Container -->
            <div id="search-results-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6 pt-2">
                <!-- Live search items render dynamically here -->
            </div>
        </div>
    </div>

    <!-- OVERLAY 3: MY CART SLIDE-OVER DRAWER -->
    <div id="overlay-cart"
        class="fixed inset-0 bg-black/40 z-50 hidden opacity-0 overlay-backdrop transition-opacity duration-400"
        onclick="closeCart()">
        <div id="cart-drawer-content"
            class="absolute right-0 top-0 bottom-0 w-full max-w-4xl bg-white p-6 md:p-10 transform translate-x-full overlay-slide flex flex-col overflow-y-auto shadow-2xl"
            onclick="event.stopPropagation()">
            <div class="flex justify-between items-center border-b border-gray-200 pb-3 mb-6">
                <h2 class="text-2xl md:text-3xl font-semibold tracking-tight font-montserrat">My cart</h2>
                <button id="cart-close" aria-label="Close Cart"
                    class="text-3xl font-light hover:rotate-90 transition-transform duration-300 focus:outline-none cursor-pointer">
                    &#10005;
                </button>
            </div>

            <div
                class="grid grid-cols-12 text-xs text-gray-500 font-regular pb-2 border-b border-gray-200 mb-6 font-neue-montreal">
                <div class="col-span-6 md:col-span-5">Product</div>
                <div class="col-span-3 md:col-span-3 text-center">Quantity</div>
                <div class="hidden md:block md:col-span-2 text-right">Price</div>
                <div class="col-span-3 md:col-span-2 text-right">Total</div>
            </div>

            <div id="cart-items-container" class="space-y-6 flex-grow"></div>

            <div class="mt-10 pt-6 border-t border-gray-200 flex flex-col items-end space-y-1">
                <div class="flex items-baseline space-x-6">
                    <span class="text-base md:text-lg font-medium text-gray-900 font-montserrat">Estimated total</span>
                    <span id="cart-total-price" class="text-xl md:text-xl font-regular font-montserrat">Rp 500.000,00
                        IDR</span>
                </div>
                <p class="text-xs text-gray-400 font-montserrat">taxes, discounts and shipping calculated at checkout.
                </p>
                <div class="pt-4 w-full text-right">
                    <button onclick="processCheckout()"
                        class="btn-brush text-sm md:text-base cursor-pointer font-montserrat" id="checkout-btn">
                        CHECKOUT
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION CONTAINER -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 pointer-events-none flex flex-col space-y-2"></div>

    <!-- MAIN APPLICATION PAGE VIEWS -->
    <main class="flex-grow w-full relative">

        <!-- VIEW 1: SHOP CATALOG WITH AUTO-SLIDING HERO CAROUSEL -->
        <section id="view-shop" class="view-page active-view pb-16">
            <div class="relative w-full h-screen overflow-hidden bg-black group">
                <div id="hero-carousel-slides" class="w-full h-full flex transition-transform duration-700 ease-in-out">
                    <div class="w-full h-full flex-shrink-0 relative">
                        <img src="{{ asset('home.jpg') }}" alt="Slide 1" class="w-full h-full object-cover">
                    </div>
                    <div class="w-full h-full flex-shrink-0 relative">
                        <img src="{{ asset('home1.jpg') }}" alt="Slide 2" class="w-full h-full object-cover">
                    </div>
                    <div class="w-full h-full flex-shrink-0 relative">
                        <img src="{{ asset('home2.png') }}" alt="Slide 3" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- OVERLAY MENU PADA CAROUSEL (A24 STYLE) -->
                <div class="absolute bottom-10 left-12 md:bottom-16 md:left-24 z-20 flex flex-col pointer-events-none">
                    <a href="javascript:void(0)" onclick="navigateTo('view-shop')"
                        class="group flex items-start pointer-events-auto cursor-pointer mb-1 md:mb-2 transition-colors duration-300 text-white/70 hover:text-white">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[5.5rem] font-bold tracking-tighter leading-[0.85] font-sans">Shop</span>
                        <span class="text-[10px] md:text-xs font-bold ml-2 mt-2 md:mt-3 tracking-widest">2026</span>
                    </a>
                    <a href="javascript:void(0)" onclick="navigateTo('view-artist-collab')"
                        class="group flex items-start pointer-events-auto cursor-pointer mb-1 md:mb-2 transition-colors duration-300 text-white/70 hover:text-white">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[5.5rem] font-bold tracking-tighter leading-[0.85] font-sans">Artist
                            Collab</span>
                        <span class="text-[10px] md:text-xs font-bold ml-2 mt-2 md:mt-3 tracking-widest">2026</span>
                    </a>
                    <a href="javascript:void(0)" onclick="navigateTo('view-lookbook-list')"
                        class="group flex items-start pointer-events-auto cursor-pointer mb-1 md:mb-2 transition-colors duration-300 text-white/70 hover:text-white">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[5.5rem] font-bold tracking-tighter leading-[0.85] font-sans">Lookbook</span>
                        <span class="text-[10px] md:text-xs font-bold ml-2 mt-2 md:mt-3 tracking-widest">2026</span>
                    </a>
                    <a href="javascript:void(0)" onclick="navigateTo('view-about')"
                        class="group flex items-start pointer-events-auto cursor-pointer mb-1 md:mb-2 transition-colors duration-300 text-white/70 hover:text-white">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[5.5rem] font-bold tracking-tighter leading-[0.85] font-sans">About</span>
                        <span class="text-[10px] md:text-xs font-bold ml-2 mt-2 md:mt-3 tracking-widest">2026</span>
                    </a>
                </div>

                <button onclick="prevSlide()"
                    class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 text-white text-4xl md:text-5xl hover:scale-110 drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)] flex items-center justify-center focus:outline-none transition-all opacity-0 group-hover:opacity-100 cursor-pointer">
                    &#10094;
                </button>
                <button onclick="nextSlide()"
                    class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 text-white text-4xl md:text-5xl hover:scale-110 drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)] flex items-center justify-center focus:outline-none transition-all opacity-0 group-hover:opacity-100 cursor-pointer">
                    &#10095;
                </button>

                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                    <button onclick="goToSlide(0)"
                        class="hero-dot w-2.5 h-2.5 rounded-full bg-white opacity-100 transition-opacity cursor-pointer"></button>
                    <button onclick="goToSlide(1)"
                        class="hero-dot w-2.5 h-2.5 rounded-full bg-white opacity-40 transition-opacity cursor-pointer"></button>
                    <button onclick="goToSlide(2)"
                        class="hero-dot w-2.5 h-2.5 rounded-full bg-white opacity-40 transition-opacity cursor-pointer"></button>
                </div>
            </div>


            <!-- PRODUCT CATALOG GRID -->
            <div class="w-full">
                <div class="grid grid-cols-2 lg:grid-cols-4 bg-black gap-[1px] border-b border-black">
                    @foreach($products as $product)
                        <div onclick="openProductDetail('{{ addslashes($product->name) }}', 'Rp {{ number_format($product->price, 2, ',', '.') }}')"
                            class="group cursor-pointer bg-white flex flex-col justify-between h-full hover:bg-gray-50 transition-colors duration-300">

                            <div class="relative w-full aspect-[3/4] bg-[#f4f4f4] overflow-hidden">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                    class="absolute inset-0 w-full h-full object-cover mix-blend-multiply transition-opacity duration-500 ease-out group-hover:opacity-0 z-10">
                                <img src="{{ asset('footagebaju2.jpg') }}" alt="{{ $product->name }} Hover"
                                    class="absolute inset-0 w-full h-full object-cover mix-blend-multiply transition-all duration-500 ease-out opacity-0 group-hover:opacity-100 group-hover:scale-105 z-0">
                            </div>

                            <div class="p-3 md:p-4 flex flex-col justify-between flex-grow border-t border-black">
                                <div
                                    class="flex flex-col xl:flex-row xl:justify-between items-start xl:items-center text-[10px] md:text-xs font-montserrat uppercase font-semibold text-black gap-1">
                                    <span>{{ $product->name }}</span>
                                    <span class="whitespace-nowrap">RP
                                        {{ number_format($product->price, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- VIEW 2: PRODUCT DETAIL PAGE -->
        <section id="view-product-detail" class="view-page px-6 md:px-12 pt-24 pb-10 max-w-7xl mx-auto">
            <div class="mb-6">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 md:gap-16 items-start">
                <div class="space-y-8 lg:sticky lg:top-24">
                    <div>
                        <h1 id="pdetail-title"
                            class="text-2xl md:text-3xl font-semibold uppercase tracking-wider text-black font-montserrat">
                            RAJA DIAMUK MASSA TEE V1
                        </h1>
                        <p class="text-xs md:text-sm text-gray-600 mt-2 font-montserrat">
                            by <span onclick="openArtistProfile('Mustafa Alatas', 'Temanggung')"
                                class="underline hover:text-black cursor-pointer font-medium transition-colors font-montserrat">Mustafa
                                Alatas</span>
                        </p>
                    </div>

                    <div class="space-y-2 border-t border-b border-gray-200 py-2">
                        <div class="border-b border-gray-200">
                            <button onclick="toggleAccordion('desc')"
                                class="w-full flex justify-between items-center text-left py-3 focus:outline-none cursor-pointer group">
                                <span
                                    class="text-base md:text-lg font-medium text-gray-500 group-hover:text-black transition-colors font-neue-montreal">Product
                                    Description</span>
                                <svg id="acc-icon-desc" class="w-4 h-4 chevron-icon stroke-current text-gray-600"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="acc-content-desc" class="accordion-content">
                                <div
                                    class="accordion-inner pb-4 pt-1 text-[11px] md:text-xs text-gray-500 font-neue-montreal">
                                    <ul class="list-disc pl-5 space-y-1.5 leading-relaxed font-neue-montreal">
                                        <li>Heavyweight 24s Cotton Combed construction</li>
                                        <li>High-density screenprinted artwork on back and chest</li>
                                        <li>Boxy relaxed fit with reinforced ribbed collar</li>
                                        <li>Crafted & printed in Bandung, Indonesia</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="border-b border-gray-200">
                            <button onclick="toggleAccordion('size')"
                                class="w-full flex justify-between items-center text-left py-3 focus:outline-none cursor-pointer group">
                                <span
                                    class="text-base md:text-lg font-medium text-gray-500 group-hover:text-black transition-colors font-neue-montreal">Size
                                    Guide</span>
                                <svg id="acc-icon-size" class="w-4 h-4 chevron-icon stroke-current text-gray-600"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="acc-content-size" class="accordion-content">
                                <div
                                    class="accordion-inner pb-4 pt-1 text-[11px] md:text-xs text-gray-500 font-neue-montreal">
                                    <ul class="list-disc pl-5 space-y-1.5 leading-relaxed font-neue-montreal">
                                        <li>Small: Width 52 cm | Length 70 cm</li>
                                        <li>Medium: Width 55 cm | Length 73 cm</li>
                                        <li>Large: Width 58 cm | Length 76 cm</li>
                                        <li>Extra Large: Width 61 cm | Length 79 cm</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button onclick="toggleAccordion('terms')"
                                class="w-full flex justify-between items-center text-left py-3 focus:outline-none cursor-pointer group">
                                <span
                                    class="text-base md:text-lg font-medium text-gray-500 group-hover:text-black transition-colors font-neue-montreal">Terms
                                    and Condition</span>
                                <svg id="acc-icon-terms" class="w-4 h-4 chevron-icon stroke-current text-gray-600"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="acc-content-terms" class="accordion-content">
                                <div
                                    class="accordion-inner pb-4 pt-1 text-[11px] md:text-xs text-gray-500 font-neue-montreal">
                                    <ul class="list-disc pl-5 space-y-1.5 leading-relaxed font-neue-montreal">
                                        <li>All sales are final once order is confirmed.</li>
                                        <li>Exchanges strictly allowed for manufacturing defects within 3 days.</li>
                                        <li>Shipping fee calculated dynamically upon checkout.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-6">
                        <div class="relative w-full sm:w-52 font-montserrat" id="custom-size-dropdown">
                            <button id="size-dropdown-trigger" onclick="toggleSizeDropdown(event)" type="button"
                                class="w-full border border-black py-2.5 px-3.5 text-xs md:text-sm font-medium uppercase tracking-wider bg-white text-black flex justify-between items-center cursor-pointer hover:bg-black hover:text-white transition-colors duration-200 font-montserrat">
                                <span id="selected-size-label" class="font-montserrat">Select size: </span>
                                <svg id="size-dropdown-chevron"
                                    class="w-4 h-4 chevron-icon stroke-current ml-2 flex-shrink-0" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="size-dropdown-menu"
                                class="custom-dropdown-menu absolute left-0 right-0 top-full mt-1.5 bg-white border border-black z-20 shadow-xl overflow-hidden font-montserrat">
                                <div onclick="selectSize('S')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors border-b border-gray-100 flex justify-between items-center font-montserrat">
                                    <span>Small (S)</span><span class="text-xs opacity-50">S</span>
                                </div>
                                <div onclick="selectSize('M')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors border-b border-gray-100 flex justify-between items-center bg-gray-50 font-montserrat">
                                    <span>Medium (M)</span><span class="text-xs opacity-50">M</span>
                                </div>
                                <div onclick="selectSize('L')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors border-b border-gray-100 flex justify-between items-center font-montserrat">
                                    <span>Large (L)</span><span class="text-xs opacity-50">L</span>
                                </div>
                                <div onclick="selectSize('XL')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors flex justify-between items-center font-montserrat">
                                    <span>Extra Large (XL)</span><span class="text-xs opacity-50">XL</span>
                                </div>
                            </div>
                        </div>

                        <button onclick="addSelectedToCart()"
                            class="btn-brush text-xs md:text-sm uppercase cursor-pointer font-montserrat">
                            ADD TO CART
                        </button>
                    </div>
                </div>

                <div class="w-full space-y-6">
                    <div class="w-full border border-black aspect-[3/4] bg-white overflow-hidden shadow-sm">
                        <img src="{{ asset('footage-baju.jpg') }}" alt="Front View"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="w-full border border-black aspect-[3/4] bg-white overflow-hidden shadow-sm">
                        <img src="{{ asset('footage-baju-belakang.jpg') }}" alt="Back Graphic"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="w-full border border-black aspect-[3/4] bg-white overflow-hidden shadow-sm">
                        <img src="{{ asset('footagebaju2.jpg') }}"
                            alt="Detail Close up" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 3: ARTIST COLLAB LIST PAGE -->
        <section id="view-artist-collab" class="view-page px-4 md:px-12 py-10 max-w-7xl mx-auto">
            <div class="mb-16">
                <h2 class="text-xl md:text-2xl font-bold mb-6 tracking-wide text-black font-montserrat">Newcomer</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-8">
                    <div class="aspect-[16/10] bg-gray-200 overflow-hidden group cursor-pointer"
                        onclick="openArtistProfile('Mustafa Alatas', 'Temanggung')">
                        <img src="https://placehold.co/800x500/222222/ffffff?text=Mustafa+Portrait"
                            alt="Mustafa Portrait"
                            class="w-full h-full object-cover grayscale group-hover:scale-105 transition-all duration-500">
                    </div>
                    <div class="aspect-[16/10] bg-gray-200 overflow-hidden group cursor-pointer"
                        onclick="openArtistProfile('Mustafa Alatas', 'Temanggung')">
                        <img src="https://placehold.co/800x500/3d5a45/ffffff?text=Mustafa+Artwork" alt="Mustafa Artwork"
                            class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                    </div>
                </div>

                <div class="max-w-3xl mx-auto text-center my-8 px-2 space-y-4">
                    <h3 class="text-lg md:text-xl font-bold text-black font-roboto">About Mustafa</h3>
                    <p class="text-gray-700 text-xs md:text-sm leading-relaxed font-regular font-roboto">
                        Mustafa Alatas is a contemporary visual artist based in Temanggung whose distinct brush strokes
                        and visceral storytelling bring deep cultural narratives into modern street apparel.
                    </p>
                    <div class="pt-2">
                        <button onclick="openArtistProfile('Mustafa Alatas', 'Temanggung')"
                            class="btn-brush text-xs py-2 px-6 cursor-pointer font-montserrat">Read More</button>
                    </div>
                </div>
            </div>

            <!-- ARTIST COLLABORATOR SECTION -->
            <div class="pt-8 border-t border-gray-200">
                <h2 class="text-xl md:text-2xl font-bold mb-8 tracking-wide text-black font-montserrat">Artist
                    Collaborator</h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    <div class="group cursor-pointer" onclick="openArtistProfile('Mustafa Alatas', 'Temanggung')">
                        <div class="w-full aspect-square bg-gray-200 mb-3 overflow-hidden">
                            <img src="https://placehold.co/500x500/181818/ffffff?text=Mustafa+Alatas"
                                alt="Mustafa Alatas"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-sm md:text-base text-black font-roboto">Mustafa Alatas</h4>
                        <p class="text-xs text-gray-500 font-roboto">Temanggung</p>
                    </div>

                    <div class="group cursor-pointer" onclick="openArtistProfile('Asep', 'Palembang')">
                        <div class="w-full aspect-square bg-gray-200 mb-3 overflow-hidden">
                            <img src="https://placehold.co/500x500/222222/ffffff?text=Asep" alt="Asep"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-sm md:text-base text-black font-roboto">Asep</h4>
                        <p class="text-xs text-gray-500 font-roboto">Palembang</p>
                    </div>

                    <div class="group cursor-pointer" onclick="openArtistProfile('Asepo', 'Jakarta')">
                        <div class="w-full aspect-square bg-gray-200 mb-3 overflow-hidden">
                            <img src="https://placehold.co/500x500/2c2c2c/ffffff?text=Asepo" alt="Asepo"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-sm md:text-base text-black font-roboto">Asepo</h4>
                        <p class="text-xs text-gray-500 font-roboto">Jakarta</p>
                    </div>

                    <div class="group cursor-pointer" onclick="openArtistProfile('Asepi', 'Padang')">
                        <div class="w-full aspect-square bg-gray-200 mb-3 overflow-hidden">
                            <img src="https://placehold.co/500x500/333333/ffffff?text=Asepi" alt="Asepi"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-sm md:text-base text-black font-roboto">Asepi</h4>
                        <p class="text-xs text-gray-500 font-roboto">Padang</p>
                    </div>

                    <div class="group cursor-pointer" onclick="openArtistProfile('Asepu', 'Jakarta')">
                        <div class="w-full aspect-square bg-gray-200 mb-3 overflow-hidden">
                            <img src="https://placehold.co/500x500/3d3d3d/ffffff?text=Asepu" alt="Asepu"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-sm md:text-base text-black font-roboto">Asepu</h4>
                        <p class="text-xs text-gray-500 font-roboto">Jakarta</p>
                    </div>

                    <div class="group cursor-pointer" onclick="openArtistProfile('Asepa', 'Kebumen')">
                        <div class="w-full aspect-square bg-gray-200 mb-3 overflow-hidden">
                            <img src="https://placehold.co/500x500/444444/ffffff?text=Asepa" alt="Asepa"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-sm md:text-base text-black font-roboto">Asepa</h4>
                        <p class="text-xs text-gray-500 font-roboto">Kebumen</p>
                    </div>

                    <div class="group cursor-pointer" onclick="openArtistProfile('Asepit', 'Lumajang')">
                        <div class="w-full aspect-square bg-gray-200 mb-3 overflow-hidden">
                            <img src="https://placehold.co/500x500/4d4d4d/ffffff?text=Asepit" alt="Asepit"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-sm md:text-base text-black font-roboto">Asepit</h4>
                        <p class="text-xs text-gray-500 font-roboto">Lumajang</p>
                    </div>

                    <div class="group cursor-pointer" onclick="openArtistProfile('Asepon', 'Pekalongan')">
                        <div class="w-full aspect-square bg-gray-200 mb-3 overflow-hidden">
                            <img src="https://placehold.co/500x500/555555/ffffff?text=Asepon" alt="Asepon"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-sm md:text-base text-black font-roboto">Asepon</h4>
                        <p class="text-xs text-gray-500 font-roboto">Pekalongan</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 3B: ARTIST PROFILE PAGE -->
        <section id="view-artist-detail" class="view-page px-6 md:px-12 pt-24 pb-10 max-w-7xl mx-auto">
            <div class="mb-6">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO ARTISTS</span>
                </button>
            </div>

            <!-- Artist Header Bio -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start mb-10">
                <div class="md:col-span-4 aspect-[4/5] bg-gray-200 overflow-hidden">
                    <img src="https://placehold.co/600x750/333333/ffffff?text=Mustafa+Alatas" alt="Mustafa Alatas"
                        class="w-full h-full object-cover grayscale">
                </div>

                <div class="md:col-span-8 space-y-3">
                    <h1 id="artist-profile-name" class="text-2xl md:text-3xl font-bold font-montserrat text-black">
                        Mustafa Alatas</h1>
                    <p id="artist-profile-location" class="text-xs md:text-sm text-gray-400 font-roboto">Temanggung</p>
                    <p class="text-xs md:text-sm text-gray-700 leading-relaxed font-roboto pt-2">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                        voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                        non proident, sunt in culpa qui officia deserunt mollit anim id est laborum...
                    </p>
                </div>
            </div>

            <!-- Tab Switcher (Artwork vs Editorial) -->
            <div class="border-b border-gray-200 mb-8 flex space-x-6 text-xs md:text-sm">
                <button id="tab-btn-artwork" onclick="switchArtistTab('artwork')"
                    class="pb-2 font-regular text-gray-400 hover:text-black transition-colors focus:outline-none cursor-pointer font-roboto">
                    Artwork
                </button>
                <button id="tab-btn-editorial" onclick="switchArtistTab('editorial')"
                    class="pb-2 font-regular text-black border-b border-black transition-colors focus:outline-none cursor-pointer font-roboto">
                    Editorial
                </button>
            </div>

            <!-- TAB CONTENT 1: EDITORIAL ARTICLES -->
            <div id="artist-tab-content-editorial" class="space-y-6">
                <h3 class="text-sm md:text-base font-bold font-montserrat text-black mb-6" id="editorial-section-title">
                    Articles Featuring Mustafa Alatas
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div onclick="openArtistEditorialDetail('Mereka Ulang Kerumitan Ungkapan Indah')"
                        class="group cursor-pointer">
                        <div class="w-full aspect-square bg-[#d9d9d9] mb-3 overflow-hidden">
                            <img src="https://placehold.co/600x600/d9d9d9/555555?text=Editorial+Article" alt="Article"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-xs md:text-sm text-black font-montserrat group-hover:underline">
                            Mereka Ulang Kerumitan Ungkapan Indah
                        </h4>
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT 2: ARTWORK GALLERY -->
            <div id="artist-tab-content-artwork" class="hidden space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div onclick="openArtistArtworkDetail('Mereka Ulang Ungkapan Indah Leila', 'Oil on canvas', '60x60cm')"
                        class="group cursor-pointer">
                        <div class="w-full aspect-[3/4] bg-[#d9d9d9] mb-3 overflow-hidden">
                            <img src="https://placehold.co/600x800/d9d9d9/555555?text=Artwork+Leila" alt="Artwork Leila"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-xs md:text-sm text-black font-montserrat group-hover:underline">
                            Mereka Ulang Ungkapan Indah Leila
                        </h4>
                    </div>

                    <div onclick="openArtistArtworkDetail('Mereka Ulang Ungkapan Indah Effendi', 'Oil on canvas', '60x60cm')"
                        class="group cursor-pointer">
                        <div class="w-full aspect-[3/4] bg-[#d9d9d9] mb-3 overflow-hidden">
                            <img src="https://placehold.co/600x800/ccc/333?text=Artwork+Effendi" alt="Artwork Effendi"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <h4 class="font-bold text-xs md:text-sm text-black font-montserrat group-hover:underline">
                            Mereka Ulang Ungkapan Indah Effendi
                        </h4>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 3C: ARTWORK DETAIL PAGE -->
        <section id="view-artist-artwork-detail" class="view-page px-6 md:px-12 pt-24 pb-10 max-w-7xl mx-auto">
            <div class="mb-6">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO ARTIST PROFILE</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start">
                <div class="md:col-span-7 aspect-[4/3] bg-[#d9d9d9] overflow-hidden">
                    <img src="https://placehold.co/1000x750/d9d9d9/555555?text=Full+Artwork+View" alt="Full Artwork"
                        class="w-full h-full object-cover">
                </div>

                <div class="md:col-span-5 space-y-4">
                    <h2 id="artwork-artist-name" class="text-xl md:text-2xl font-bold font-montserrat text-black">
                        Mustafa Alatas</h2>
                    <h3 id="artwork-title" class="text-base md:text-lg italic font-roboto text-black">Mereka Ulang
                        Ungkapan Indah Leila</h3>

                    <div class="text-xs md:text-sm text-gray-700 font-roboto space-y-1">
                        <p id="artwork-medium">Oil on canvas</p>
                        <p id="artwork-dimensions">60x60cm</p>
                    </div>

                    <p class="text-xs md:text-sm text-gray-700 leading-relaxed font-roboto pt-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                        voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                        non proident, sunt in culpa qui officia deserunt mollit anim id est laborum...
                    </p>
                </div>
            </div>
        </section>

        <!-- VIEW 3D: EDITORIAL ARTICLE DETAIL PAGE -->
        <section id="view-artist-editorial-detail" class="view-page px-6 md:px-12 pt-24 pb-10 max-w-7xl mx-auto">
            <div class="mb-6">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO EDITORIALS</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start">
                <!-- Left Column: Article Text & Captioned Images -->
                <div class="md:col-span-6 space-y-6">
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold font-montserrat text-black leading-snug">
                            Mustafa Alatas Menafsir Kuasa dan Sosial dalam Lukisan Figuratif dan Simbolisme
                        </h1>
                        <p class="text-xs text-gray-500 font-roboto mt-2">Laksa Dawantara</p>
                    </div>

                    <p class="text-xs md:text-sm text-gray-700 leading-relaxed font-roboto">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                        voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                        non proident, sunt in culpa qui officia deserunt mollit anim id est laborum...
                    </p>

                    <div class="grid grid-cols-2 gap-4 my-6">
                        <div>
                            <div class="w-full aspect-[3/4] bg-[#d9d9d9] overflow-hidden mb-1.5">
                                <img src="https://placehold.co/400x500/222/fff?text=Portrait" alt="Portrait"
                                    class="w-full h-full object-cover">
                            </div>
                            <p class="text-[10px] text-gray-400 font-roboto">Portrait of Mustafa Alatas. Photo by ......
                            </p>
                        </div>
                        <div>
                            <div class="w-full aspect-[3/2] bg-[#d9d9d9] overflow-hidden mb-1.5">
                                <img src="https://placehold.co/400x300/444/fff?text=Artwork" alt="Artwork"
                                    class="w-full h-full object-cover">
                            </div>
                            <p class="text-[10px] text-gray-400 font-roboto">Raja Diamuk Massa, 2024. Photo by .... .
                                Courtesy of Medium.</p>
                        </div>
                    </div>

                    <p class="text-xs md:text-sm text-gray-700 leading-relaxed font-roboto">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                        voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                        non proident, sunt in culpa qui officia deserunt mollit anim id est laborum...
                    </p>
                </div>

                <!-- Right Column: Full Featured Image -->
                <div class="md:col-span-6 aspect-[4/5] bg-[#d9d9d9] overflow-hidden">
                    <img src="https://placehold.co/800x1000/d9d9d9/555555?text=Featured+Editorial+Image"
                        alt="Editorial Feature" class="w-full h-full object-cover">
                </div>
            </div>
        </section>

        <!-- VIEW 4: LOOKBOOK INDEX PAGE (MINIMALIST TIMELINE DESAIN SESUAI REFERENSI) -->
        <section id="view-lookbook-list"
            class="view-page min-h-[75vh] px-8 sm:px-16 md:px-24 py-16 md:py-24 max-w-5xl mx-auto flex flex-col justify-center">
            <div class="relative pl-8 md:pl-12 my-auto">
                <!-- Vertical Timeline Line -->
                <div class="absolute left-0 top-3 bottom-3 w-[1.5px] bg-gray-300"></div>

                <!-- Timeline Entries -->
                <div class="space-y-12 md:space-y-16">
                    <!-- Timeline Item 1: 2025 LOOKBOOK -->
                    <div onclick="showPage('view-lookbook-detail')"
                        class="group relative flex items-center cursor-pointer select-none">
                        <!-- Round Dot Node sitting directly on the line -->
                        <div
                            class="absolute -left-[32px] md:-left-[48px] w-3.5 h-3.5 md:w-4 md:h-4 rounded-full bg-gray-400 border-2 border-white group-hover:bg-black group-hover:scale-150 transition-all duration-300 shadow-sm">
                        </div>

                        <!-- Lookbook Text -->
                        <span
                            class="text-xl sm:text-2xl md:text-3xl font-light tracking-[0.2em] text-black uppercase font-montserrat group-hover:translate-x-4 transition-all duration-300">
                            2025 LOOKBOOK
                        </span>
                    </div>

                    <!-- Timeline Item 2: 2024 LOOKBOOK -->
                    <div onclick="showPage('view-lookbook-detail')"
                        class="group relative flex items-center cursor-pointer select-none">
                        <!-- Round Dot Node -->
                        <div
                            class="absolute -left-[32px] md:-left-[48px] w-3.5 h-3.5 md:w-4 md:h-4 rounded-full bg-gray-300 border-2 border-white group-hover:bg-black group-hover:scale-150 transition-all duration-300 shadow-sm">
                        </div>

                        <!-- Lookbook Text -->
                        <span
                            class="text-xl sm:text-2xl md:text-3xl font-light tracking-[0.2em] text-gray-400 uppercase font-montserrat group-hover:text-black group-hover:translate-x-4 transition-all duration-300">
                            2024 LOOKBOOK
                        </span>
                    </div>

                    <!-- Timeline Item 3: 2023 LOOKBOOK -->
                    <div onclick="showPage('view-lookbook-detail')"
                        class="group relative flex items-center cursor-pointer select-none">
                        <!-- Round Dot Node -->
                        <div
                            class="absolute -left-[32px] md:-left-[48px] w-3.5 h-3.5 md:w-4 md:h-4 rounded-full bg-gray-300 border-2 border-white group-hover:bg-black group-hover:scale-150 transition-all duration-300 shadow-sm">
                        </div>

                        <!-- Lookbook Text -->
                        <span
                            class="text-xl sm:text-2xl md:text-3xl font-light tracking-[0.2em] text-gray-400 uppercase font-montserrat group-hover:text-black group-hover:translate-x-4 transition-all duration-300">
                            2023 LOOKBOOK
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 5: LOOKBOOK 2025 DETAIL PAGE -->
        <section id="view-lookbook-detail" class="view-page min-h-screen pt-24 pb-20">
            <div class="max-w-7xl mx-auto px-6 md:px-12 pt-6 pb-4">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO LOOKBOOKS</span>
                </button>
            </div>

            <div class="w-full bg-[#A29892] py-16 md:py-24 mb-12 flex items-center justify-center shadow-inner">
                <h2 class="text-xl md:text-2xl font-normal tracking-[0.25em] text-black uppercase font-montserrat">2025
                    LOOKBOOK</h2>
            </div>

            <div class="px-6 md:px-10 max-w-7xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 md:gap-12">
                    @foreach($fotoshoots as $foto)
                        <div class="w-full group cursor-pointer">
                            <div class="overflow-hidden bg-[#d9d9d9] relative">
                                <img src="{{ asset($foto->image) }}" alt="{{ $foto->title }}"
                                    class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                @if($foto->title)
                                    <div
                                        class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span
                                            class="text-white font-montserrat tracking-[0.2em] uppercase text-sm md:text-base">{{ $foto->title }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- VIEW 6: ABOUT BRAND PAGE -->
        <section id="view-about"
            class="view-page min-h-[calc(100vh-80px)] flex flex-col px-6 md:px-12 pt-28 pb-12 relative">
            <div class="mb-6 absolute top-28 left-6 md:left-12">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>
            <div class="flex-grow flex flex-col justify-center items-center">
            <div class="max-w-3xl mx-auto text-center space-y-4 md:space-y-6">
                <p
                    class="text-black text-xl sm:text-2xl md:text-3xl font-light leading-relaxed tracking-wide font-roboto">
                    Notisse is an alternative movement in art exhibition
                </p>
                <p
                    class="text-black text-xl sm:text-2xl md:text-3xl font-light leading-relaxed tracking-wide font-roboto">
                    woven into the fabric of everyday life
                </p>
                <p
                    class="text-black text-xl sm:text-2xl md:text-3xl font-light leading-relaxed tracking-wide font-roboto">
                    so art becomes part of daily activity not a separate occasion.
                </p>
            </div>
            </div>
        </section>

        <!-- VIEW 6B: CONTACT PAGE -->
        <section id="view-contact"
            class="view-page px-8 sm:px-16 md:px-24 pt-28 pb-16 md:pb-24 max-w-5xl mx-auto min-h-[calc(100vh-80px)] flex flex-col relative">
            <div class="mb-6 absolute top-28 left-8 sm:left-16 md:left-24">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>
            <div class="flex-grow flex flex-col justify-center">
            <div class="space-y-12 text-left font-roboto">
                <!-- Workshop Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-light text-black tracking-tight">Workshop :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide">
                        Jl Mangga Dua, Desa Sukapura, DayeuhKolot, Bandung.
                    </p>
                </div>

                <!-- Email Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-light text-black tracking-tight">Email :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide">
                        notissebrand@gmail.com
                    </p>
                </div>

                <!-- Whatsapp Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-light text-black tracking-tight">Whatsapp :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide">
                        +62 856 0771 4704
                    </p>
                </div>
                </div>
            </div>
            </div>
        </section>
        <!-- VIEW 7: LOGIN PAGE -->
        <section id="view-login" class="view-page px-6 pt-28 pb-12 max-w-md mx-auto relative">
            <div class="mb-6 absolute top-28 left-6 md:-left-12">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('logowebkecil.png') }}" alt="Notisse Logo" class="h-12 w-auto object-contain">
                </div>
                <h2 class="text-2xl font-bold font-montserrat uppercase tracking-wider text-black">Login Account</h2>
                <p class="text-xs text-gray-500 font-roboto mt-1">Masuk untuk mengakses koleksi dan pesanan Anda.</p>
            </div>

            <form onsubmit="handleLoginSubmit(event)" class="space-y-4">
                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1 font-montserrat">Email</label>
                    <input type="email" required placeholder="nama@email.com"
                        class="w-full border border-black rounded-lg p-3 text-sm focus:outline-none font-roboto">
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1 font-montserrat">Password</label>
                    <input type="password" required placeholder="••••••••"
                        class="w-full border border-black rounded-lg p-3 text-sm focus:outline-none font-roboto">
                </div>

                <div class="flex items-center justify-between text-xs font-roboto pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="accent-black">
                        <span class="text-gray-600">Ingat saya</span>
                    </label>
                    <a href="javascript:void(0)" onclick="showToast('Link reset password telah dikirim ke email.')"
                        class="underline text-gray-500 hover:text-black">Lupa password?</a>
                </div>

                <div class="pt-4 flex justify-center">
                    <button type="submit" class="btn-brush w-full text-sm uppercase cursor-pointer font-montserrat">
                        LOG IN
                    </button>
                </div>

                <div class="text-center pt-4">
                    <p class="text-xs text-gray-500 font-roboto">
                        Belum memiliki akun?
                        <a href="javascript:void(0)" onclick="showPage('view-register')"
                            class="font-bold underline text-black">Daftar sekarang</a>
                    </p>
                </div>
            </form>
        </section>

        <!-- VIEW 8: REGISTER / DAFTAR AKUN PAGE -->
        <section id="view-register" class="view-page px-6 pt-28 pb-12 max-w-md mx-auto relative">
            <div class="mb-6 absolute top-28 left-6 md:-left-12">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>
            <div class="bg-white border border-black rounded-2xl p-8 sm:p-10 shadow-2xl space-y-6">
                <!-- Signature N/Notisse Logo Top Center -->
                <div class="flex justify-center">
                    <img src="{{ asset('logowebkecil.png') }}" alt="Notisse" class="h-12 w-auto object-contain">
                </div>

                <!-- Headline Tagline -->
                <h2 class="text-center font-montserrat text-lg sm:text-xl font-medium text-black leading-snug px-2">
                    Daftar untuk terlibat dalam proses kreatif para seniman
                </h2>

                <!-- Registration Form -->
                <form onsubmit="handleRegisterSubmit(event)" class="space-y-4 pt-2">
                    <div>
                        <input type="text" required placeholder="Nama Lengkap"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-roboto placeholder-gray-400">
                    </div>

                    <div>
                        <input type="email" required placeholder="Email"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-roboto placeholder-gray-400">
                    </div>

                    <div>
                        <input type="password" required placeholder="Password"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-roboto placeholder-gray-400">
                    </div>

                    <div>
                        <input type="password" required placeholder="Konfirmasi Password"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-roboto placeholder-gray-400">
                    </div>

                    <div class="pt-4 flex justify-center">
                        <button type="submit" class="btn-brush w-full text-sm uppercase cursor-pointer font-montserrat">
                            Daftar
                        </button>
                    </div>
                </form>

                <div class="text-center pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-500 font-roboto">
                        Sudah punya akun?
                        <a href="javascript:void(0)" onclick="showPage('view-login')"
                            class="font-bold underline text-black">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </section>

        <!-- VIEW 9: LOGGED-IN ACCOUNT DASHBOARD -->
        <section id="view-account-profile" class="view-page px-6 md:px-12 pt-28 pb-12 max-w-5xl mx-auto space-y-8">
            <div class="mb-4">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>
            <div class="flex justify-between items-center border-b border-gray-200 pb-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold font-montserrat uppercase text-black">Akun Saya</h1>
                    <p id="user-display-email" class="text-xs text-gray-500 font-roboto mt-1">user@notisse.com</p>
                </div>
                <button onclick="handleLogout()"
                    class="text-xs font-semibold uppercase tracking-wider text-red-600 hover:underline cursor-pointer font-montserrat">
                    Logout
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Order History Summary -->
                <div class="md:col-span-2 space-y-4">
                    <h3 class="text-base font-bold font-montserrat text-black uppercase tracking-wider">Riwayat Pesanan
                    </h3>
                    <div id="order-history-container" class="space-y-4">
                        <div class="border border-gray-200 rounded-lg p-6 space-y-4 bg-gray-50 text-center">
                            <p class="text-gray-500 font-roboto text-sm">Memuat pesanan...</p>
                        </div>
                    </div>
                </div>

                <!-- Account Address Details -->
                <div class="space-y-4">
                    <h3 class="text-base font-bold font-montserrat text-black uppercase tracking-wider">Alamat
                        Pengiriman</h3>
                    <div
                        class="border border-gray-200 rounded-lg p-6 text-xs font-roboto space-y-2 leading-relaxed bg-white">
                        <p class="font-bold text-black text-sm font-montserrat" id="user-display-name">John Doe</p>
                        <p class="text-gray-600">Jl. Dago Asri No. 12, Coblong</p>
                        <p class="text-gray-600">Kota Bandung, Jawa Barat 40135</p>
                        <p class="text-gray-600">Indonesia</p>
                        <div class="pt-2">
                            <button onclick="showToast('Fitur ubah alamat segera hadir.')"
                                class="underline text-black font-semibold cursor-pointer">
                                Edit Alamat
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- RUANG KOSONG AGAR BISA DI-SCROLL KE BAWAH -->
    <div class="h-[150vh] w-full bg-white"></div>

    <!-- MARQUEE TEXT GLOBAL -->
    <div
        class="fixed bottom-0 left-0 z-[100] w-full border-t border-black overflow-hidden flex whitespace-nowrap py-2 md:py-2.5 bg-black shadow-lg">
        <div
            class="animate-marquee inline-block font-montserrat font-bold text-xs md:text-sm tracking-[0.25em] text-white">
            NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span>&nbsp;
        </div>
        <div
            class="animate-marquee inline-block font-montserrat font-bold text-xs md:text-sm tracking-[0.25em] text-white">
            NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span> NOTISSE<sup class="text-[8px] md:text-[10px] font-normal tracking-normal text-gray-400">&reg;</sup> <span class="mx-3 md:mx-5 text-gray-500 font-light">///</span>&nbsp;
        </div>
    </div>
    <!-- JAVASCRIPT CONTROLLER -->
    <script>
        // MOCK DATABASE PRODUK KONTEN BRAND
        const productsData = [
            { id: 1, name: "Raja Diamuk Massa Tee V1", price: "Rp 250.000,00", image: "https://placehold.co/600x800/d9d9d9/555555?text=Raja+Diamuk+Tee", artist: "Mustafa Alatas" },
            { id: 2, name: "Muscle Tank V2 - Black", price: "Rp 180.000,00", image: "https://placehold.co/600x800/181818/ffffff?text=Muscle+Tank+Black", artist: "Asep" },
            { id: 3, name: "Muscle Tank V2 - White", price: "Rp 180.000,00", image: "https://placehold.co/600x800/ffffff/111111?text=Muscle+Tank+White", artist: "Asep" },
            { id: 4, name: "Relaxed Tailored Shirt V1", price: "Rp 320.000,00", image: "https://placehold.co/600x800/f0f0f0/222222?text=Tailored+Shirt", artist: "Asepo" },
            { id: 5, name: "Accessorized Slim Tee V2 - Black", price: "Rp 220.000,00", image: "https://placehold.co/600x800/111111/ffffff?text=Slim+Tee+Black", artist: "Mustafa Alatas" },
            { id: 6, name: "Rugged Long Sleeve Tee V2 - Gray", price: "Rp 280.000,00", image: "https://placehold.co/600x800/888888/ffffff?text=Long+Sleeve+Gray", artist: "Asepi" },
            { id: 7, name: "Sweatshirt - Gray", price: "Rp 350.000,00", image: "https://placehold.co/600x800/cccccc/333333?text=Sweatshirt+Gray", artist: "Asepu" },
            { id: 8, name: "Pleated Denim V1", price: "Rp 450.000,00", image: "https://placehold.co/600x800/1e293b/ffffff?text=Pleated+Denim", artist: "Asepa" },
            { id: 9, name: "Collarless Leather Blazer", price: "Rp 650.000,00", image: "https://placehold.co/600x800/262626/ffffff?text=Leather+Blazer", artist: "Asepon" }
        ];

        let currentSelectedSize = "M";
        let currentSlideIndex = 0;
        let slideInterval = null;

        // Interactive Logo and Menus on Scroll
        window.addEventListener('scroll', () => {
            const logo = document.getElementById('header-logo');
            const menuLeft = document.getElementById('menu-trigger');
            const cartTrigger = document.getElementById('cart-trigger');

            if (window.scrollY > 50) {
                if (logo) logo.src = "{{ asset('logowebkecil.png') }}";
                if (menuLeft) {
                    menuLeft.classList.remove('opacity-0', 'pointer-events-none');
                    menuLeft.classList.add('opacity-100', 'pointer-events-auto');
                }
                if (cartTrigger) {
                    cartTrigger.classList.add('is-scrolled');
                }
            } else {
                if (logo) logo.src = "{{ asset('logo.png') }}";
                if (menuLeft) {
                    menuLeft.classList.remove('opacity-100', 'pointer-events-auto');
                    menuLeft.classList.add('opacity-0', 'pointer-events-none');
                }
                if (cartTrigger) {
                    cartTrigger.classList.remove('is-scrolled');
                }
            }
        });

        let navigationHistory = ['view-shop'];
        let isAuthenticated = localStorage.getItem('notisse_auth') === 'true';
        let loggedInUser = JSON.parse(localStorage.getItem('notisse_user')) || {
            name: "John Doe",
            email: "john.doe@notisse.com"
        };

        let cartState = [
            { id: 1, name: "RAJA DIAMUK MASSA TEE V1", size: "M", unitPrice: 250000, qty: 1 },
            { id: 2, name: "RAJA DIAMUK MASSA TEE V1", size: "L", unitPrice: 250000, qty: 1 }
        ];

        const overlayMenuBackdrop = document.getElementById('overlay-menu-backdrop');
        const overlayMenuSidebar = document.getElementById('overlay-menu-sidebar');
        const overlaySearch = document.getElementById('overlay-search');
        const overlayCart = document.getElementById('overlay-cart');
        const cartDrawerContent = document.getElementById('cart-drawer-content');

        const menuTrigger = document.getElementById('menu-trigger');
        const menuClose = document.getElementById('menu-close');
        const searchTrigger = document.getElementById('search-trigger');
        const searchClose = document.getElementById('search-close');
        const cartTrigger = document.getElementById('cart-trigger');
        const cartClose = document.getElementById('cart-close');

        function showPage(pageId, isBacking = false) {
            const activePage = document.querySelector('.view-page.active-view');
            const currentActiveId = activePage ? activePage.id : null;

            if (!isBacking && currentActiveId && currentActiveId !== pageId) {
                navigationHistory.push(currentActiveId);
            }

            document.querySelectorAll('.view-page').forEach(page => page.classList.remove('active-view'));
            const targetPage = document.getElementById(pageId);
            if (targetPage) targetPage.classList.add('active-view');
            closeSizeDropdown();
            window.scrollTo({ top: 0, behavior: 'smooth' });
            sessionStorage.setItem('current_page', pageId);
        }

        function goBack() {
            if (navigationHistory.length > 0) {
                const previousPageId = navigationHistory.pop();
                showPage(previousPageId, true);
            } else {
                showPage('view-shop', true);
            }
        }

        function navigateTo(pageId) {
            closeMenu();
            showPage(pageId);
        }

        /* ACCOUNT FUNCTIONALITIES */
        function handleAccountButtonClick() {
            if (isAuthenticated) {
                showPage('view-account-profile');
                fetchOrderHistory();
            } else {
                showPage('view-login');
            }
        }

        async function fetchOrderHistory() {
            try {
                const response = await fetch('/my-orders');
                const orders = await response.json();
                
                const container = document.getElementById('order-history-container');
                container.innerHTML = '';
                
                if (orders.length === 0) {
                    container.innerHTML = `
                        <div class="border border-gray-200 rounded-lg p-6 space-y-4 bg-gray-50 text-center">
                            <p class="text-gray-500 font-roboto text-sm">Belum ada pesanan terbaru.</p>
                        </div>
                    `;
                    return;
                }
                
                orders.forEach(order => {
                    let itemsHtml = '';
                    order.items.forEach(item => {
                        itemsHtml += `
                        <div class="flex items-center space-x-4 mt-4">
                            <div class="w-12 h-16 bg-gray-300 flex-shrink-0">
                                <img src="https://placehold.co/200x250/d9d9d9/555555?text=Tee" class="w-full h-full object-cover">
                            </div>
                            <div class="text-xs font-roboto">
                                <p class="font-bold text-black font-montserrat">${item.product_name}</p>
                                <p class="text-gray-500">Ukuran: ${item.size} | Jumlah: ${item.quantity}</p>
                                <p class="font-semibold text-black mt-1">Rp ${parseInt(item.price).toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                        `;
                    });

                    let statusClass = "bg-black text-white";
                    let cancelButton = '';
                    if (order.status.toLowerCase() === 'paid') {
                        statusClass = "bg-green-600 text-white";
                    } else if (order.status.toLowerCase() === 'pending') {
                        statusClass = "bg-yellow-500 text-black";
                        cancelButton = `<button onclick="cancelOrder(${order.id})" class="text-[10px] text-red-600 font-bold uppercase tracking-wider hover:underline font-montserrat mt-2">Batalkan</button>`;
                    } else if (order.status.toLowerCase() === 'cancelled') {
                        statusClass = "bg-red-600 text-white";
                    }

                    container.innerHTML += `
                    <div class="border border-gray-200 rounded-lg p-6 space-y-4 bg-gray-50">
                        <div class="flex justify-between items-start text-xs font-roboto pb-3 border-b border-gray-200">
                            <div>
                                <p class="font-bold text-black font-montserrat">ORDER #NTS-${order.id}</p>
                                <p class="text-gray-400">Dibuat pada ${new Date(order.created_at).toLocaleDateString('id-ID')}</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="px-2.5 py-1 ${statusClass} font-bold rounded text-[10px] tracking-wider uppercase font-montserrat">${order.status}</span>
                                ${cancelButton}
                            </div>
                        </div>
                        ${itemsHtml}
                    </div>
                    `;
                });
            } catch(e) {
                console.error(e);
            }
        }

        async function cancelOrder(id) {
            if (!confirm("Apakah Anda yakin ingin membatalkan pesanan ini?")) return;
            try {
                const response = await fetch(`/my-orders/${id}/cancel`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });
                if (response.ok) {
                    showToast("Pesanan berhasil dibatalkan.");
                    fetchOrderHistory();
                } else {
                    showToast("Gagal membatalkan pesanan.");
                }
            } catch (e) {
                console.error(e);
                showToast("Terjadi kesalahan sistem.");
            }
        }

        function handleLoginSubmit(e) {
            e.preventDefault();
            isAuthenticated = true;
            localStorage.setItem('notisse_auth', 'true');
            localStorage.setItem('notisse_user', JSON.stringify(loggedInUser));
            document.getElementById('user-display-email').innerText = loggedInUser.email;
            document.getElementById('user-display-name').innerText = loggedInUser.name;
            showToast('Berhasil Login!');
            showPage('view-account-profile');
            fetchOrderHistory();
        }

        function handleRegisterSubmit(e) {
            e.preventDefault();
            isAuthenticated = true;
            localStorage.setItem('notisse_auth', 'true');
            localStorage.setItem('notisse_user', JSON.stringify(loggedInUser));
            showToast('Akun Berhasil Dibuat!');
            showPage('view-account-profile');
            fetchOrderHistory();
        }

        function handleLogout() {
            isAuthenticated = false;
            localStorage.removeItem('notisse_auth');
            localStorage.removeItem('notisse_user');
            showToast('Anda telah logout.');
            showPage('view-shop');
        }

        function openMenu() {
            overlayMenuBackdrop.classList.remove('hidden');
            setTimeout(() => {
                overlayMenuBackdrop.classList.remove('opacity-0');
                overlayMenuSidebar.classList.remove('-translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            overlayMenuSidebar.classList.add('-translate-x-full');
            overlayMenuBackdrop.classList.add('opacity-0');
            setTimeout(() => {
                overlayMenuBackdrop.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        menuTrigger.addEventListener('click', openMenu);
        menuClose.addEventListener('click', closeMenu);

        /* LOGIKA REAL-TIME / INSTANT LIVE SEARCH */
        function openSearch() {
            overlaySearch.classList.remove('-translate-y-full');
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 150);
            }
        }

        function closeSearch() {
            overlaySearch.classList.add('-translate-y-full');
        }

        searchTrigger.addEventListener('click', openSearch);
        searchClose.addEventListener('click', closeSearch);

        function handleLiveSearch(query) {
            const gridContainer = document.getElementById('search-results-grid');
            const metaContainer = document.getElementById('search-results-meta');
            const countSpan = document.getElementById('search-results-count');

            const cleanQuery = query.trim().toLowerCase();

            if (cleanQuery === "") {
                gridContainer.innerHTML = "";
                metaContainer.classList.add('hidden');
                return;
            }

            // Filter produk berdasarkan nama produk atau nama seniman
            const filteredProducts = productsData.filter(item => {
                return item.name.toLowerCase().includes(cleanQuery) || item.artist.toLowerCase().includes(cleanQuery);
            });

            countSpan.innerText = filteredProducts.length;
            metaContainer.classList.remove('hidden');

            if (filteredProducts.length === 0) {
                gridContainer.innerHTML = `
                    <div class="col-span-full text-center py-12 text-gray-400 font-roboto text-sm">
                        No products found matching "<span class="text-black font-semibold">${query}</span>".
                    </div>
                `;
                return;
            }

            // Render daftar produk secara instan ke grid
            let html = '';
            filteredProducts.forEach(item => {
                html += `
                    <div onclick="selectSearchResult('${item.name}', '${item.price}')" class="group cursor-pointer">
                        <div class="w-full aspect-[3/4] bg-[#d9d9d9] mb-2 overflow-hidden border border-gray-100">
                            <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <h4 class="font-montserrat font-semibold text-xs md:text-sm text-black group-hover:underline truncate">${item.name}</h4>
                        <p class="font-montserrat text-xs text-gray-500">${item.price}</p>
                    </div>
                `;
            });

            gridContainer.innerHTML = html;
        }

        function selectSearchResult(name, price) {
            closeSearch();
            openProductDetail(name, price);
        }

        /* CART DRAWER LOGIC */
        function openCart() {
            renderCartItems();
            overlayCart.classList.remove('hidden');
            setTimeout(() => {
                overlayCart.classList.remove('opacity-0');
                cartDrawerContent.classList.remove('translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeCart() {
            cartDrawerContent.classList.add('translate-x-full');
            overlayCart.classList.add('opacity-0');
            setTimeout(() => {
                overlayCart.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        cartTrigger.addEventListener('click', openCart);
        cartClose.addEventListener('click', closeCart);

        function openProductDetail(title, price) {
            if (title) document.getElementById('pdetail-title').innerText = title;
            showPage('view-product-detail');
        }

        function openArtistProfile(name, location) {
            document.getElementById('artist-profile-name').innerText = name;
            document.getElementById('artist-profile-location').innerText = location;
            document.getElementById('editorial-section-title').innerText = "Articles Featuring " + name;
            switchArtistTab('editorial');
            showPage('view-artist-detail');
        }

        function switchArtistTab(tab) {
            const btnArtwork = document.getElementById('tab-btn-artwork');
            const btnEditorial = document.getElementById('tab-btn-editorial');
            const contentArtwork = document.getElementById('artist-tab-content-artwork');
            const contentEditorial = document.getElementById('artist-tab-content-editorial');

            if (tab === 'artwork') {
                btnArtwork.className = 'pb-2 font-regular text-black border-b border-black transition-colors focus:outline-none cursor-pointer font-roboto';
                btnEditorial.className = 'pb-2 font-regular text-gray-400 hover:text-black transition-colors focus:outline-none cursor-pointer font-roboto';
                contentArtwork.classList.remove('hidden');
                contentEditorial.classList.add('hidden');
            } else {
                btnEditorial.className = 'pb-2 font-regular text-black border-b border-black transition-colors focus:outline-none cursor-pointer font-roboto';
                btnArtwork.className = 'pb-2 font-regular text-gray-400 hover:text-black transition-colors focus:outline-none cursor-pointer font-roboto';
                contentEditorial.classList.remove('hidden');
                contentArtwork.classList.add('hidden');
            }
        }

        function openArtistArtworkDetail(title, medium, dimensions) {
            if (title) document.getElementById('artwork-title').innerText = title;
            if (medium) document.getElementById('artwork-medium').innerText = medium;
            if (dimensions) document.getElementById('artwork-dimensions').innerText = dimensions;
            showPage('view-artist-artwork-detail');
        }

        function openArtistEditorialDetail(title) {
            showPage('view-artist-editorial-detail');
        }

        function showToast(message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'bg-black text-white px-5 py-3 rounded text-xs font-montserrat uppercase tracking-wider shadow-xl transition-all duration-300 opacity-0 transform translate-y-2';
            toast.innerText = message;
            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-y-2');
            }, 10);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function updateCarouselUI() {
            const slidesContainer = document.getElementById('hero-carousel-slides');
            const dots = document.querySelectorAll('.hero-dot');
            if (slidesContainer) {
                slidesContainer.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
            }
            dots.forEach((dot, index) => {
                dot.style.opacity = index === currentSlideIndex ? '1' : '0.4';
            });
        }

        function nextSlide() {
            currentSlideIndex = (currentSlideIndex + 1) % 3;
            updateCarouselUI();
        }

        function prevSlide() {
            currentSlideIndex = (currentSlideIndex - 1 + 3) % 3;
            updateCarouselUI();
        }

        function goToSlide(index) {
            currentSlideIndex = index;
            updateCarouselUI();
        }

        function startAutoSlide() {
            stopAutoSlide();
            slideInterval = setInterval(nextSlide, 2000);
        }

        function stopAutoSlide() {
            if (slideInterval) clearInterval(slideInterval);
        }

        function toggleSizeDropdown(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('size-dropdown-menu');
            if (menu) menu.classList.toggle('active');
        }

        function closeSizeDropdown() {
            const menu = document.getElementById('size-dropdown-menu');
            if (menu) menu.classList.remove('active');
        }

        function selectSize(code) {
            currentSelectedSize = code;
            document.getElementById('selected-size-label').innerText = "Select size: " + code;
            closeSizeDropdown();
        }

        function toggleAccordion(key) {
            const content = document.getElementById('acc-content-' + key);
            const icon = document.getElementById('acc-icon-' + key);
            if (content) content.classList.toggle('expanded');
            if (icon) icon.classList.toggle('rotated');
        }

        function formatRupiah(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID') + ',00';
        }

        function renderCartItems() {
            const container = document.getElementById('cart-items-container');
            const totalElement = document.getElementById('cart-total-price');
            const badgeElement = document.getElementById('header-cart-badge');
            let totalAmount = 0;
            let totalItemCount = 0;

            if (cartState.length === 0) {
                container.innerHTML = `<div class="text-center py-16 text-gray-400 text-sm font-roboto">Your cart is empty.</div>`;
            } else {
                let html = '';
                cartState.forEach((item, index) => {
                    const itemTotal = item.unitPrice * item.qty;
                    totalAmount += itemTotal;
                    totalItemCount += item.qty;
                    html += `
                        <div class="grid grid-cols-12 items-center text-xs md:text-sm py-2 border-b border-gray-100">
                            <div class="col-span-6 md:col-span-5 flex items-center space-x-3">
                                <div class="w-12 h-16 bg-gray-200 flex-shrink-0">
                                    <img src="https://placehold.co/200x250/d9d9d9/555555?text=Tee" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-semibold uppercase text-black text-xs md:text-sm font-montserrat">${item.name}</p>
                                    <p class="text-xs text-gray-500 font-roboto">Size: ${item.size}</p>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-3 flex justify-center">
                                <div class="flex items-center border border-gray-400">
                                    <button onclick="updateCartQty(${index}, -1)" class="px-2 py-1 hover:bg-gray-100 font-bold font-roboto">−</button>
                                    <span class="px-3 py-1 font-semibold font-roboto">${item.qty}</span>
                                    <button onclick="updateCartQty(${index}, 1)" class="px-2 py-1 hover:bg-gray-100 font-bold font-roboto">+</button>
                                </div>
                            </div>
                            <div class="hidden md:block md:col-span-2 text-right font-montserrat">${formatRupiah(item.unitPrice)}</div>
                            <div class="col-span-3 md:col-span-2 text-right font-medium font-montserrat">${formatRupiah(itemTotal)}</div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }

            totalElement.innerText = formatRupiah(totalAmount) + ' IDR';
            badgeElement.innerText = `${totalItemCount}`;
        }

        function updateCartQty(index, change) {
            if (cartState[index]) {
                cartState[index].qty += change;
                if (cartState[index].qty <= 0) cartState.splice(index, 1);
            }
            renderCartItems();
        }

        function addSelectedToCart() {
            cartState.push({
                id: Date.now(),
                name: 'RAJA DIAMUK MASSA TEE V1',
                size: currentSelectedSize || 'M',
                unitPrice: 250000,
                qty: 1
            });
            openCart();
            showToast('Added to cart!');
        }

        async function processCheckout() {
            if (cartState.length === 0) {
                showToast("Cart is empty!");
                return;
            }

            const btn = document.getElementById('checkout-btn');
            btn.innerText = "PROCESSING...";
            btn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const items = cartState.map(item => ({
                name: item.name,
                price: item.unitPrice,
                quantity: item.qty,
                size: item.size
            }));

            const payload = {
                customer_name: loggedInUser.name || "Guest User",
                customer_email: loggedInUser.email || "guest@example.com",
                customer_phone: "08123456789", // Mock
                shipping_address: "Jl. Dago Asri No. 12, Bandung", // Mock
                items: items,
                _token: csrfToken
            };

            try {
                const response = await fetch('/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                
                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: async function(result) {
                            showToast("Payment success!");
                            
                            // Hit API simulasi webhook untuk localhost
                            await fetch('/midtrans/local-success', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ order_id: result.order_id })
                            });
                            
                            cartState = [];
                            renderCartItems();
                        },
                        onPending: async function(result) {
                            showToast("Waiting your payment...");
                            
                            // Hit API simulasi webhook untuk localhost (bypass lunas otomatis)
                            await fetch('/midtrans/local-success', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ order_id: result.order_id })
                            });
                            
                            cartState = [];
                            renderCartItems();
                            showToast("Payment success (Simulated)!");
                        },
                        onError: function(result) {
                            showToast("Payment failed!");
                        },
                        onClose: function() {
                            showToast('You closed the popup without finishing the payment');
                        }
                    });
                } else {
                    showToast("Failed to create transaction.");
                }
            } catch (error) {
                console.error(error);
                showToast("Error processing checkout.");
            } finally {
                btn.innerText = "CHECKOUT";
                btn.disabled = false;
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const lastPage = sessionStorage.getItem('current_page') || 'view-shop';
            showPage(lastPage);
            if(lastPage === 'view-account-profile' && isAuthenticated) {
                fetchOrderHistory();
            }
            renderCartItems();
            startAutoSlide();
        });
    </script>
</body>

</html>