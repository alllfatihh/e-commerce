<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notisse - Fashion & Art Brand</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logowebkecil.png') }}" type="image/png">
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
                transform: translateX(-100%);
            }
        }

        .animate-marquee {
            animation: marquee 30s linear infinite;
            will-change: transform;
        }

        @media (max-width: 640px) {
            .animate-marquee {
                animation: marquee 24s linear infinite;
            }
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
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.35s ease;
            will-change: transform;
        }

        .overlay-backdrop {
            transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        #overlay-cart {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        #cart-drawer-content {
            transition: transform 0.48s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
        }

        /* A24 STAGGERED FADE-UP FOR CART DRAWER CONTENT */
        .cart-animated-item {
            animation: a24CartFadeUp 0.42s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes a24CartFadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* A24 HEADER CART BADGE BOUNCE POP */
        .cart-badge-pop {
            animation: a24BadgeBounce 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes a24BadgeBounce {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.4) translateY(-1px);
                color: #d52c2b;
            }
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
        /* A24 INSPIRED DUAL LOGO MORPH ON SCROLL */
        .header-logo-large {
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.45s cubic-bezier(0.16, 1, 0.3, 1),
                        filter 0.35s ease;
            will-change: opacity, transform;
            transform-origin: center center;
        }

        .header-logo-small {
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.45s cubic-bezier(0.16, 1, 0.3, 1),
                        filter 0.35s ease;
            will-change: opacity, transform;
            transform-origin: center center;
        }

        /* State: Large Wordmark (Top / Hero) */
        .logo-state-large .header-logo-large {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }
        .logo-state-large .header-logo-small {
            opacity: 0;
            transform: scale(0.65) rotate(-6deg);
            pointer-events: none;
        }

        /* State: Small Logo / Monogram (Scrolled) */
        .logo-state-small .header-logo-large {
            opacity: 0;
            transform: scale(0.85);
            pointer-events: none;
        }
        .logo-state-small .header-logo-small {
            opacity: 1;
            transform: scale(1) rotate(0deg);
            pointer-events: auto;
            animation: smallLogoPop 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes smallLogoPop {
            0% {
                opacity: 0;
                transform: scale(0.65) translateY(3px);
                filter: brightness(1.2) drop-shadow(0 4px 10px rgba(0,0,0,0.15));
            }
            60% {
                transform: scale(1.08) translateY(-1px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
                filter: brightness(1) drop-shadow(0 2px 5px rgba(0,0,0,0.06));
            }
        }

        .header-logo-container:hover .header-logo-large {
            transform: scale(1.04);
            filter: brightness(1.12);
        }

        .header-logo-container:hover .header-logo-small {
            transform: scale(1.1) rotate(2deg);
            filter: brightness(1.15) drop-shadow(0 3px 8px rgba(0,0,0,0.12));
        }

        /* A24 SMART HIDE / REVEAL HEADER ON SCROLL */
        #main-header {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                        background-color 0.35s ease,
                        border-color 0.35s ease,
                        box-shadow 0.35s ease;
            will-change: transform;
        }

        #main-header.header-hidden {
            transform: translateY(-100%) !important;
        }

        #main-header.header-visible {
            transform: translateY(0) !important;
        }

        #menu-trigger,
        #header-right-menu {
            transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        /* ========================================================== */
        /* EXACT A24 MORPHING CART BUTTON WITH MOVING CONNECTING LINE */
        /* ========================================================== */
        .header__link--cart {
            display: block;
            position: fixed;
            top: 27px; /* Fine-tuned vertical alignment with SEARCH */
            right: 28px;
            width: 72px;
            height: 38px;
            z-index: 55;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            cursor: pointer;
            color: #000000;
            transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                        width 0.32s cubic-bezier(0.16, 1, 0.3, 1),
                        height 0.32s cubic-bezier(0.16, 1, 0.3, 1);
            -webkit-tap-highlight-color: transparent;
        }

        @media (max-width: 768px) {
            .header__link--cart {
                top: 23px; /* Fine-tuned vertical alignment with SEARCH */
                right: 20px;
            }
        }
        
        @media (max-width: 640px) {
            .header__link--cart {
                top: 19px; /* Fine-tuned vertical alignment with SEARCH */
                right: 18px;
            }
        }

        .header__link--cart:hover {
            opacity: 0.65;
        }

        /* 1. THE MOVING / MORPHING CONNECTING LINE (L-SHAPE THAT SHRINKS & STRETCHES) */
        .header__link--cart::before {
            content: "";
            position: absolute;
            right: 0px;
            top: 7px;
            width: 22px;
            height: 16px;
            border-top: 1.2px solid currentColor;
            border-right: 1.2px solid currentColor;
            border-bottom: none;
            border-left: none;
            box-sizing: border-box;
            pointer-events: none;
            /* When returning (scrolling UP): vertical shrinks first (0s), horizontal opens after delay (0.12s) */
            transition: width 0.28s cubic-bezier(0.16, 1, 0.3, 1) 0.12s,
                        height 0.25s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        top 0.25s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        left 0.25s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        right 0.25s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        transform 0.25s cubic-bezier(0.16, 1, 0.3, 1) 0s;
            will-change: width, height, top, left, right, transform;
        }

        /* On hover in default horizontal state: line actively moves/stretches */
        .header__link--cart:not(.state-minimal):hover::before {
            width: 28px;
            transition-delay: 0s;
        }

        /* 2. DEFAULT HORIZONTAL LABEL */
        .header__link--cart__default-label {
            position: absolute;
            left: 0;
            top: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.16em;
            line-height: 16px;
            text-transform: uppercase;
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1) 0.1s,
                        transform 0.3s cubic-bezier(0.16, 1, 0.3, 1) 0.1s;
        }

        /* 3. VERTICAL LABEL (APPEARS ON SCROLL) */
        /* Reads top to bottom naturally with generous breathing room */
        .header__link--cart__vertical-label {
            position: absolute;
            top: 0px;
            left: 50%;
            transform: translateX(-50%) translateY(6px);
            width: auto;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.18em;
            line-height: 1;
            text-transform: uppercase;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        transform 0.2s cubic-bezier(0.16, 1, 0.3, 1) 0s;
        }

        /* 4. MONOSPACE COUNTER */
        .header__link--cart__counter {
            position: absolute;
            right: 0px;
            top: 25px;
            width: 14px;
            text-align: center;
            font-family: 'Courier New', Courier, Monaco, monospace;
            font-size: 11.5px;
            font-weight: 600;
            line-height: 1;
            transition: top 0.25s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        left 0.25s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        right 0.25s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        transform 0.25s cubic-bezier(0.16, 1, 0.3, 1) 0s;
        }

        /* ============================================================== */
        /* SCROLLED MINIMAL STATE (.state-minimal): MOVING LINE ANIMATION */
        /* ============================================================== */
        .header__link--cart.state-minimal {
            width: 24px;
            height: 118px;
        }

        /* Horizontal segment collapses to 0 immediately (0s); Vertical segment stretches down with generous spacing (0.12s) */
        /* Spacing: "CART" ends around top:44px, line starts at top:56px (12px gap!), height:32px (ends at 88px), counter at 98px (10px gap!) */
        .header__link--cart.state-minimal::before {
            width: 0px !important;
            height: 32px !important;
            top: 56px !important;
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
            border-top: none !important;
            border-right: 1.2px solid currentColor !important;
            transition: width 0.18s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        height 0.32s cubic-bezier(0.16, 1, 0.3, 1) 0.12s,
                        top 0.32s cubic-bezier(0.16, 1, 0.3, 1) 0.12s !important;
        }

        /* On hover in vertical state: vertical line stretches down smoothly */
        .header__link--cart.state-minimal:hover::before {
            height: 42px !important;
            transition: height 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0s !important;
        }

        .header__link--cart.state-minimal .header__link--cart__counter {
            top: 98px !important;
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
            width: auto !important;
            text-align: center !important;
            transition: top 0.32s cubic-bezier(0.16, 1, 0.3, 1) 0.12s !important;
        }

        .header__link--cart.state-minimal:hover .header__link--cart__counter {
            top: 108px !important;
            transition: top 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0s !important;
        }

        .header__link--cart.state-minimal .header__link--cart__default-label {
            opacity: 0 !important;
            pointer-events: none !important;
            transform: translateY(-6px) !important;
            transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        transform 0.2s cubic-bezier(0.16, 1, 0.3, 1) 0s !important;
        }

        .header__link--cart.state-minimal .header__link--cart__vertical-label {
            opacity: 1 !important;
            pointer-events: auto !important;
            top: 0px !important;
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) translateY(0) !important;
            transition: opacity 0.32s cubic-bezier(0.16, 1, 0.3, 1) 0.12s,
                        transform 0.32s cubic-bezier(0.16, 1, 0.3, 1) 0.12s !important;
        }

        /* Animated moving shimmer line in cart drawer */
        @keyframes movingLineSlide {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .moving-line-shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.9), transparent);
            animation: movingLineSlide 2.2s infinite ease-in-out;
        }

        @keyframes cartBadgePop {
            0% {
                transform: scale(1);
            }
            40% {
                transform: scale(1.35);
                color: #d52c2b;
            }
            100% {
                transform: scale(1);
            }
        }

        .cart-badge-pop {
            animation: cartBadgePop 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* OVERLAYS AND STACKING CONTEXTS (MENIMPA RUNNING TEXT FOOTER) */
        #global-marquee {
            z-index: 30 !important;
            color: #d52c2b !important;
        }

        #global-marquee * {
            color: #d52c2b !important;
        }

        #overlay-menu-backdrop,
        #overlay-cart {
            z-index: 900 !important;
        }

        #overlay-menu-sidebar,
        #cart-drawer-content {
            z-index: 910 !important;
        }

        #overlay-search {
            z-index: 950 !important;
        }

        #modal-artwork-offering {
            z-index: 960 !important;
        }

        #modal-image-lightbox {
            z-index: 970 !important;
        }

        #toast-container {
            z-index: 999 !important;
        }
    </style>
</head>

<body
    class="min-h-screen flex flex-col justify-between relative bg-white text-black antialiased selection:bg-black selection:text-white">

    <!-- GLOBAL PERSISTENT HEADER (A24-INSPIRED MINIMALIST LUXURY) -->
    <header id="main-header"
        class="fixed top-0 left-0 w-full z-50 bg-transparent px-5 sm:px-8 py-3.5 flex items-center justify-between transition-all duration-400 ease-[cubic-bezier(0.16,1,0.3,1)]">
        <!-- Left: Hamburger Icon -->
        <button id="menu-trigger" aria-label="Open Navigation Menu"
            class="p-2 -ml-2 text-black hover:opacity-60 hover:scale-105 active:scale-95 transition-all duration-300 cursor-pointer focus:outline-none flex items-center justify-center opacity-0 pointer-events-none -translate-x-3">
            <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
            </svg>
        </button>

        <!-- Center: Dual Animated Logo (Switches to Small Logo on Scroll) -->
        <div id="header-logo-container"
            class="header-logo-container logo-state-large absolute left-1/2 transform -translate-x-1/2 cursor-pointer flex items-center justify-center select-none py-1 h-7 sm:h-8"
            onclick="showPage('view-shop')" title="Back to Shop">
            <!-- Large Brand Wordmark -->
            <img id="header-logo-large" src="{{ asset('logo.png') }}" alt="Notisse Logo"
                class="header-logo-large h-5 sm:h-6 md:h-6.5 w-auto object-contain select-none">
            <!-- Small Brand Monogram / Icon -->
            <img id="header-logo-small" src="{{ asset('logowebkecil.png') }}" alt="Notisse Icon"
                class="header-logo-small absolute h-5 sm:h-5.5 md:h-6 w-auto object-contain select-none">
        </div>

        <!-- Right: SEARCH -->
        <div id="header-right-menu"
            class="flex items-center text-[11px] font-medium tracking-[0.16em] uppercase transition-all duration-300 opacity-0 pointer-events-none translate-x-3 pr-20 md:pr-24">
            <button id="search-trigger" aria-label="Search"
                class="font-montserrat hover:opacity-50 hover:scale-105 active:scale-95 transition-all focus:outline-none py-1 px-1 cursor-pointer">
                SEARCH
            </button>
        </div>
    </header>

    <!-- EXACT A24 UNIFIED MORPHING CART TRIGGER (WITH VISIBLE MOVING LINE ANIMATION) -->
    <button id="cart-trigger" aria-label="Cart" onclick="openCart()"
        class="header__link--cart focus:outline-none select-none cursor-pointer">
        <span class="header__link--cart__default-label">CART</span>
        <span class="header__link--cart__vertical-label">CART</span>
        <span id="header-cart-badge" class="header__link--cart__counter font-bold">2</span>
    </button>

    <!-- OVERLAY 1: 1/4 SCREEN WIDTH NAVIGATION SIDEBAR -->
    <div id="overlay-menu-backdrop"
        class="fixed inset-0 bg-black/50 z-[60] hidden opacity-0 overlay-backdrop transition-opacity duration-300"
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

            <div class="flex items-center space-x-6 sm:space-x-8 font-roboto mb-2">
                <a href="javascript:void(0)" onclick="closeMenu(); handleAccountButtonClick();"
                    class="text-sm md:text-base font-normal tracking-widest text-gray-400 hover:text-white transition-all duration-300 w-fit">ACCOUNT</a>
                <a href="javascript:void(0)" onclick="navigateTo('view-contact')"
                    class="text-sm md:text-base font-normal tracking-widest text-gray-400 hover:text-white transition-all duration-300 w-fit">CONTACT</a>
            </div>

        </div>
    </div>

    <!-- OVERLAY 2: FULL-SCREEN EDITORIAL LIVE SEARCH -->
    <div id="overlay-search"
        class="fixed inset-0 w-full h-full bg-white z-[90] transform -translate-y-full overlay-slide flex flex-col transition-transform duration-400 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-y-auto">
        <!-- Search Sticky Top Bar -->
        <div class="sticky top-0 bg-white/95 backdrop-blur-md z-30 px-6 sm:px-10 py-4 sm:py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase font-montserrat text-black">SEARCH</span>
                <span class="text-gray-300 text-xs select-none">/</span>
                <span class="text-[11px] text-gray-400 tracking-wider font-roboto uppercase hidden sm:inline">NOTISSE ARCHIVE</span>
            </div>
            <button id="search-close" aria-label="Close Search"
                class="group text-xs sm:text-sm font-semibold tracking-widest uppercase hover:text-gray-500 transition-colors flex items-center space-x-2 cursor-pointer focus:outline-none py-1">
                <span>CLOSE</span>
                <span class="text-base sm:text-lg group-hover:rotate-90 transition-transform duration-300 leading-none">&#10005;</span>
            </button>
        </div>

        <!-- Main Full Screen Content Container -->
        <div class="flex-grow w-full max-w-7xl mx-auto px-6 sm:px-10 py-8 md:py-12 flex flex-col">
            <!-- Large Editorial Search Input -->
            <div class="w-full max-w-4xl mx-auto mb-8 md:mb-12">
                <div class="relative flex items-center border-b-2 border-black pb-3">
                    <input type="text" id="search-input" oninput="handleLiveSearch(this.value)"
                        placeholder="Search collection, product name, or artist..."
                        autocomplete="off"
                        class="w-full text-xl sm:text-3xl md:text-4xl bg-transparent focus:outline-none placeholder:text-gray-300 font-light font-roboto py-1 text-black">
                    <button onclick="handleLiveSearch(document.getElementById('search-input').value)" aria-label="Submit Search"
                        class="ml-3 text-2xl md:text-3xl text-black hover:translate-x-1.5 transition-transform focus:outline-none cursor-pointer">
                        &rarr;
                    </button>
                </div>

                <!-- Quick Filter Suggestion Chips -->
                <div class="flex flex-wrap items-center gap-2 pt-4">
                    <span class="text-[11px] uppercase tracking-wider text-gray-400 font-montserrat mr-1 font-medium">Popular:</span>
                    <button onclick="applySearchQuery('Tee')" class="text-[11px] font-montserrat uppercase px-3 py-1 border border-gray-200 hover:border-black rounded-full hover:bg-black hover:text-white transition-colors cursor-pointer">Tee</button>
                    <button onclick="applySearchQuery('Raja Diamuk')" class="text-[11px] font-montserrat uppercase px-3 py-1 border border-gray-200 hover:border-black rounded-full hover:bg-black hover:text-white transition-colors cursor-pointer">Raja Diamuk</button>
                    <button onclick="applySearchQuery('Tank')" class="text-[11px] font-montserrat uppercase px-3 py-1 border border-gray-200 hover:border-black rounded-full hover:bg-black hover:text-white transition-colors cursor-pointer">Tank</button>
                    <button onclick="applySearchQuery('Shirt')" class="text-[11px] font-montserrat uppercase px-3 py-1 border border-gray-200 hover:border-black rounded-full hover:bg-black hover:text-white transition-colors cursor-pointer">Shirt</button>
                    <button onclick="applySearchQuery('Blazer')" class="text-[11px] font-montserrat uppercase px-3 py-1 border border-gray-200 hover:border-black rounded-full hover:bg-black hover:text-white transition-colors cursor-pointer">Blazer</button>
                </div>
            </div>

            <!-- Dynamic Search Result Header -->
            <div id="search-results-meta"
                class="text-xs uppercase tracking-widest text-gray-400 font-montserrat font-semibold mb-6 hidden">
                PRODUCTS (<span id="search-results-count">0</span>)
            </div>

            <!-- Dynamic Live Results Grid Container (Full Screen Multi-Column Grid) -->
            <div id="search-results-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 md:gap-8 flex-grow">
                <!-- Live search items render dynamically here -->
            </div>
        </div>
    </div>

    <!-- OVERLAY 3: MY CART SLIDE-OVER DRAWER -->
    <div id="overlay-cart"
        class="fixed inset-0 bg-black/40 z-[60] hidden opacity-0 overlay-backdrop transition-opacity duration-400"
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

            <div class="mt-6 pt-6 border-t border-gray-200 flex flex-col items-end space-y-1">
                <div class="flex items-baseline space-x-6">
                    <span class="text-base md:text-lg font-medium text-gray-900 font-montserrat">Subtotal</span>
                    <span id="cart-total-price" class="text-xl md:text-xl font-regular font-montserrat">Rp 0,00 IDR</span>
                </div>
                <p class="text-xs text-gray-400 font-montserrat">Shipping will be calculated at checkout.</p>
                <div class="pt-4 w-full text-right">
                    <button onclick="window.location.href='/auth/checkout'"
                        class="btn-brush text-sm md:text-base cursor-pointer font-montserrat" id="checkout-btn">
                        CHECKOUT
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- OVERLAY 4: ARTWORK OFFERING MODAL (ACQUISITION INQUIRY) -->
    <div id="modal-artwork-offering"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4 sm:p-6"
        onclick="closeArtworkOfferingModal()">
        <div class="bg-white w-full max-w-lg shadow-2xl border border-gray-200 transform scale-95 transition-transform duration-300 flex flex-col max-h-[92vh] overflow-hidden"
            onclick="event.stopPropagation()">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-white sticky top-0 z-10">
                <div>
                    <span class="text-[10px] font-bold tracking-[0.25em] uppercase text-gray-400 font-montserrat block">NOTISSE ART GALLERY ACQUISITION</span>
                    <h3 class="text-base sm:text-lg font-bold font-montserrat text-black tracking-tight">Artwork Offering / Buy Inquiry</h3>
                </div>
                <button onclick="closeArtworkOfferingModal()" aria-label="Close Modal"
                    class="text-2xl font-light hover:rotate-90 text-gray-500 hover:text-black transition-transform duration-300 focus:outline-none cursor-pointer">
                    &#10005;
                </button>
            </div>

            <!-- Modal Form Body (Scrollable) -->
            <div id="artwork-offering-form-body" class="p-6 overflow-y-auto space-y-5 flex-grow">
                <!-- Selected Artwork Card Preview -->
                <div class="flex items-center space-x-4 p-3.5 bg-neutral-50 border border-gray-200 rounded-sm">
                    <img id="offering-artwork-img" src="https://placehold.co/400x500/222/fff?text=Artwork" alt="Artwork"
                        class="w-16 h-20 object-cover bg-gray-200 border border-gray-300 flex-shrink-0">
                    <div class="space-y-0.5 min-w-0">
                        <span class="text-[9px] font-semibold tracking-widest uppercase bg-black text-white px-1.5 py-0.5 inline-block font-montserrat mb-1">Original Piece</span>
                        <p id="offering-artwork-title" class="font-bold text-xs sm:text-sm text-black font-montserrat truncate">Mereka Ulang Ungkapan Indah Leila</p>
                        <p class="text-xs text-gray-600 font-roboto">Artist: <span id="offering-artwork-artist" class="font-semibold text-black">Mustafa Alatas</span></p>
                        <p id="offering-artwork-specs" class="text-[11px] text-gray-500 font-roboto">Oil on canvas &bull; 60x60cm</p>
                    </div>
                </div>

                <!-- Offering Form -->
                <form id="artwork-offering-form" onsubmit="handleArtworkOfferingSubmit(event)" class="space-y-4">
                    <!-- Account Identity Card (Logged in User) -->
                    <div class="p-3.5 bg-neutral-50 border border-gray-200 rounded-sm">
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-400 font-montserrat block mb-1.5">Akun Penawar (Tercatat di Sistem)</span>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center font-bold text-xs font-montserrat flex-shrink-0" id="offering-user-avatar">
                                    JD
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-xs sm:text-sm text-black font-montserrat truncate" id="offering-user-name">John Doe</p>
                                    <p class="text-[11px] text-gray-500 font-roboto truncate" id="offering-user-email">john.doe@notisse.com</p>
                                </div>
                            </div>
                            <span class="text-[9px] font-semibold tracking-wider uppercase text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded font-montserrat flex-shrink-0">
                                Akun Terverifikasi
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wider uppercase font-montserrat text-black mb-1">
                            Nominal Tawaran (IDR) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-500 font-montserrat">Rp</span>
                            <input type="text" id="offering-buyer-price" required placeholder="Misal: 7.500.000"
                                oninput="formatOfferingPriceInput(this)"
                                class="w-full border border-gray-300 pl-10 pr-3.5 py-2.5 text-sm font-roboto focus:outline-none focus:border-black transition-colors font-medium">
                        </div>
                        <p class="text-[10px] text-gray-400 font-roboto mt-1">Masukkan nominal penawaran yang diajukan untuk karya seni asli.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wider uppercase font-montserrat text-black mb-1">
                            Catatan / Pesan Pembelian (Opsional)
                        </label>
                        <textarea id="offering-buyer-message" rows="3" placeholder="Tuliskan catatan tambahan atau rencana penempatan karya untuk kurator & seniman..."
                            class="w-full border border-gray-300 px-3.5 py-2 text-sm font-roboto focus:outline-none focus:border-black transition-colors resize-none"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" id="offering-submit-btn"
                            class="w-full bg-black text-white hover:bg-neutral-800 transition-colors py-3.5 px-6 font-montserrat text-xs sm:text-sm font-bold tracking-[0.2em] uppercase cursor-pointer flex items-center justify-center space-x-2">
                            <span>AJUKAN PENAWARAN (SUBMIT OFFER)</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </form>

                <p class="text-[11px] text-gray-400 text-center font-roboto">
                    Pengajuan offering tersimpan otomatis di akun Anda dan diteruskan ke kurasi internal Notisse & seniman pemilik karya.
                </p>
            </div>

            <!-- Success State Body (Official In-App Confirmation) -->
            <div id="artwork-offering-success-body" class="p-6 sm:p-8 hidden text-center space-y-5">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
                    &#10003;
                </div>
                <div>
                    <h4 class="text-lg sm:text-xl font-bold font-montserrat text-black">Offering Berhasil Diajukan!</h4>
                    <p class="text-xs text-gray-600 font-roboto mt-2 max-w-sm mx-auto leading-relaxed">
                        Penawaran telah tercatat secara resmi di sistem Notisse. Tim kurasi Notisse dan pemilik karya (<strong class="text-black" id="success-artist-name">Mustafa Alatas</strong>) akan mereview penawaran ini.
                    </p>
                </div>

                <div class="p-4 bg-neutral-50 border border-gray-200 text-left space-y-2 text-xs font-roboto">
                    <div class="flex justify-between border-b border-gray-200 pb-1.5">
                        <span class="text-gray-500 font-montserrat text-[11px] uppercase">Ref. Offering:</span>
                        <span class="font-mono font-bold text-black" id="success-offering-ref">#NTS-ART-9281</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Akun Pembeli:</span>
                        <span class="font-medium text-black" id="success-buyer-name">John Doe (john.doe@notisse.com)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Karya Seni:</span>
                        <span class="font-medium text-black truncate ml-2" id="success-artwork-title">Mereka Ulang Ungkapan Indah Leila</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nominal Tawaran:</span>
                        <span class="font-bold text-emerald-700 font-montserrat" id="success-artwork-price">Rp 12.500.000 IDR</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-gray-500">Status Penawaran:</span>
                        <span class="font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded text-[10px] uppercase font-montserrat">Dalam Tinjauan Kurasi</span>
                    </div>
                </div>

                <p class="text-[11px] text-gray-400 font-roboto leading-relaxed">
                    Hasil kurasi penawaran serta instruksi lanjutan akan dikirimkan langsung ke notifikasi akun dan email Anda.
                </p>

                <div class="pt-2">
                    <button type="button" onclick="closeArtworkOfferingModal()"
                        class="w-full bg-black text-white hover:bg-neutral-800 font-montserrat py-3 px-4 text-xs font-semibold tracking-wider uppercase transition-colors cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- OVERLAY 5: FULL-SCREEN IMAGE LIGHTBOX MODAL (ZOOM SIZE CHART & ARTWORKS) -->
    <div id="modal-image-lightbox"
        class="fixed inset-0 bg-black/90 backdrop-blur-md hidden opacity-0 transition-opacity duration-300 flex flex-col items-center justify-center p-4 sm:p-8"
        onclick="closeLightbox()">
        <div class="absolute top-4 right-6 flex items-center space-x-4 z-20">
            <span id="lightbox-caption" class="text-xs text-gray-300 uppercase font-montserrat tracking-widest hidden sm:inline"></span>
            <button onclick="closeLightbox()" aria-label="Close Lightbox"
                class="text-3xl text-white hover:text-gray-300 hover:rotate-90 transition-transform duration-300 focus:outline-none cursor-pointer">
                &#10005;
            </button>
        </div>
        <div class="max-w-4xl max-h-[85vh] flex items-center justify-center p-2" onclick="event.stopPropagation()">
            <img id="lightbox-img" src="" alt="Enlarged View" class="max-w-full max-h-[82vh] object-contain shadow-2xl rounded-sm">
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
                    <a href="javascript:void(0)" onclick="document.getElementById('shop-catalog').scrollIntoView({behavior: 'smooth'})"
                        class="group flex items-start pointer-events-auto cursor-pointer mb-1 md:mb-2 transition-colors duration-300 text-white/70 hover:text-white">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[5.5rem] font-bold tracking-tighter leading-[0.85] font-sans">Shop</span>
                    </a>
                    <a href="javascript:void(0)" onclick="navigateTo('view-artist-collab')"
                        class="group flex items-start pointer-events-auto cursor-pointer mb-1 md:mb-2 transition-colors duration-300 text-white/70 hover:text-white">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[5.5rem] font-bold tracking-tighter leading-[0.85] font-sans">Artist
                            Collab</span>
                    </a>
                    <a href="javascript:void(0)" onclick="navigateTo('view-lookbook-list')"
                        class="group flex items-start pointer-events-auto cursor-pointer mb-1 md:mb-2 transition-colors duration-300 text-white/70 hover:text-white">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[5.5rem] font-bold tracking-tighter leading-[0.85] font-sans">Lookbook</span>
                    </a>
                    <a href="javascript:void(0)" onclick="navigateTo('view-about')"
                        class="group flex items-start pointer-events-auto cursor-pointer mb-1 md:mb-2 transition-colors duration-300 text-white/70 hover:text-white">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[5.5rem] font-bold tracking-tighter leading-[0.85] font-sans">About</span>
                    </a>
                </div>

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
            <div id="shop-catalog" class="w-full">
                <div class="grid grid-cols-2 lg:grid-cols-4 bg-black gap-[1px] border-b border-black">
                    @foreach($products as $product)
                        <div onclick="openProductDetail({{ $product->id }})"
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
                        <p id="pdetail-price" class="text-lg md:text-xl font-medium mt-1 font-montserrat text-black">
                            RP 250.000,00
                        </p>
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
                                <div class="accordion-inner pb-4 pt-2">
                                    <div class="border border-gray-200 bg-neutral-50 p-2.5 text-center group cursor-zoom-in rounded-sm"
                                        onclick="openLightbox('{{ asset('size chart 1.png') }}', 'NOTISSE APPAREL SIZE CHART')">
                                        <img src="{{ asset('size chart 1.png') }}" alt="Notisse Size Chart"
                                            class="w-full max-w-sm mx-auto h-auto object-contain transition-transform duration-300 group-hover:scale-[1.02] shadow-sm">
                                        <p class="text-[10px] text-gray-500 font-montserrat uppercase tracking-wider mt-2.5 flex items-center justify-center space-x-1 font-medium">
                                            <svg class="w-3.5 h-3.5 stroke-current inline" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                            <span>Klik gambar untuk memperbesar</span>
                                        </p>
                                    </div>
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

                    <!-- MODEL FIT & MEASUREMENTS (TB, BB, SIZE) -->
                    <div class="pt-1 flex items-center space-x-2 text-xs text-gray-600 font-roboto">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p class="tracking-wide">
                            <span class="text-black font-semibold uppercase font-montserrat text-[11px]">Model:</span> TB 178 cm / BB 68 kg — wearing size <span class="font-bold text-black font-montserrat">L</span>
                        </p>
                    </div>

                    <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-6">
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
                        <img id="pdetail-image" src="{{ asset('footage-baju.jpg') }}" alt="Front View"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="w-full border border-black aspect-[3/4] bg-white overflow-hidden shadow-sm">
                        <img id="pdetail-back-image" src="{{ asset('footage-baju-belakang.jpg') }}" alt="Back Graphic"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="w-full border border-black aspect-[3/4] bg-white overflow-hidden shadow-sm">
                        <img id="pdetail-footage-image" src="{{ asset('footagebaju2.jpg') }}"
                            alt="Detail Close up" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 3: ARTIST COLLAB LIST PAGE -->
        <section id="view-artist-collab" class="view-page px-4 md:px-12 pt-20 sm:pt-24 pb-12 max-w-7xl mx-auto">
            <div class="mb-16">
                <h2 class="text-xl md:text-2xl font-bold mb-6 tracking-wide text-black font-montserrat">Newcomer</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-8">
                    <div class="aspect-[16/10] bg-gray-200 overflow-hidden group cursor-pointer"
                        onclick="openArtistProfile('{{ addslashes($newcomer->name ?? 'Mustafa Alatas') }}', '{{ addslashes($newcomer->city ?? 'Temanggung') }}')">
                        <img src="{{ asset($newcomer->portrait_image ?? 'masmus.jpeg') }}"
                            alt="{{ $newcomer->name ?? 'Mustafa Portrait' }}"
                            class="w-full h-full object-cover grayscale group-hover:scale-105 transition-all duration-500">
                    </div>
                    <div class="aspect-[16/10] bg-gray-200 overflow-hidden group cursor-pointer"
                        onclick="openArtistProfile('{{ addslashes($newcomer->name ?? 'Mustafa Alatas') }}', '{{ addslashes($newcomer->city ?? 'Temanggung') }}')">
                        <img src="{{ asset($newcomer->artwork_image ?? 'home.jpg') }}" alt="{{ $newcomer->name ?? 'Mustafa Artwork' }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                    </div>
                </div>

                <div class="max-w-3xl mx-auto text-center my-8 px-2 space-y-4">
                    <h3 class="text-lg md:text-xl font-bold text-black font-roboto">About {{ explode(' ', $newcomer->name ?? 'Mustafa')[0] }}</h3>
                    <p class="text-gray-700 text-xs md:text-sm leading-relaxed font-regular font-roboto">
                        {{ $newcomer->bio ?? 'Mustafa Alatas is a contemporary visual artist based in Temanggung whose distinct brush strokes and visceral storytelling bring deep cultural narratives into modern street apparel.' }}
                    </p>
                    <div class="pt-2">
                        <button onclick="openArtistProfile('{{ addslashes($newcomer->name ?? 'Mustafa Alatas') }}', '{{ addslashes($newcomer->city ?? 'Temanggung') }}')"
                            class="btn-brush text-xs py-2 px-6 cursor-pointer font-montserrat">Read More</button>
                    </div>
                </div>
            </div>

            <!-- ARTIST COLLABORATOR SECTION -->
            <div class="pt-8 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-xl md:text-2xl font-bold tracking-wide text-black font-montserrat uppercase">
                        Artist Collaborator
                    </h2>
                    <!-- Search Bar Sejajar dengan Tulisan Artist Collaborator -->
                    <div class="relative w-full sm:w-72 md:w-80">
                        <input type="text" id="artist-search-input" oninput="filterArtists(this.value)"
                            placeholder="Search artist or city..."
                            class="w-full bg-transparent border-b border-black py-1.5 pl-7 pr-3 text-xs md:text-sm font-roboto tracking-wide focus:outline-none focus:border-[#d52c2b] placeholder-gray-400 transition-colors">
                        <svg class="w-4 h-4 text-black absolute left-0 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-4.35z" />
                        </svg>
                    </div>
                </div>

                <div id="artist-collab-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    @if(isset($artists) && $artists->count() > 0)
                        @foreach($artists as $artItem)
                            @php
                                $aPhoto = $artItem->photo ? (Str::startsWith($artItem->photo, 'http') ? $artItem->photo : (file_exists(public_path('storage/' . $artItem->photo)) ? asset('storage/' . $artItem->photo) : asset($artItem->photo))) : 'https://placehold.co/500x500/181818/ffffff?text=' . urlencode($artItem->name);
                            @endphp
                            <div class="artist-card group cursor-pointer" data-artist-name="{{ $artItem->name }}" data-artist-city="{{ $artItem->city }}" onclick="openArtistProfile('{{ addslashes($artItem->name) }}', '{{ addslashes($artItem->city) }}')">
                                <div class="w-full aspect-square bg-gray-200 mb-3 overflow-hidden">
                                    <img src="{{ $aPhoto }}"
                                        alt="{{ $artItem->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <h4 class="font-bold text-sm md:text-base text-black font-roboto">{{ $artItem->name }}</h4>
                                <p class="text-xs text-gray-500 font-roboto">{{ $artItem->city ?: 'Indonesia' }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="artist-card group cursor-pointer" data-artist-name="Mustafa Alatas" data-artist-city="Temanggung" onclick="openArtistProfile('Mustafa Alatas', 'Temanggung')">
                            <div class="w-full aspect-square bg-gray-200 mb-3 overflow-hidden">
                                <img src="https://placehold.co/500x500/181818/ffffff?text=Mustafa+Alatas"
                                    alt="Mustafa Alatas"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <h4 class="font-bold text-sm md:text-base text-black font-roboto">Mustafa Alatas</h4>
                            <p class="text-xs text-gray-500 font-roboto">Temanggung</p>
                        </div>
                    @endif

                    <!-- Empty State for Artist Search -->
                    <div id="artist-no-results" class="hidden col-span-2 md:col-span-4 text-center py-12">
                        <p class="text-gray-400 text-sm font-roboto">No artist found matching your search.</p>
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
                    Articles Featuring Artist
                </h3>

                <div id="artist-editorial-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Populated dynamically via JS per artist -->
                </div>

                <div id="artist-editorial-empty" class="hidden text-center py-12 bg-gray-50 border border-gray-100 text-gray-400 font-roboto text-sm">
                    Belum ada artikel editorial yang dipublikasikan untuk seniman ini.
                </div>
            </div>

            <!-- TAB CONTENT 2: ARTWORK GALLERY -->
            <div id="artist-tab-content-artwork" class="hidden space-y-6">
                <div id="artist-artwork-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Populated dynamically via JS per artist -->
                </div>

                <div id="artist-artwork-empty" class="hidden text-center py-12 bg-gray-50 border border-gray-100 text-gray-400 font-roboto text-sm">
                    Belum ada karya seni fisik yang dipublikasikan untuk seniman ini.
                </div>
            </div>
        </section>

        <!-- VIEW 3C: ARTWORK DETAIL PAGE (WITH DIRECT OFFERING ACQUISITION PANEL) -->
        <section id="view-artist-artwork-detail" class="view-page px-6 md:px-12 pt-24 pb-10 max-w-7xl mx-auto">
            <div class="mb-6 flex items-center justify-between">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO ARTIST PROFILE</span>
                </button>
                <div class="flex items-center space-x-2 text-xs font-montserrat">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-gray-600 font-medium tracking-wider uppercase text-[11px]">Available for Direct Offering</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start">
                <div class="md:col-span-7 aspect-[4/3] bg-[#d9d9d9] overflow-hidden shadow-sm relative group cursor-zoom-in"
                    onclick="openLightbox(document.getElementById('artwork-detail-image').src, document.getElementById('artwork-title').innerText)">
                    <img id="artwork-detail-image" src="https://placehold.co/1000x750/d9d9d9/555555?text=Full+Artwork+View" alt="Full Artwork"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                    <div class="absolute bottom-3 right-3 bg-black/70 backdrop-blur-sm text-white text-[10px] font-montserrat uppercase px-2.5 py-1 tracking-wider flex items-center space-x-1">
                        <svg class="w-3 h-3 stroke-current inline" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        <span>Click to Enlarge</span>
                    </div>
                </div>

                <div class="md:col-span-5 space-y-4">
                    <div>
                        <span class="text-[10px] font-bold tracking-[0.25em] uppercase text-gray-400 font-montserrat block mb-1">NOTISSE ARTIST COLLABORATION</span>
                        <h2 id="artwork-artist-name" class="text-xl md:text-2xl font-bold font-montserrat text-black">
                            Mustafa Alatas</h2>
                    </div>

                    <h3 id="artwork-title" class="text-base md:text-lg italic font-roboto text-black">Mereka Ulang
                        Ungkapan Indah Leila</h3>

                    <div class="text-xs md:text-sm text-gray-700 font-roboto space-y-1 py-1 border-y border-gray-100">
                        <p id="artwork-medium">Medium: Oil on canvas</p>
                        <p id="artwork-dimensions">Dimensions: 60x60cm</p>
                    </div>

                    <p class="text-xs md:text-sm text-gray-700 leading-relaxed font-roboto pt-2">
                        Karya seni asli (original physical artwork) yang diciptakan eksklusif oleh seniman. Setiap karya disertai dengan sertifikat autentisitas bertanda tangan kuratorial Notisse.
                    </p>

                    <!-- DIRECT OFFERING ACQUISITION PANEL -->
                    <div class="pt-6 border-t border-gray-200 space-y-3.5">
                        <div class="p-3 bg-neutral-50 border border-gray-200 text-xs font-roboto text-gray-600 space-y-1">
                            <div class="flex items-center justify-between font-montserrat">
                                <span class="font-semibold uppercase tracking-wider text-black text-[11px]">Metode Pembelian:</span>
                                <span class="text-emerald-700 font-bold uppercase tracking-wider text-[11px]">Offering / Inquiry</span>
                            </div>
                            <p class="text-[11px] text-gray-500 leading-relaxed pt-1">
                                Pembelian artwork berbeda dengan produk pakaian di cart. Sistem offering akan menghubungkan penawaran Anda langsung ke seniman dan kurator Notisse.
                            </p>
                        </div>

                        <button onclick="triggerArtworkOfferingCurrent()"
                            class="w-full bg-black text-white hover:bg-neutral-800 transition-all py-3.5 px-6 font-montserrat text-xs md:text-sm font-bold tracking-[0.2em] uppercase cursor-pointer flex items-center justify-center space-x-2 shadow-md hover:shadow-xl">
                            <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12m-6-6h12"/>
                            </svg>
                            <span>OFFERING / BUY ARTWORK</span>
                        </button>

                        <p class="text-[11px] text-gray-400 font-roboto text-center leading-relaxed">
                            Penawaran yang Anda submit akan dicatat ke sistem Notisse dan diverifikasi langsung oleh tim kurator dan seniman.
                        </p>
                    </div>
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
                        <h1 id="editorial-detail-title" class="text-xl md:text-2xl font-bold font-montserrat text-black leading-snug">
                            Mustafa Alatas Menafsir Kuasa dan Sosial dalam Lukisan Figuratif dan Simbolisme
                        </h1>
                        <p id="editorial-detail-author" class="text-xs text-gray-500 font-roboto mt-2">Laksa Dawantara</p>
                    </div>

                    <div id="editorial-detail-content" class="text-xs md:text-sm text-gray-700 leading-relaxed font-roboto space-y-4">
                        <p>
                            Dokumentasi wacana dan catatan proses berkarya seniman bersama Notisse.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 my-6">
                        <div>
                            <div class="w-full aspect-[3/4] bg-[#d9d9d9] overflow-hidden mb-1.5">
                                <img id="editorial-detail-portrait" src="https://placehold.co/400x500/222/fff?text=Portrait" alt="Portrait"
                                    class="w-full h-full object-cover">
                            </div>
                            <p class="text-[10px] text-gray-400 font-roboto">Dokumentasi kuratorial seniman Notisse.</p>
                        </div>
                        <div>
                            <div class="w-full aspect-[3/4] bg-[#d9d9d9] overflow-hidden mb-1.5">
                                <img id="editorial-detail-artwork" src="https://placehold.co/400x500/444/fff?text=Artwork" alt="Artwork"
                                    class="w-full h-full object-cover">
                            </div>
                            <p class="text-[10px] text-gray-400 font-roboto">Karya seni terkait dalam artikel.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Full Featured Editorial Video -->
                <div id="editorial-video-container" class="md:col-span-6 aspect-[4/5] bg-black overflow-hidden relative group shadow-lg">
                    <video id="editorial-video-player"
                        class="w-full h-full object-cover"
                        autoplay loop muted playsinline controls
                        poster="{{ asset('home.jpg') }}">
                        <source id="editorial-video-source" src="{{ asset('video/editorial.mp4') }}" type="video/mp4">
                        <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <!-- Editorial Video Watermark Badge -->
                    <div class="absolute top-4 right-4 pointer-events-none bg-black/60 backdrop-blur-md text-white text-[10px] font-montserrat uppercase px-2.5 py-1 tracking-widest border border-white/20">
                        Editorial Video
                    </div>
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
            class="view-page min-h-[calc(100vh-80px)] flex flex-col justify-center items-start px-6 sm:px-12 md:px-16 lg:px-20 xl:px-24 py-16 md:py-24 pb-28 md:pb-32 max-w-full">
            <div class="w-full text-left space-y-7 sm:space-y-9 md:space-y-12 lg:space-y-15">
                <p
                    class="text-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl 2xl:text-[5.5vw] font-bold leading-[1.18] sm:leading-[1.18] tracking-tight font-roboto">
                    NOTISSE IS AN ALTERNATIVE MOVEMENT IN ART EXHIBITION.WOVEN INTO THE FABRIC OF EVERYDAY LIFE.SO ART BECOMES PART OF DAILY ACTIVITY NOT A SEPARATE OCCASION.
                </p>
            </div>
        </section>

        <!-- VIEW 6B: CONTACT PAGE -->
        <section id="view-contact"
            class="view-page px-8 sm:px-16 md:px-24 py-16 md:py-24 max-w-5xl mx-auto min-h-[calc(100vh-80px)] flex flex-col justify-center">
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
        </section>

        <!-- VIEW 7: LOGIN PAGE -->
        <section id="view-login" class="view-page px-6 py-12 max-w-md mx-auto">
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
        <section id="view-register" class="view-page px-6 py-12 max-w-md mx-auto">
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
        <section id="view-account-profile" class="view-page px-4 sm:px-6 md:px-12 pt-32 md:pt-40 pb-28 max-w-5xl mx-auto space-y-10">
            <!-- Back to Shop -->
            <div>
                <button onclick="showPage('view-shop')"
                    class="group inline-flex items-center space-x-2 text-xs font-semibold tracking-widest text-neutral-400 hover:text-black uppercase transition-colors font-montserrat focus:outline-none cursor-pointer">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>Kembali ke Toko</span>
                </button>
            </div>

            <!-- Profile Hero Header Banner -->
            <div class="border border-black bg-white p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
                <div class="flex items-center space-x-5">
                    <div class="w-16 h-16 bg-black text-white flex items-center justify-center font-bold text-xl font-montserrat tracking-wider border border-black shrink-0">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name ?? 'N', 0, 2)) : 'NT' }}
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-xl md:text-2xl font-bold font-montserrat uppercase tracking-tight text-black" id="account-header-name">
                                {{ auth()->check() ? auth()->user()->name : 'Member Notisse' }}
                            </h1>
                            <span class="text-[9px] font-bold font-montserrat tracking-widest uppercase bg-black text-white px-2 py-0.5">VERIFIED CLIENT</span>
                        </div>
                        <p id="user-display-email" class="text-xs text-neutral-500 font-roboto">
                            {{ auth()->check() ? auth()->user()->email : 'user@notisse.com' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 self-start md:self-auto">
                    <button onclick="showEditProfile()"
                        class="px-4 py-2 border border-black hover:bg-black hover:text-white transition-colors text-xs font-semibold uppercase tracking-wider font-montserrat cursor-pointer">
                        Edit Profil
                    </button>
                    <button onclick="handleLogout()"
                        class="px-4 py-2 bg-neutral-100 hover:bg-red-600 hover:text-white text-neutral-700 transition-colors text-xs font-semibold uppercase tracking-wider font-montserrat cursor-pointer">
                        Logout
                    </button>
                </div>
            </div>

            <!-- Profile Section Tabs -->
            <div class="flex border-b border-gray-200 gap-2 sm:gap-6 overflow-x-auto">
                <button onclick="switchAccountTab('orders')" id="acc-tab-orders"
                    class="acc-tab-btn active pb-3 text-xs sm:text-sm font-bold font-montserrat uppercase tracking-wider border-b-2 border-black text-black cursor-pointer whitespace-nowrap">
                    Riwayat Pesanan
                </button>
                <button onclick="switchAccountTab('address')" id="acc-tab-address"
                    class="acc-tab-btn pb-3 text-xs sm:text-sm font-medium font-montserrat uppercase tracking-wider border-b-2 border-transparent text-gray-400 hover:text-black transition-colors cursor-pointer whitespace-nowrap">
                    Buku Alamat
                </button>
                <button onclick="switchAccountTab('offers')" id="acc-tab-offers"
                    class="acc-tab-btn pb-3 text-xs sm:text-sm font-medium font-montserrat uppercase tracking-wider border-b-2 border-transparent text-gray-400 hover:text-black transition-colors cursor-pointer whitespace-nowrap">
                    Penawaran Karya Seni
                </button>
            </div>

            <!-- TAB 1: ORDER HISTORY -->
            <div id="acc-pane-orders" class="space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold font-montserrat text-black uppercase tracking-wider">Pesanan Belanja</h2>
                        <p class="text-xs text-gray-400 font-roboto mt-0.5">Daftar transaksi produk Notisse yang terhubung ke pembayaran Midtrans.</p>
                    </div>
                    <button onclick="fetchOrderHistory()" class="text-xs font-montserrat font-semibold uppercase tracking-wider hover:underline text-gray-500 hover:text-black">
                        Refresh
                    </button>
                </div>

                <div id="order-history-container" class="space-y-4">
                    <div class="border border-gray-200 p-8 text-center bg-gray-50">
                        <p class="text-gray-400 font-roboto text-sm">Memuat pesanan...</p>
                    </div>
                </div>
            </div>

            <!-- TAB 2: ADDRESS BOOK -->
            <div id="acc-pane-address" class="hidden space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold font-montserrat text-black uppercase tracking-wider">Alamat Pengiriman Utama</h2>
                        <p class="text-xs text-gray-400 font-roboto mt-0.5">Alamat ini digunakan untuk kalkulasi ongkos kirim Biteship saat checkout.</p>
                    </div>
                    <button onclick="showEditProfile()" class="text-xs font-montserrat font-bold uppercase tracking-wider text-black underline">
                        Ubah Alamat
                    </button>
                </div>

                <div class="border border-gray-200 p-6 md:p-8 bg-white space-y-4">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-4">
                        <div>
                            <span class="text-[10px] font-bold font-montserrat tracking-widest uppercase bg-neutral-100 text-neutral-600 px-2 py-0.5 inline-block mb-1.5">Alamat Terpilih</span>
                            <h3 class="font-bold text-black text-base font-montserrat" id="user-display-name">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</h3>
                            <p class="text-xs text-gray-500 font-roboto mt-0.5" id="user-display-phone">{{ auth()->check() && auth()->user()->phone ? auth()->user()->phone : 'No. HP belum diatur' }}</p>
                        </div>
                        <button onclick="showEditProfile()" class="text-xs font-semibold uppercase tracking-wider text-black hover:underline font-montserrat">
                            Edit
                        </button>
                    </div>
                    <div>
                        <p class="text-xs font-roboto text-gray-700 leading-relaxed" id="user-display-address">
                            {{ auth()->check() && auth()->user()->address ? auth()->user()->address : 'Belum ada alamat pengiriman. Silakan tambahkan alamat untuk kemudahan checkout.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- TAB 3: ARTWORK OFFERS INQUIRIES -->
            <div id="acc-pane-offers" class="hidden space-y-6">
                <div>
                    <h2 class="text-base font-bold font-montserrat text-black uppercase tracking-wider">Penawaran Karya Seni (Acquisition Inquiries)</h2>
                    <p class="text-xs text-gray-400 font-roboto mt-0.5">Daftar penawaran harga karya seni editorial Notisse yang Anda ajukan untuk kurasi.</p>
                </div>

                <div id="account-art-offers-list" class="space-y-4">
                    <div class="border border-gray-200 p-8 text-center bg-gray-50">
                        <p class="text-gray-400 font-roboto text-sm">Belum ada penawaran karya seni yang diajukan.</p>
                        <button onclick="showPage('view-artist-detail')" class="mt-3 text-xs font-bold font-montserrat uppercase underline tracking-wider">
                            Jelajahi Galeri Seni
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 10: EDIT PROFILE / ALAMAT -->
        <section id="view-edit-profile" class="view-page px-4 sm:px-6 md:px-12 pt-32 md:pt-40 pb-28 max-w-xl mx-auto space-y-8">
            <div class="mb-4 flex items-center justify-between">
                <button onclick="goBack()" class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-montserrat">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>KEMBALI</span>
                </button>
                <h1 class="text-xl font-bold font-montserrat uppercase text-black">Edit Profil & Alamat</h1>
            </div>

            <div id="edit-profile-error" class="hidden bg-red-100 text-red-700 p-3 text-sm border border-red-200"></div>
            <div id="edit-profile-success" class="hidden bg-green-100 text-green-700 p-3 text-sm border border-green-200"></div>

            <form onsubmit="handleEditProfileSubmit(event)" class="space-y-6 bg-white border border-gray-200 p-6 md:p-8">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-montserrat">Nama Lengkap *</label>
                    <input type="text" id="edit-name" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black font-roboto">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-montserrat">No. HP / Whatsapp *</label>
                    <input type="text" id="edit-phone" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black font-roboto">
                </div>
                
                <div class="relative">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-montserrat">Cari Kecamatan / Kota (Biteship) *</label>
                    <input type="text" id="edit-area-search" class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black font-roboto" placeholder="Ketik nama kecamatan..." autocomplete="off">
                    <ul id="edit-area-results" class="absolute z-20 w-full bg-white border border-gray-300 shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></ul>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-montserrat">Alamat Lengkap *</label>
                    <textarea id="edit-address" required class="w-full border border-gray-300 p-3 text-sm h-24 focus:outline-none focus:border-black font-roboto resize-none" placeholder="Nama Jalan, RT/RW, Nomor Rumah, Patokan..."></textarea>
                </div>

                <input type="hidden" id="edit-area-id" required>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="button" onclick="goBack()" class="w-1/3 border border-gray-300 py-3.5 text-xs font-bold font-montserrat uppercase hover:bg-gray-50 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="edit-profile-btn" class="w-2/3 bg-black text-white font-bold font-montserrat uppercase py-3.5 text-xs tracking-wider hover:bg-neutral-800 transition-colors cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>

    </main>

    <!-- RUANG KOSONG AGAR BISA DI-SCROLL KE BAWAH -->
    <div class="h-[150vh] w-full bg-white"></div>

    <!-- MARQUEE TEXT GLOBAL -->
    <div id="global-marquee"
        class="fixed bottom-0 left-0 z-30 w-full border-t border-black overflow-hidden flex whitespace-nowrap py-2 md:py-2.5 bg-black shadow-lg">
        <div
            class="animate-marquee inline-block font-montserrat font-bold text-xs md:text-sm tracking-[0.22em] text-[#d52c2b] uppercase">
            {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span>&nbsp;
        </div>
        <div
            class="animate-marquee inline-block font-montserrat font-bold text-xs md:text-sm tracking-[0.22em] text-[#d52c2b] uppercase">
            {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $settings['marquee_text'] ?? 'NOTISSE WORLDWIDE SHIPPING' }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span>&nbsp;
        </div>
    </div>
    <!-- TOAST NOTIFICATION -->
    <div id="toast-notification"
        class="fixed top-24 left-1/2 -translate-x-1/2 bg-black text-white px-6 py-3 rounded-full shadow-lg z-[60] flex items-center space-x-3 transition-all duration-300 opacity-0 translate-y-4 hidden font-montserrat text-sm tracking-wide">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span id="toast-message" class="font-medium">Notification message</span>
    </div>

    <!-- JAVASCRIPT CONTROLLER -->
    <script>
        let currentSelectedSize = "M";
        let currentSlideIndex = 0;
        let slideInterval = null;

        let lastScrollY = window.scrollY;
        const scrollDelta = 8;

        // A24-Inspired Header (Hide on Scroll Down, Reveal on Scroll Up & Morph Logo)
        function updateHeaderState() {
            const activePage = document.querySelector('.view-page.active-view');
            const isShopPage = !activePage || activePage.id === 'view-shop';
            const mainHeader = document.getElementById('main-header');
            const logoContainer = document.getElementById('header-logo-container');
            const menuLeft = document.getElementById('menu-trigger');
            const menuRight = document.getElementById('header-right-menu');
            const currentScrollY = window.scrollY;
            const diff = currentScrollY - lastScrollY;

            if (!mainHeader) return;

            // Check if any modal/overlay is currently opened
            const overlayBackdrop = document.getElementById('overlay-menu-backdrop');
            const overlaySearch = document.getElementById('overlay-search');
            const overlayCart = document.getElementById('overlay-cart');
            const modalOffering = document.getElementById('modal-artwork-offering');
            const modalLightbox = document.getElementById('modal-image-lightbox');
            const isOverlayOpen = (overlayBackdrop && !overlayBackdrop.classList.contains('hidden')) ||
                                  (overlaySearch && !overlaySearch.classList.contains('-translate-y-full')) ||
                                  (overlayCart && !overlayCart.classList.contains('hidden')) ||
                                  (modalOffering && !modalOffering.classList.contains('hidden')) ||
                                  (modalLightbox && !modalLightbox.classList.contains('hidden'));

            if (currentScrollY <= 40) {
                // At the very top: ALWAYS VISIBLE
                mainHeader.style.transform = 'translateY(0)';
                mainHeader.classList.remove('header-hidden');
                mainHeader.classList.add('header-visible');

                if (isShopPage) {
                    // Shop Hero State: transparent, hidden controls, large wordmark
                    mainHeader.classList.remove('bg-white/90', 'backdrop-blur-md', 'border-b', 'border-black/[0.08]', 'shadow-[0_2px_12px_rgba(0,0,0,0.03)]');
                    mainHeader.classList.add('bg-transparent', 'border-transparent');

                    if (menuLeft) {
                        menuLeft.classList.remove('opacity-100', 'pointer-events-auto', 'translate-x-0');
                        menuLeft.classList.add('opacity-0', 'pointer-events-none', '-translate-x-3');
                    }
                    if (menuRight) {
                        menuRight.classList.remove('opacity-100', 'pointer-events-auto', 'translate-x-0');
                        menuRight.classList.add('opacity-0', 'pointer-events-none', 'translate-x-3');
                    }
                    if (logoContainer) {
                        logoContainer.classList.remove('logo-state-small');
                        logoContainer.classList.add('logo-state-large');
                    }
                } else {
                    // Other pages top state: white/blur, controls visible, large wordmark
                    mainHeader.classList.add('bg-white/90', 'backdrop-blur-md', 'border-b', 'border-black/[0.08]', 'shadow-[0_2px_12px_rgba(0,0,0,0.03)]');
                    mainHeader.classList.remove('bg-transparent', 'border-transparent');

                    if (menuLeft) {
                        menuLeft.classList.remove('opacity-0', 'pointer-events-none', '-translate-x-3');
                        menuLeft.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
                    }
                    if (menuRight) {
                        menuRight.classList.remove('opacity-0', 'pointer-events-none', 'translate-x-3');
                        menuRight.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
                    }
                    if (logoContainer) {
                        logoContainer.classList.remove('logo-state-small');
                        logoContainer.classList.add('logo-state-large');
                    }
                }
            } else {
                // When scrolled down beyond top (> 40px)
                // Set sticky style (white/blur, controls visible, small logo)
                mainHeader.classList.add('bg-white/90', 'backdrop-blur-md', 'border-b', 'border-black/[0.08]', 'shadow-[0_2px_12px_rgba(0,0,0,0.03)]');
                mainHeader.classList.remove('bg-transparent', 'border-transparent');

                if (menuLeft) {
                    menuLeft.classList.remove('opacity-0', 'pointer-events-none', '-translate-x-3');
                    menuLeft.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
                }
                if (menuRight) {
                    menuRight.classList.remove('opacity-0', 'pointer-events-none', 'translate-x-3');
                    menuRight.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
                }
                if (logoContainer) {
                    logoContainer.classList.add('logo-state-small');
                    logoContainer.classList.remove('logo-state-large');
                }

                // SMART HIDE ON SCROLL DOWN, REVEAL ON SCROLL UP
                if (!isOverlayOpen) {
                    if (diff > scrollDelta && currentScrollY > 70) {
                        // Scrolling DOWN -> HIDE HEADER
                        mainHeader.style.transform = 'translateY(-100%)';
                        mainHeader.classList.add('header-hidden');
                        mainHeader.classList.remove('header-visible');
                    } else if (diff < -scrollDelta) {
                        // Scrolling UP -> REVEAL HEADER
                        mainHeader.style.transform = 'translateY(0)';
                        mainHeader.classList.remove('header-hidden');
                        mainHeader.classList.add('header-visible');
                    }
                }
            }

            // EXACT A24 MORPHING CART BUTTON (Triggers the moving connecting line animation)
            const cartTrigger = document.getElementById('cart-trigger');
            if (cartTrigger) {
                if (isOverlayOpen || (isShopPage && currentScrollY <= 40)) {
                    cartTrigger.classList.add('opacity-0', 'pointer-events-none');
                } else {
                    cartTrigger.classList.remove('opacity-0', 'pointer-events-none');
                    // In A24, cart is in minimal (vertical) state ONLY when header is hidden on scroll down.
                    // When scrolling up and header reappears (not header-hidden), cart returns to horizontal!
                    const isHeaderHidden = mainHeader.classList.contains('header-hidden');
                    if (isHeaderHidden) {
                        cartTrigger.classList.add('state-minimal');
                    } else {
                        cartTrigger.classList.remove('state-minimal');
                    }
                }
            }

            lastScrollY = currentScrollY <= 0 ? 0 : currentScrollY;
        }

        window.addEventListener('scroll', updateHeaderState, { passive: true });
        updateHeaderState();

        let navigationHistory = ['view-shop'];
        let isAuthenticated = localStorage.getItem('notisse_auth') === 'true' || {{ auth()->check() ? 'true' : 'false' }};
        let loggedInUser = @json(auth()->user());
        if (!loggedInUser && localStorage.getItem('notisse_user')) {
            try { loggedInUser = JSON.parse(localStorage.getItem('notisse_user')); } catch(e){}
        }

        let productsData = @json($products);
        let artistsData = @json($artists ?? []);
        let currentProduct = null;
        let cartState = [];
        
        async function fetchCart() {
            try {
                const response = await fetch('/api/cart', { headers: { 'Accept': 'application/json' }});
                cartState = await response.json();
                renderCartItems();
            } catch (e) {
                console.error("Gagal memuat keranjang:", e);
            }
        }
        
        document.addEventListener('DOMContentLoaded', fetchCart);

        function saveCartState() {
            // No-op, managed by backend now
        }

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
            window.scrollTo({ top: 0, behavior: 'auto' });
            lastScrollY = 0;
            const mainHeader = document.getElementById('main-header');
            if (mainHeader) {
                mainHeader.style.transform = 'translateY(0)';
                mainHeader.classList.remove('header-hidden');
                mainHeader.classList.add('header-visible');
            }
            updateHeaderState();
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
            if (loggedInUser) {
                showPage('view-account-profile');
                fetchOrderHistory();
            } else {
                window.location.href = '/auth/checkout';
            }
        }

        function switchAccountTab(tab) {
            const tabs = ['orders', 'address', 'offers'];
            tabs.forEach(t => {
                const btn = document.getElementById(`acc-tab-${t}`);
                const pane = document.getElementById(`acc-pane-${t}`);
                if (btn && pane) {
                    if (t === tab) {
                        btn.className = "acc-tab-btn active pb-3 text-xs sm:text-sm font-bold font-montserrat uppercase tracking-wider border-b-2 border-black text-black cursor-pointer whitespace-nowrap";
                        pane.classList.remove('hidden');
                    } else {
                        btn.className = "acc-tab-btn pb-3 text-xs sm:text-sm font-medium font-montserrat uppercase tracking-wider border-b-2 border-transparent text-gray-400 hover:text-black transition-colors cursor-pointer whitespace-nowrap";
                        pane.classList.add('hidden');
                    }
                }
            });

            if (tab === 'orders') {
                fetchOrderHistory();
            } else if (tab === 'offers') {
                renderArtworkOffers();
            }
        }

        function renderArtworkOffers() {
            const container = document.getElementById('account-art-offers-list');
            if (!container) return;
            
            let offers = [];
            try {
                offers = JSON.parse(localStorage.getItem('notisse_artwork_offers') || '[]');
            } catch (e) {
                offers = [];
            }
            
            if (offers.length === 0) {
                container.innerHTML = `
                    <div class="border border-gray-200 p-8 text-center bg-gray-50">
                        <p class="text-gray-400 font-roboto text-sm">Belum ada penawaran karya seni yang diajukan.</p>
                        <button onclick="showPage('view-artist-detail')" class="mt-3 text-xs font-bold font-montserrat uppercase underline tracking-wider cursor-pointer">
                            Jelajahi Galeri Seni & Editorial
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = offers.map((offer) => {
                const artwork = offer.artwork || {};
                const displayDate = offer.date ? new Date(offer.date).toLocaleDateString('id-ID') : '-';
                return `
                    <div class="border border-gray-200 p-6 bg-white space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-gray-100 gap-2">
                            <div>
                                <span class="text-[10px] font-mono font-bold tracking-widest uppercase bg-black text-white px-2 py-0.5 inline-block">${offer.refId || '#NTS-ART'}</span>
                                <span class="text-xs text-gray-400 font-roboto ml-2">${displayDate}</span>
                            </div>
                            <span class="text-[10px] font-semibold tracking-wider uppercase text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded font-montserrat w-fit">
                                ${offer.status || 'Dalam Tinjauan Kurasi'}
                            </span>
                        </div>
                        <div class="flex items-start space-x-4">
                            <img src="${artwork.image || '/footage-baju.jpg'}" alt="${artwork.title || 'Artwork'}" class="w-16 h-20 object-cover bg-gray-100 border border-gray-200 flex-shrink-0">
                            <div class="space-y-1 min-w-0 flex-1">
                                <h4 class="font-bold text-sm text-black font-montserrat truncate">${artwork.title || 'Karya Seni Notisse'}</h4>
                                <p class="text-xs text-gray-500 font-roboto">Seniman: <span class="font-semibold text-black">${artwork.artist || 'Curated Artist'}</span></p>
                                <p class="text-xs text-emerald-700 font-montserrat font-bold pt-1">Nominal Ditawarkan: Rp ${offer.offerPrice} IDR</p>
                                ${offer.message ? `<p class="text-xs text-gray-500 font-roboto italic bg-neutral-50 p-2 border border-neutral-100 mt-2">"${offer.message}"</p>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).reverse().join('');
        }


        async function fetchOrderHistory() {
            try {
                const response = await fetch('/my-orders');
                const orders = await response.json();
                
                const container = document.getElementById('order-history-container');
                if (!container) return;
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
                    if (order.items && order.items.length > 0) {
                        order.items.forEach(item => {
                            itemsHtml += `
                            <div class="flex items-center space-x-4 mt-4">
                                <div class="w-12 h-16 bg-gray-100 flex-shrink-0 border border-gray-200 overflow-hidden">
                                    <img src="{{ asset('footage-baju.jpg') }}" alt="${item.product_name}" class="w-full h-full object-cover">
                                </div>
                                <div class="text-xs font-roboto">
                                    <p class="font-bold text-black font-montserrat uppercase">${item.product_name}</p>
                                    <p class="text-gray-500">Ukuran: ${item.size} | Jumlah: ${item.quantity}</p>
                                    <p class="font-semibold text-black mt-1">Rp ${parseInt(item.price).toLocaleString('id-ID')},00</p>
                                </div>
                            </div>
                            `;
                        });
                    }

                    let statusClass = "bg-black text-white";
                    let actionButtons = '';
                    const st = (order.status || '').toLowerCase();
                    if (st === 'paid') {
                        statusClass = "bg-green-600 text-white";
                    } else if (st === 'pending') {
                        statusClass = "bg-yellow-500 text-black";
                        actionButtons = `
                            <button onclick="payOrder(${order.id})" class="text-[10px] text-white bg-black px-3 py-1.5 font-bold uppercase tracking-wider rounded font-montserrat mt-2 w-full hover:bg-neutral-800 transition-colors cursor-pointer">Bayar Sekarang</button>
                            <button onclick="cancelOrder(${order.id})" class="text-[10px] text-red-600 font-bold uppercase tracking-wider hover:underline font-montserrat mt-2 w-full text-center cursor-pointer">Batalkan</button>
                        `;
                    } else if (st === 'cancelled') {
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
                                <div class="mt-2 w-full flex flex-col gap-1">
                                    ${actionButtons}
                                </div>
                            </div>
                        </div>
                        ${itemsHtml}
                    </div>
                    `;
                });
            } catch(e) {
                console.error("Gagal memuat pesanan:", e);
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
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast("Gagal membatalkan pesanan.");
                }
            } catch (e) {
                console.error(e);
                showToast("Terjadi kesalahan sistem.");
            }
        }

        async function payOrder(id) {
            try {
                const response = await fetch(`/my-orders/${id}/pay`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                const data = await response.json();
                
                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: async function(result) {
                            showToast("Payment success!");
                            await fetch('/midtrans/local-success', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ order_id: result.order_id })
                            });
                            fetchOrderHistory();
                        },
                        onPending: async function(result) {
                            showToast("Waiting your payment...");
                            await fetch('/midtrans/local-success', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ order_id: result.order_id })
                            });
                            fetchOrderHistory();
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
                    showToast(data.error || "Gagal memproses pembayaran");
                }
            } catch (err) {
                console.error(err);
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
            if (document.getElementById('user-display-address')) {
                document.getElementById('user-display-address').innerText = loggedInUser.address ? loggedInUser.address : 'Belum ada alamat pengiriman.';
            }
            showToast('Berhasil Login!');
            showPage('view-account-profile');
            fetchOrderHistory();
        }

        function handleRegisterSubmit(e) {
            e.preventDefault();
            isAuthenticated = true;
            localStorage.setItem('notisse_auth', 'true');
            localStorage.setItem('notisse_user', JSON.stringify(loggedInUser));
            document.getElementById('user-display-email').innerText = loggedInUser.email;
            document.getElementById('user-display-name').innerText = loggedInUser.name;
            if (document.getElementById('user-display-address')) {
                document.getElementById('user-display-address').innerText = loggedInUser.address ? loggedInUser.address : 'Belum ada alamat pengiriman.';
            }
            showToast('Akun Berhasil Dibuat!');
            showPage('view-account-profile');
            fetchOrderHistory();
        }

        async function handleLogout() {
            try {
                await fetch('/logout', { method: 'POST' });
            } catch (e) {
                console.error(e);
            }
            isAuthenticated = false;
            loggedInUser = null;
            localStorage.removeItem('notisse_auth');
            localStorage.removeItem('notisse_user');
            window.location.reload();
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
        /* LOGIKA REAL-TIME / INSTANT LIVE SEARCH (FULL-SCREEN EDITORIAL) */
        function openSearch() {
            overlaySearch.classList.remove('-translate-y-full');
            document.body.style.overflow = 'hidden';
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 150);
                handleLiveSearch(searchInput.value || "");
            }
        }

        function closeSearch() {
            overlaySearch.classList.add('-translate-y-full');
            document.body.style.overflow = 'auto';
        }

        function applySearchQuery(query) {
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.value = query;
                handleLiveSearch(query);
            }
        }

        searchTrigger.addEventListener('click', openSearch);
        searchClose.addEventListener('click', closeSearch);

        // Close on ESC key
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeSearch();
                closeMenu();
                closeCart();
                closeArtworkOfferingModal();
                closeLightbox();
            }
        });

        function handleLiveSearch(query) {
            const gridContainer = document.getElementById('search-results-grid');
            const metaContainer = document.getElementById('search-results-meta');
            const countSpan = document.getElementById('search-results-count');

            const cleanQuery = (query || "").trim().toLowerCase();

            // Filter produk berdasarkan nama produk atau artis/deskripsi
            const filteredProducts = cleanQuery === "" 
                ? productsData 
                : productsData.filter(item => {
                    return item.name.toLowerCase().includes(cleanQuery) || 
                           (item.artist && item.artist.toLowerCase().includes(cleanQuery));
                });

            if (countSpan) countSpan.innerText = filteredProducts.length;
            if (metaContainer) {
                metaContainer.classList.remove('hidden');
                metaContainer.innerHTML = cleanQuery === "" 
                    ? `ALL COLLECTION PRODUCTS (<span id="search-results-count">${filteredProducts.length}</span>)` 
                    : `SEARCH RESULTS (<span id="search-results-count">${filteredProducts.length}</span>)`;
            }

            if (filteredProducts.length === 0) {
                gridContainer.innerHTML = `
                    <div class="col-span-full text-center py-20 text-gray-400 font-roboto text-sm space-y-2">
                        <p class="text-base text-black font-montserrat uppercase font-semibold">No products found matching "<span class="underline">${query}</span>"</p>
                        <p class="text-xs text-gray-400 font-roboto">Try searching for keywords like "Tee", "Tank", "Shirt", or "Blazer".</p>
                    </div>
                `;
                return;
            }

            // Render daftar produk secara instan ke grid dengan gambar asli
            let html = '';
            filteredProducts.forEach(item => {
                const escapedName = item.name.replace(/'/g, "\\'");
                html += `
                    <div onclick="selectSearchResult('${escapedName}', '${item.price}', '${item.image}')" 
                        class="group cursor-pointer flex flex-col justify-between bg-white border border-gray-100 hover:border-black transition-all duration-300 p-2.5 sm:p-3 shadow-sm hover:shadow-md">
                        <div class="relative w-full aspect-[3/4] bg-[#f4f4f4] mb-3 overflow-hidden">
                            <img src="${item.image}" alt="${item.name}" 
                                onerror="this.src='{{ asset('footage-baju.jpg') }}'"
                                class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="flex flex-col justify-between flex-grow pt-1 border-t border-gray-100">
                            <h4 class="font-montserrat font-semibold text-xs sm:text-sm text-black group-hover:underline truncate uppercase">${item.name}</h4>
                            <p class="font-montserrat text-xs text-gray-500 mt-1">${item.price}</p>
                        </div>
                    </div>
                `;
            });

            gridContainer.innerHTML = html;
        }

        function selectSearchResult(name, price, image) {
            closeSearch();
            openProductDetail(name, price, image);
        }

        /* CART DRAWER LOGIC */
        function openCart() {
            renderCartItems();
            overlayCart.classList.remove('hidden');
            const cartTrigger = document.getElementById('cart-trigger');
            if (cartTrigger) cartTrigger.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                overlayCart.classList.remove('opacity-0');
                cartDrawerContent.classList.remove('translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
            
            if (loggedInUser && loggedInUser.area_id && cartState.length > 0) {
                fetchShippingRates(loggedInUser.area_id);
            }
        }

        function closeCart() {
            cartDrawerContent.classList.add('translate-x-full');
            overlayCart.classList.add('opacity-0');
            setTimeout(() => {
                overlayCart.classList.add('hidden');
                document.body.style.overflow = 'auto';
                updateHeaderState();
            }, 300);
        }

        cartTrigger.addEventListener('click', openCart);
        cartClose.addEventListener('click', closeCart);

        function openProductDetail(productIdOrName, price, image) {
            let product = null;
            if (typeof productIdOrName === 'number') {
                product = productsData ? productsData.find(p => p.id === productIdOrName) : null;
            } else if (typeof productIdOrName === 'string') {
                product = productsData ? productsData.find(p => p.name === productIdOrName || String(p.id) === productIdOrName) : null;
            }

            if (product) {
                currentProduct = product;
            } else {
                currentProduct = {
                    id: typeof productIdOrName === 'number' ? productIdOrName : 1,
                    name: productIdOrName || 'Product',
                    price: price ? (typeof price === 'string' ? parseFloat(price.replace(/[^0-9]/g, '')) : price) : 250000,
                    stocks: [
                        { size: 'S', stock: 5 },
                        { size: 'M', stock: 10 },
                        { size: 'L', stock: 5 },
                        { size: 'XL', stock: 2 }
                    ]
                };
            }

            const titleEl = document.getElementById('pdetail-title');
            if (titleEl) titleEl.innerText = currentProduct.name;

            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(currentProduct.price);
            const priceEl = document.getElementById('pdetail-price');
            if (priceEl) priceEl.innerText = formattedPrice;

            if (image) {
                const imgEl = document.getElementById('pdetail-image');
                if (imgEl) imgEl.src = image;
            } else if (currentProduct.image) {
                const imgEl = document.getElementById('pdetail-image');
                if (imgEl) {
                    const imgSrc = currentProduct.image.startsWith('http') || currentProduct.image.startsWith('/') 
                        ? currentProduct.image 
                        : '/' + currentProduct.image;
                    imgEl.src = imgSrc;
                }
            }

            // Update Back View Image
            const backImgEl = document.getElementById('pdetail-back-image');
            if (backImgEl) {
                if (currentProduct.back_image) {
                    const bSrc = currentProduct.back_image.startsWith('http') || currentProduct.back_image.startsWith('/') 
                        ? currentProduct.back_image 
                        : '/' + currentProduct.back_image;
                    backImgEl.src = bSrc;
                } else {
                    backImgEl.src = "{{ asset('footage-baju-belakang.jpg') }}";
                }
            }

            // Update On-Model Footage Image
            const footImgEl = document.getElementById('pdetail-footage-image');
            if (footImgEl) {
                if (currentProduct.footage_image) {
                    const fSrc = currentProduct.footage_image.startsWith('http') || currentProduct.footage_image.startsWith('/') 
                        ? currentProduct.footage_image 
                        : '/' + currentProduct.footage_image;
                    footImgEl.src = fSrc;
                } else {
                    footImgEl.src = "{{ asset('footagebaju2.jpg') }}";
                }
            }

            // Update Size Chart Image & Lightbox Click
            const sizeChartBox = document.getElementById('pdetail-size-box');
            const sizeChartImg = document.getElementById('pdetail-size-img');
            const chartSrc = currentProduct.size_chart_image 
                ? (currentProduct.size_chart_image.startsWith('http') || currentProduct.size_chart_image.startsWith('/') ? currentProduct.size_chart_image : '/' + currentProduct.size_chart_image)
                : "{{ asset('size chart 1.png') }}";
            if (sizeChartImg) sizeChartImg.src = chartSrc;
            if (sizeChartBox) sizeChartBox.onclick = () => openLightbox(chartSrc, (currentProduct.name || 'NOTISSE APPAREL') + ' SIZE CHART');

            // Update Artist byline
            const artistEl = document.getElementById('pdetail-artist');
            if (artistEl) {
                const artistName = currentProduct.artist || 'Mustafa Alatas';
                artistEl.innerText = artistName;
                artistEl.onclick = () => openArtistProfile(artistName, 'Temanggung');
            }

            // Update Description & Fabric Specs
            const descContent = document.getElementById('pdetail-desc-content');
            if (descContent) {
                let html = '';
                if (currentProduct.specs) {
                    html += `<div class="mb-3 p-2.5 bg-neutral-50 border border-neutral-200 text-[11px] font-mono text-gray-800">
                                <strong>FABRIC & FIT SPECS:</strong> ${currentProduct.specs}
                             </div>`;
                }
                if (currentProduct.description) {
                    html += `<p class="leading-relaxed text-[11px] md:text-xs text-gray-600 mb-2">${currentProduct.description}</p>`;
                }
                if (!html) {
                    html = `<ul class="list-disc pl-5 space-y-1.5 leading-relaxed font-neue-montreal text-gray-500">
                                <li>Heavyweight 24s Cotton Combed construction</li>
                                <li>High-density screenprinted artwork on back and chest</li>
                                <li>Boxy relaxed fit with reinforced ribbed collar</li>
                                <li>Crafted & printed in Bandung, Indonesia</li>
                            </ul>`;
                }
                descContent.innerHTML = html;
            }

            const sizeMenu = document.getElementById('size-dropdown-menu');
            if (sizeMenu) {
                sizeMenu.innerHTML = '';
                if (currentProduct.stocks && currentProduct.stocks.length > 0) {
                    currentProduct.stocks.forEach(stock => {
                        if (stock.stock > 0) {
                            sizeMenu.innerHTML += `
                                <div onclick="selectSize('${stock.size}')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors border-b border-gray-100 flex justify-between items-center font-montserrat">
                                    <span>${stock.size} <span class="lowercase tracking-normal font-normal opacity-60 ml-2">(Sisa: ${stock.stock})</span></span><span class="text-xs opacity-50">${stock.size}</span>
                                </div>
                            `;
                        } else {
                            sizeMenu.innerHTML += `
                                <div class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider border-b border-gray-100 flex justify-between items-center font-montserrat text-gray-300 cursor-not-allowed">
                                    <span>${stock.size} (Habis)</span><span class="text-xs opacity-50">${stock.size}</span>
                                </div>
                            `;
                        }
                    });
                } else {
                    sizeMenu.innerHTML = '<div class="px-4 py-2.5 text-xs">Stock Tidak Tersedia</div>';
                }
            }

            const sizeLabel = document.getElementById('selected-size-label');
            if (sizeLabel) sizeLabel.innerText = 'Select size: ';
            currentSelectedSize = null;
            showPage('view-product-detail');
        }

        function openArtistProfile(name, location) {
            const artist = (typeof artistsData !== 'undefined' && artistsData) ? artistsData.find(a => (a.name || '').toLowerCase() === (name || '').toLowerCase()) : null;

            document.getElementById('artist-profile-name').innerText = name;
            document.getElementById('artist-profile-location').innerText = artist && artist.city ? artist.city : (location || 'Indonesia');
            document.getElementById('editorial-section-title').innerText = "Articles Featuring " + name;

            if (artist) {
                if (artist.bio) {
                    const bioEl = document.querySelector('#view-artist-detail .md\\:col-span-8 p.text-gray-700');
                    if (bioEl) bioEl.innerText = artist.bio;
                }
                if (artist.photo) {
                    const photoEl = document.querySelector('#view-artist-detail .md\\:col-span-4 img');
                    if (photoEl) {
                        const pSrc = artist.photo.startsWith('http') || artist.photo.startsWith('/') ? artist.photo : '/' + artist.photo;
                        photoEl.src = pSrc;
                    }
                }
            }

            // Populate Editorial Articles dynamically per artist
            const edGrid = document.getElementById('artist-editorial-grid');
            const edEmpty = document.getElementById('artist-editorial-empty');
            if (edGrid) {
                edGrid.innerHTML = '';
                const editorials = (artist && artist.editorials) ? artist.editorials : [];
                if (editorials.length > 0) {
                    if (edEmpty) edEmpty.classList.add('hidden');
                    edGrid.classList.remove('hidden');
                    editorials.forEach(ed => {
                        const edImg = ed.image ? (ed.image.startsWith('http') || ed.image.startsWith('/') ? ed.image : '/' + ed.image) : 'https://placehold.co/600x600/d9d9d9/555555?text=Editorial';
                        const card = document.createElement('div');
                        card.className = 'group cursor-pointer';
                        card.onclick = () => openArtistEditorialDetailById(ed.id);
                        card.innerHTML = `
                            <div class="w-full aspect-square bg-[#d9d9d9] mb-3 overflow-hidden relative">
                                <img src="${edImg}" alt="${ed.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                ${ed.video_url ? '<span class="absolute top-2.5 right-2.5 bg-black/80 text-white text-[9px] font-montserrat uppercase px-2 py-0.5 tracking-wider">Video</span>' : ''}
                            </div>
                            <h4 class="font-bold text-xs md:text-sm text-black font-montserrat group-hover:underline leading-snug">
                                ${ed.title}
                            </h4>
                            <p class="text-[11px] text-gray-500 font-roboto mt-1">${ed.author || 'Editorial Notisse'}</p>
                        `;
                        edGrid.appendChild(card);
                    });
                } else {
                    edGrid.classList.add('hidden');
                    if (edEmpty) edEmpty.classList.remove('hidden');
                }
            }

            // Populate Artworks dynamically per artist
            const artGrid = document.getElementById('artist-artwork-grid');
            const artEmpty = document.getElementById('artist-artwork-empty');
            if (artGrid) {
                artGrid.innerHTML = '';
                const artworks = (artist && artist.artworks) ? artist.artworks : [];
                if (artworks.length > 0) {
                    if (artEmpty) artEmpty.classList.add('hidden');
                    artGrid.classList.remove('hidden');
                    artworks.forEach(artw => {
                        const artImg = artw.image ? (artw.image.startsWith('http') || artw.image.startsWith('/') ? artw.image : '/' + artw.image) : 'https://placehold.co/600x800/d9d9d9/555555?text=Artwork';
                        const card = document.createElement('div');
                        card.className = 'group cursor-pointer';
                        card.onclick = () => openArtistArtworkDetail(artw.title, name, artw.medium, artw.dimensions, artImg);
                        card.innerHTML = `
                            <div class="w-full aspect-[3/4] bg-[#d9d9d9] mb-3 overflow-hidden relative">
                                <img src="${artImg}" alt="${artw.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <span class="absolute top-2.5 left-2.5 bg-black/75 backdrop-blur-sm text-white text-[9px] font-montserrat uppercase tracking-widest px-2 py-0.5">
                                    ${artw.status || 'Original'}
                                </span>
                            </div>
                            <h4 class="font-bold text-xs sm:text-sm text-black font-montserrat group-hover:underline">
                                ${artw.title}
                            </h4>
                            <p class="text-[11px] text-gray-500 font-roboto mt-0.5">${artw.medium || 'Artwork'} ${artw.dimensions ? '&bull; ' + artw.dimensions : ''}</p>
                        `;
                        artGrid.appendChild(card);
                    });
                } else {
                    artGrid.classList.add('hidden');
                    if (artEmpty) artEmpty.classList.remove('hidden');
                }
            }

            switchArtistTab('editorial');
            showPage('view-artist-detail');
        }

        function filterArtists(query) {
            const q = (query || '').toLowerCase().trim();
            const cards = document.querySelectorAll('#artist-collab-grid .artist-card');
            let matchCount = 0;
            cards.forEach(card => {
                const name = (card.getAttribute('data-artist-name') || '').toLowerCase();
                const city = (card.getAttribute('data-artist-city') || '').toLowerCase();
                if (!q || name.includes(q) || city.includes(q)) {
                    card.style.display = '';
                    matchCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            const noResults = document.getElementById('artist-no-results');
            if (noResults) {
                noResults.style.display = matchCount === 0 ? 'block' : 'none';
            }
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

        let currentActiveArtwork = {
            title: "Mereka Ulang Ungkapan Indah Leila",
            artist: "Mustafa Alatas",
            medium: "Oil on canvas",
            dimensions: "60x60cm",
            image: "https://placehold.co/1000x750/d9d9d9/555555?text=Full+Artwork+View"
        };

        function openArtistArtworkDetail(title, artist, medium, dimensions, image) {
            currentActiveArtwork = {
                title: title || 'Artwork',
                artist: artist || 'Mustafa Alatas',
                medium: medium || 'Oil on canvas',
                dimensions: dimensions || '60x60cm',
                image: image || 'https://placehold.co/1000x750/d9d9d9/555555?text=Full+Artwork+View'
            };

            const titleEl = document.getElementById('artwork-title');
            if (titleEl) titleEl.innerText = currentActiveArtwork.title;

            const artistEl = document.getElementById('artwork-artist-name');
            if (artistEl) artistEl.innerText = currentActiveArtwork.artist;

            const mediumEl = document.getElementById('artwork-medium');
            if (mediumEl) mediumEl.innerText = "Medium: " + currentActiveArtwork.medium;

            const dimEl = document.getElementById('artwork-dimensions');
            if (dimEl) dimEl.innerText = "Dimensions: " + currentActiveArtwork.dimensions;

            const imgEl = document.getElementById('artwork-detail-image');
            if (imgEl && currentActiveArtwork.image) imgEl.src = currentActiveArtwork.image;

            showPage('view-artist-artwork-detail');
        }

        /* ARTWORK OFFERING MODAL LOGIC */
        function triggerArtworkOffering(title, artist, medium, dimensions, image) {
            currentActiveArtwork = {
                title: title || 'Artwork',
                artist: artist || 'Mustafa Alatas',
                medium: medium || 'Oil on canvas',
                dimensions: dimensions || '60x60cm',
                image: image || 'https://placehold.co/400x500/222/fff?text=Artwork'
            };
            openArtworkOfferingModal();
        }

        function triggerArtworkOfferingCurrent() {
            openArtworkOfferingModal();
        }

        function openArtworkOfferingModal() {
            const modal = document.getElementById('modal-artwork-offering');
            const formBody = document.getElementById('artwork-offering-form-body');
            const successBody = document.getElementById('artwork-offering-success-body');

            // Populate Preview Info
            const prevImg = document.getElementById('offering-artwork-img');
            const prevTitle = document.getElementById('offering-artwork-title');
            const prevArtist = document.getElementById('offering-artwork-artist');
            const prevSpecs = document.getElementById('offering-artwork-specs');

            if (prevImg) prevImg.src = currentActiveArtwork.image || 'https://placehold.co/400x500/222/fff?text=Artwork';
            if (prevTitle) prevTitle.innerText = currentActiveArtwork.title;
            if (prevArtist) prevArtist.innerText = currentActiveArtwork.artist;
            if (prevSpecs) prevSpecs.innerText = `${currentActiveArtwork.medium} • ${currentActiveArtwork.dimensions}`;

            // Populate User Account Info from logged-in session / storage
            let activeUser = loggedInUser;
            try {
                const storedUser = localStorage.getItem('notisse_user');
                if (storedUser) activeUser = JSON.parse(storedUser);
            } catch (e) {}

            const userNameEl = document.getElementById('offering-user-name');
            const userEmailEl = document.getElementById('offering-user-email');
            const userAvatarEl = document.getElementById('offering-user-avatar');

            if (userNameEl) userNameEl.innerText = activeUser.name || 'John Doe';
            if (userEmailEl) userEmailEl.innerText = activeUser.email || 'john.doe@notisse.com';
            if (userAvatarEl) {
                const initials = (activeUser.name || 'John Doe').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                userAvatarEl.innerText = initials || 'NT';
            }

            // Reset form fields
            const priceInput = document.getElementById('offering-buyer-price');
            const messageInput = document.getElementById('offering-buyer-message');
            if (priceInput) priceInput.value = '';
            if (messageInput) messageInput.value = '';

            // Reset view state
            if (formBody) formBody.classList.remove('hidden');
            if (successBody) successBody.classList.add('hidden');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                const inner = modal.querySelector('div');
                if (inner) inner.classList.remove('scale-95');
            }, 10);
            document.body.style.overflow = 'hidden';
            updateHeaderState();
        }

        function closeArtworkOfferingModal() {
            const modal = document.getElementById('modal-artwork-offering');
            if (!modal) return;
            const inner = modal.querySelector('div');
            if (inner) inner.classList.add('scale-95');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                updateHeaderState();
            }, 300);
        }

        function formatOfferingPriceInput(input) {
            let val = input.value.replace(/\D/g, '');
            if (!val) {
                input.value = '';
                return;
            }
            input.value = new Intl.NumberFormat('id-ID').format(val);
        }

        async function handleArtworkOfferingSubmit(e) {
            e.preventDefault();
            const priceStr = document.getElementById('offering-buyer-price').value.trim();
            const message = document.getElementById('offering-buyer-message').value.trim();

            if (!priceStr) {
                showToast("Harap masukkan nominal tawaran");
                return;
            }

            const rawPrice = priceStr.replace(/\D/g, '');

            let activeUser = loggedInUser;
            try {
                const storedUser = localStorage.getItem('notisse_user');
                if (storedUser) activeUser = JSON.parse(storedUser);
            } catch (err) {}

            if (!activeUser) {
                showToast("Silakan login atau daftar terlebih dahulu.");
                return;
            }

            const btn = document.querySelector('#artwork-offering-form-body button[type="submit"]');
            const origText = btn.innerText;
            btn.innerText = "MENGIRIM...";
            btn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            try {
                const response = await fetch('/api/artwork-offerings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        artwork_id: currentActiveArtwork.id,
                        offering_price: rawPrice
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    const succRef = document.getElementById('success-offering-ref');
                    const succArtist = document.getElementById('success-artist-name');
                    const succTitle = document.getElementById('success-artwork-title');
                    const succPrice = document.getElementById('success-artwork-price');
                    const succBuyer = document.getElementById('success-buyer-name');

                    if (succRef) succRef.innerText = data.data.reference_number;
                    if (succArtist) succArtist.innerText = data.data.artist_name;
                    if (succTitle) succTitle.innerText = data.data.artwork_title;
                    if (succPrice) succPrice.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(data.data.offering_price) + " IDR";
                    if (succBuyer) succBuyer.innerText = `${data.data.buyer_name} (${data.data.buyer_email})`;

                    document.getElementById('artwork-offering-form-body').classList.add('hidden');
                    document.getElementById('artwork-offering-success-body').classList.remove('hidden');

                    showToast("Offering diajukan! Penawaran tercatat di sistem.");
                } else {
                    showToast(data.message || "Gagal mengajukan penawaran.");
                }
            } catch (err) {
                console.error(err);
                showToast("Terjadi kesalahan sistem saat mengirim penawaran.");
            } finally {
                btn.innerText = origText;
                btn.disabled = false;
            }
        }

        /* LIGHTBOX IMAGE VIEWER LOGIC (SIZE CHART & ARTWORK) */
        function openLightbox(src, caption) {
            const lightbox = document.getElementById('modal-image-lightbox');
            const img = document.getElementById('lightbox-img');
            const cap = document.getElementById('lightbox-caption');

            if (!lightbox || !img) return;

            img.src = src;
            if (cap) cap.innerText = caption || '';

            lightbox.classList.remove('hidden');
            setTimeout(() => {
                lightbox.classList.remove('opacity-0');
            }, 10);
            document.body.style.overflow = 'hidden';
            updateHeaderState();
        }

        function closeLightbox() {
            const lightbox = document.getElementById('modal-image-lightbox');
            if (!lightbox) return;

            lightbox.classList.add('opacity-0');
            setTimeout(() => {
                lightbox.classList.add('hidden');
                document.body.style.overflow = 'auto';
                updateHeaderState();
            }, 300);
        }

        function openArtistEditorialDetail(title) {
            showPage('view-artist-editorial-detail');
        }

        function openArtistEditorialDetailById(id) {
            let foundEd = null;
            let foundArtist = null;
            if (typeof artistsData !== 'undefined' && artistsData) {
                for (const a of artistsData) {
                    if (a.editorials) {
                        const ed = a.editorials.find(e => e.id == id);
                        if (ed) {
                            foundEd = ed;
                            foundArtist = a;
                            break;
                        }
                    }
                }
            }

            if (foundEd) {
                const titleEl = document.getElementById('editorial-detail-title');
                if (titleEl) titleEl.innerText = foundEd.title;

                const authorEl = document.getElementById('editorial-detail-author');
                if (authorEl) authorEl.innerText = (foundEd.author || 'Tim Editorial Notisse') + (foundArtist ? ' • Featuring ' + foundArtist.name : '');

                const contentContainer = document.getElementById('editorial-detail-content');
                if (contentContainer) {
                    contentContainer.innerHTML = '';
                    const text = foundEd.content || foundEd.excerpt || 'Dokumentasi editorial seniman Notisse.';
                    const paragraphs = text.split('\n');
                    paragraphs.forEach(p => {
                        const trimmed = p.trim();
                        if (trimmed) {
                            const pEl = document.createElement('p');
                            pEl.className = 'leading-relaxed text-xs md:text-sm text-gray-700 font-roboto';
                            pEl.innerText = trimmed;
                            contentContainer.appendChild(pEl);
                        }
                    });
                }

                const edImg = foundEd.image ? (foundEd.image.startsWith('http') || foundEd.image.startsWith('/') ? foundEd.image : '/' + foundEd.image) : 'https://placehold.co/400x500/222/fff?text=Editorial';
                const portraitEl = document.getElementById('editorial-detail-portrait');
                if (portraitEl) {
                    portraitEl.src = (foundArtist && foundArtist.photo) ? (foundArtist.photo.startsWith('http') || foundArtist.photo.startsWith('/') ? foundArtist.photo : '/' + foundArtist.photo) : edImg;
                }
                const artworkEl = document.getElementById('editorial-detail-artwork');
                if (artworkEl) artworkEl.src = edImg;

                const videoPlayer = document.getElementById('editorial-video-player');
                const videoSource = document.getElementById('editorial-video-source');
                const videoContainer = document.getElementById('editorial-video-container');
                if (foundEd.video_url && videoPlayer && videoSource) {
                    if (videoContainer) videoContainer.classList.remove('hidden');
                    const vSrc = foundEd.video_url.startsWith('http') || foundEd.video_url.startsWith('/') ? foundEd.video_url : '/' + foundEd.video_url;
                    videoSource.src = vSrc;
                    videoPlayer.load();
                }
            }

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

        let selectedShippingCost = 0;
        let selectedCourierName = "";
        let searchTimeout;

        function renderCartItems() {
            const container = document.getElementById('cart-items-container');
            const totalElement = document.getElementById('cart-total-price');
            const badgeElement = document.getElementById('header-cart-badge');
            const counterElement = document.getElementById('header-cart-counter');
            let totalAmount = 0;
            let totalItemCount = 0;

            if (!container) return;

            if (!cartState || cartState.length === 0) {
                container.innerHTML = `<div class="text-center py-16 text-gray-400 text-sm font-roboto">Your cart is empty.</div>`;
            } else {
                let html = '';
                cartState.forEach((item, index) => {
                    const itemPrice = item.unitPrice || item.price || 0;
                    const itemTotal = itemPrice * item.qty;
                    totalAmount += itemTotal;
                    totalItemCount += item.qty;

                    let itemImg = item.image || '';
                    if (itemImg) {
                        if (!itemImg.startsWith('http') && !itemImg.startsWith('/')) {
                            itemImg = '/' + itemImg;
                        }
                    } else {
                        itemImg = "{{ asset('footage-baju.jpg') }}";
                    }

                    html += `
                        <div class="grid grid-cols-12 items-center text-xs md:text-sm py-2 border-b border-gray-100 cart-animated-item" style="animation-delay: ${index * 70}ms">
                            <div class="col-span-6 md:col-span-5 flex items-center space-x-3">
                                <div class="w-12 h-16 bg-gray-100 flex-shrink-0 border border-gray-200 overflow-hidden">
                                    <img src="${itemImg}" alt="${item.name}" class="w-full h-full object-cover" onerror="this.src='{{ asset('footage-baju.jpg') }}'">
                                </div>
                                <div>
                                    <p class="font-semibold uppercase text-black text-xs md:text-sm font-montserrat">${item.name}</p>
                                    <p class="text-xs text-gray-500 font-roboto">Size: ${item.size}</p>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-3 flex justify-center">
                                <div class="flex items-center border border-gray-400">
                                    <button onclick="updateCartQty(${index}, -1)" class="px-2 py-1 hover:bg-gray-100 font-bold font-roboto cursor-pointer">−</button>
                                    <span class="px-3 py-1 font-semibold font-roboto">${item.qty}</span>
                                    <button onclick="updateCartQty(${index}, 1)" class="px-2 py-1 hover:bg-gray-100 font-bold font-roboto cursor-pointer">+</button>
                                </div>
                            </div>
                            <div class="hidden md:block md:col-span-2 text-right font-montserrat">${formatRupiah(itemPrice)}</div>
                            <div class="col-span-3 md:col-span-2 text-right font-medium font-montserrat">${formatRupiah(itemTotal)}</div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }

            const grandTotal = totalAmount + selectedShippingCost;
            if (totalElement) totalElement.innerText = formatRupiah(grandTotal) + ' IDR';
            if (badgeElement) badgeElement.innerText = totalItemCount;
            if (counterElement) counterElement.innerText = totalItemCount;

            saveCartState();
        }

        function triggerBadgePop() {
            const badge = document.getElementById('header-cart-badge');
            if (badge) {
                badge.classList.remove('cart-badge-pop');
                void badge.offsetWidth;
                badge.classList.add('cart-badge-pop');
            }
        }

        async function updateCartQty(index, change) {
            if (cartState[index]) {
                const item = cartState[index];
                const newQty = item.qty + change;
                if (newQty <= 0) {
                    await removeCartItem(index);
                } else {
                    try {
                        const response = await fetch(`/api/cart/${item.cart_item_id}`, {
                            method: 'PUT',
                            headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                            body: JSON.stringify({ quantity: newQty })
                        });
                        const data = await response.json();
                        if (data.success) {
                            cartState = data.items;
                            renderCartItems();
                            triggerBadgePop();
                        }
                    } catch (e) { console.error(e); }
                }
            }
        }

        async function removeCartItem(index) {
            if (cartState[index]) {
                const item = cartState[index];
                try {
                    const response = await fetch(`/api/cart/${item.cart_item_id}`, {
                        method: 'DELETE',
                        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}
                    });
                    const data = await response.json();
                    if (data.success) {
                        cartState = data.items;
                        renderCartItems();
                        triggerBadgePop();
                    }
                } catch (e) { console.error(e); }
            }
        }

        function addCrossSellItem() {
            showToast("Fitur Cross Sell sedang dalam perbaikan");
        }

        async function addSelectedToCart() {
            if (!currentProduct) {
                currentProduct = productsData && productsData.length > 0 ? productsData[0] : null;
            }
            if (!currentProduct) return;
            if (!currentSelectedSize) {
                showToast('Please select a size!');
                return;
            }

            try {
                const response = await fetch('/api/cart', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                    body: JSON.stringify({
                        product_id: currentProduct.id,
                        size: currentSelectedSize,
                        quantity: 1
                    })
                });
                const data = await response.json();
                if (data.success) {
                    cartState = data.items;
                    renderCartItems();
                    triggerBadgePop();
                    openCart();
                    showToast('Added to cart!');
                } else {
                    showToast(data.message || 'Gagal menambahkan ke keranjang');
                }
            } catch(e) {
                console.error(e);
                showToast('Terjadi kesalahan sistem.');
            }
        }

        async function searchAreaBiteship(keyword) {
            const areaResults = document.getElementById('area-results');
            const areaIdHidden = document.getElementById('selected-area-id');
            const areaInput = document.getElementById('area-search');

            clearTimeout(searchTimeout);
            if(!keyword || keyword.length < 3) {
                if (areaResults) areaResults.classList.add('hidden');
                return;
            }
            searchTimeout = setTimeout(async () => {
                try {
                    const res = await fetch(`/shipping-areas?keyword=${encodeURIComponent(keyword)}`);
                    const data = await res.json();
                    
                    if (!areaResults) return;
                    areaResults.innerHTML = '';
                    if (data.areas && data.areas.length > 0) {
                        data.areas.forEach(area => {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100';
                            div.innerText = `${area.name} - ${area.administrative_division_level_2_name}`;
                            div.onclick = () => {
                                areaInput.value = `${area.name}, ${area.administrative_division_level_2_name}`;
                                areaIdHidden.value = area.id;
                                areaResults.classList.add('hidden');
                                fetchShippingRates(area.id);
                            };
                            areaResults.appendChild(div);
                        });
                        areaResults.classList.remove('hidden');
                    }
                } catch (e) {
                    console.error("Gagal mengambil area", e);
                }
            }, 500);
        }

        async function fetchShippingRates(areaId) {
            const shippingOptions = document.getElementById('shipping-options');
            const shippingContainer = document.getElementById('shipping-options-container');

            if (shippingOptions) shippingOptions.innerHTML = '<span class="text-gray-400">Menghitung ongkos kirim...</span>';
            if (shippingContainer) shippingContainer.classList.remove('hidden');

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const items = cartState.map(item => ({
                id: item.id,
                name: item.name,
                price: item.unitPrice,
                quantity: item.qty
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
                if (shippingOptions) shippingOptions.innerHTML = '';
                
                if (data.pricing && data.pricing.length > 0) {
                    data.pricing.forEach(rate => {
                        const label = document.createElement('label');
                        label.className = 'flex items-center space-x-2 cursor-pointer';
                        label.innerHTML = `
                            <input type="radio" name="courier" value="${rate.price}" class="form-radio text-black" onchange="selectCourier('${rate.courier_name} ${rate.courier_service_name}', ${rate.price})">
                            <span>${rate.courier_name} ${rate.courier_service_name} - ${formatRupiah(rate.price)}</span>
                        `;
                        shippingOptions.appendChild(label);
                    });
                } else {
                    if (shippingOptions) shippingOptions.innerHTML = '<span class="text-red-500">Kurir tidak tersedia ke area ini.</span>';
                }
            } catch (e) {
                console.error("Gagal mengambil ongkir", e);
                if (shippingOptions) shippingOptions.innerHTML = '<span class="text-red-500">Terjadi kesalahan koneksi.</span>';
            }
        }

        window.selectCourier = function(name, cost) {
            selectedCourierName = name;
            selectedShippingCost = cost;
            renderCartItems(); // trigger recalc total in cart
        };

        function processCheckout() {
            if (cartState.length === 0) {
                showToast("Cart is empty!");
                return;
            }
            window.location.href = '/auth/checkout';
        }

        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            
            const lastPage = sessionStorage.getItem('current_page') || 'view-shop';
            showPage(lastPage);
            
            if(lastPage === 'view-account-profile' && loggedInUser) {
                fetchOrderHistory();
            }
            updateHeaderState();
            renderCartItems();
            startAutoSlide();
            
            if (urlParams.get('open_checkout') === 'true') {
                openCart();
            }
        });
        // ====== EDIT PROFILE LOGIC ======
        let editAreaSearchTimeout = null;
        const editAreaSearchInput = document.getElementById('edit-area-search');
        const editAreaResultsBox = document.getElementById('edit-area-results');

        function showEditProfile() {
            if (!loggedInUser) return;
            document.getElementById('edit-name').value = loggedInUser.name || '';
            document.getElementById('edit-phone').value = loggedInUser.phone || '';
            document.getElementById('edit-address').value = loggedInUser.address || '';
            document.getElementById('edit-area-id').value = loggedInUser.area_id || '';
            
            document.getElementById('edit-profile-error').classList.add('hidden');
            document.getElementById('edit-profile-success').classList.add('hidden');
            
            showPage('view-edit-profile');
        }

        if (editAreaSearchInput) {
            editAreaSearchInput.addEventListener('input', function(e) {
                clearTimeout(editAreaSearchTimeout);
                const keyword = e.target.value;
                
                if (keyword.length < 3) {
                    editAreaResultsBox.classList.add('hidden');
                    return;
                }

                editAreaSearchTimeout = setTimeout(() => {
                    fetch(`/shipping-areas?keyword=${keyword}`)
                        .then(res => res.json())
                        .then(data => {
                            editAreaResultsBox.innerHTML = '';
                            if (data.areas && data.areas.length > 0) {
                                data.areas.forEach(area => {
                                    const li = document.createElement('li');
                                    li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm font-roboto border-b border-gray-100';
                                    li.innerText = area.name;
                                    li.onclick = () => {
                                        editAreaSearchInput.value = area.name;
                                        document.getElementById('edit-area-id').value = area.id;
                                        editAreaResultsBox.classList.add('hidden');
                                    };
                                    editAreaResultsBox.appendChild(li);
                                });
                                editAreaResultsBox.classList.remove('hidden');
                            } else {
                                editAreaResultsBox.classList.add('hidden');
                            }
                        });
                }, 500);
            });

            document.addEventListener('click', function(e) {
                if (!editAreaSearchInput.contains(e.target) && !editAreaResultsBox.contains(e.target)) {
                    editAreaResultsBox.classList.add('hidden');
                }
            });
        }

        async function handleEditProfileSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('edit-profile-btn');
            const errorBox = document.getElementById('edit-profile-error');
            const successBox = document.getElementById('edit-profile-success');
            const originalText = btn.innerText;

            errorBox.classList.add('hidden');
            successBox.classList.add('hidden');

            const payload = {
                name: document.getElementById('edit-name').value,
                phone: document.getElementById('edit-phone').value,
                address: document.getElementById('edit-address').value,
                area_id: document.getElementById('edit-area-id').value,
            };

            if (!payload.area_id) {
                errorBox.innerText = 'Pilih kecamatan dari daftar pencarian terlebih dahulu.';
                errorBox.classList.remove('hidden');
                return;
            }

            btn.innerText = 'MENYIMPAN...';
            btn.disabled = true;

            try {
                const response = await fetch('/update-profile', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });
                
                const data = await response.json();
                if (response.ok && data.success) {
                    successBox.innerText = 'Profil berhasil diperbarui.';
                    successBox.classList.remove('hidden');
                    
                    // Update frontend state
                    loggedInUser.name = payload.name;
                    loggedInUser.phone = payload.phone;
                    loggedInUser.address = payload.address;
                    loggedInUser.area_id = payload.area_id;
                    
                    localStorage.setItem('notisse_user', JSON.stringify(loggedInUser));
                    document.getElementById('user-display-name').innerText = loggedInUser.name;
                    document.getElementById('user-display-address').innerText = loggedInUser.address;
                    
                    setTimeout(() => {
                        showPage('view-account-profile');
                    }, 1500);
                } else {
                    errorBox.innerText = data.message || 'Gagal menyimpan perubahan.';
                    errorBox.classList.remove('hidden');
                }
            } catch (err) {
                errorBox.innerText = 'Terjadi kesalahan jaringan.';
                errorBox.classList.remove('hidden');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        // Initial Cart & URL Trigger Setup
        document.addEventListener('DOMContentLoaded', () => {
            renderCartItems();

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('open_checkout') === 'true') {
                window.history.replaceState({}, document.title, window.location.pathname);
                setTimeout(() => {
                    openCart();
                    if (loggedInUser && loggedInUser.area_id && cartState.length > 0) {
                        fetchShippingRates(loggedInUser.area_id);
                    }
                }, 300);
            }
        });

    </script>
</body>

</html>