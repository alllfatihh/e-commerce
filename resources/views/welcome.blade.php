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

    <!-- Google Fonts Preconnect & Imports (Space Grotesk, Inter, Caveat) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

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
            src: local('Space Grotesk'), local('Helvetica Neue'), local('Arial');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background-color: #ffffff;
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

        .no-scrollbar::-webkit-scrollbar,
        .custom-scrollbar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .no-scrollbar,
        .custom-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }

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
            transform: scale(1.05) rotate(-0.8deg);
            opacity: 0.95;
            color: #ffffff !important;
        }

        .btn-brush:active {
            transform: scale(0.95) rotate(0.5deg);
            color: #ffffff !important;
        }

        .btn-brush:disabled {
            cursor: not-allowed;
            transform: none !important;
            opacity: 0.65;
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

        #overlay-menu-backdrop,
        #overlay-cart {
            backdrop-filter: blur(1px);
            -webkit-backdrop-filter: blur(1px);
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
            transition: opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.48s cubic-bezier(0.16, 1, 0.3, 1),
                        filter 0.35s ease;
            will-change: opacity, transform;
            transform-origin: center center;
        }

        .header-logo-small {
            transition: opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.48s cubic-bezier(0.16, 1, 0.3, 1),
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
            transform: scale(0.75);
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
            transform: scale(1);
            pointer-events: auto;
        }

        .header-logo-container:hover .header-logo-large {
            transform: scale(1.04);
            filter: brightness(1.12);
        }

        .header-logo-container:hover .header-logo-small {
            transform: scale(1.08);
            filter: brightness(1.15) drop-shadow(0 3px 8px rgba(0,0,0,0.12));
        }

        /* HEADER TRANSITIONS & TRANSPARENCY */
        #main-header {
            background-color: transparent !important;
            border-bottom: none !important;
            box-shadow: none !important;
        }

        #header-logo-container,
        #header-right-menu,
        #search-trigger,
        #menu-trigger {
            transition: opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity;
        }

        #overlay-search {
            transition: transform 0.48s cubic-bezier(0.16, 1, 0.3, 1) !important;
            will-change: transform;
        }

        .header-fade-hidden {
            opacity: 0 !important;
            pointer-events: none !important;
        }

        .header-fade-visible {
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        #search-trigger {
            margin-right: 32px !important;
        }

        /* ========================================================== */
        /* EXACT A24 MORPHING CART BUTTON WITH MOVING CONNECTING LINE */
        /* ========================================================== */
        .header__link--cart {
            display: inline-block;
            position: relative;
            width: 88px;
            height: 16px;
            z-index: 55;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            cursor: pointer;
            color: #000000;
            overflow: visible;
            vertical-align: middle;
            transition: opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            -webkit-tap-highlight-color: transparent;
        }

        .header__link--cart:hover {
            opacity: 0.65;
        }

        /* 1. CONNECTING LINE: L-SHAPE WITH 9PX BALANCED GAPS & SMOOTH MORPH */
        .header__link--cart::before {
            content: "";
            position: absolute;
            right: 0px;
            top: 7px;
            width: 35px; /* From left: 53px to right: 0px (left: 88px) -> 9px gap from 44px CART text */
            height: 16px; /* From top: 7px to top: 23px -> 9px gap to top: 32px counter */
            border-top: 1.4px solid currentColor;
            border-right: 1.4px solid currentColor;
            border-bottom: none;
            border-left: none;
            box-sizing: border-box;
            pointer-events: none;
            transition: width 0.35s cubic-bezier(0.16, 1, 0.3, 1) 0.08s,
                        height 0.38s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        top 0.38s cubic-bezier(0.16, 1, 0.3, 1) 0s;
            will-change: width, height, top;
        }

        /* On hover in horizontal state: line actively moves/stretches */
        .header__link--cart:not(.state-minimal):hover::before {
            width: 40px;
        }

        /* 2. DEFAULT HORIZONTAL LABEL */
        .header__link--cart__default-label {
            position: absolute;
            right: 44px; /* Anchored to right edge so it DOES NOT slide when width shrinks! */
            top: 0;
            width: 44px;
            height: 16px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.16em;
            line-height: 16px;
            text-transform: uppercase;
            opacity: 1;
            transition: opacity 0.38s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity;
        }

        /* 3. VERTICAL LABEL (APPEARS ON SCROLL - EXACT A24 NATURAL VERTICAL-RL) */
        .header__link--cart__vertical-label {
            position: absolute;
            right: -7px;
            top: 0px;
            width: 14px;
            text-align: center;
            writing-mode: vertical-rl;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.16em;
            line-height: 1;
            text-transform: uppercase;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.38s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity;
        }

        /* 4. MONOSPACE COUNTER */
        .header__link--cart__counter {
            position: absolute;
            right: -7px; /* Center-aligned with right-edge border at right: 0px */
            top: 32px; /* 9px gap below line height of 23px */
            width: 14px;
            text-align: center;
            font-family: 'Courier New', Courier, Monaco, monospace;
            font-size: 13px;
            font-weight: 600;
            line-height: 1;
            transition: top 0.38s cubic-bezier(0.16, 1, 0.3, 1) 0s;
            will-change: top;
        }

        /* ============================================================== */
        /* SCROLLED MINIMAL STATE (.state-minimal): EXACT A24 VERTICAL MORPH */
        /* ============================================================== */
        .header__link--cart.state-minimal {
            width: 88px;
            height: 16px;
        }

        /* Horizontal segment collapses to 0; Vertical segment stretches down smoothly */
        .header__link--cart.state-minimal::before {
            width: 0px !important;
            height: 32px !important;
            top: 45px !important;
            right: 0px !important;
            border-top: none !important;
            border-right: 1.4px solid currentColor !important;
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1) 0s,
                        height 0.38s cubic-bezier(0.16, 1, 0.3, 1) 0.08s,
                        top 0.38s cubic-bezier(0.16, 1, 0.3, 1) 0.08s !important;
        }

        /* On hover in minimal state: vertical line stretches down smoothly */
        .header__link--cart.state-minimal:hover::before {
            height: 42px !important;
            transition: height 0.28s cubic-bezier(0.16, 1, 0.3, 1) 0s !important;
        }

        .header__link--cart.state-minimal .header__link--cart__counter {
            top: 86px !important;
            right: -7px !important;
            transition: top 0.38s cubic-bezier(0.16, 1, 0.3, 1) 0.08s !important;
        }

        .header__link--cart.state-minimal:hover .header__link--cart__counter {
            top: 96px !important;
            transition: top 0.28s cubic-bezier(0.16, 1, 0.3, 1) 0s !important;
        }

        /* Pure FADE animations for CART label transition */
        .header__link--cart.state-minimal .header__link--cart__default-label {
            opacity: 0 !important;
            pointer-events: none !important;
            transition: opacity 0.32s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .header__link--cart.state-minimal .header__link--cart__vertical-label {
            opacity: 1 !important;
            pointer-events: auto !important;
            transition: opacity 0.42s cubic-bezier(0.16, 1, 0.3, 1) 0.08s !important;
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
            transition: transform 0.48s cubic-bezier(0.16, 1, 0.3, 1) !important;
            will-change: transform;
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
        
        /* WIEMER-STYLE PULL-UP PRELOADER */
        #global-loader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background-color: #d52c2b;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            overflow: hidden;
            user-select: none;
            will-change: transform;
            transition: transform 1.05s cubic-bezier(0.65, 0.01, 0.05, 0.99);
        }

        #global-loader.pulled-up {
            transform: translateY(-100%);
            pointer-events: none;
        }

        .loader-fade-out {
            opacity: 0 !important;
            transform: translateY(-10px) scale(0.98);
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        #loader-progress-bar {
            transform-origin: left center;
            will-change: transform;
            transition: transform 0.16s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>
</head>

<body
    class="min-h-screen flex flex-col justify-between relative bg-white text-black antialiased selection:bg-black selection:text-white">

    <!-- WIEMER-STYLE GLOBAL LOADING SCREEN -->
    <div id="global-loader">
        <!-- Center: Motion GIF Animation (No Background, Black Logo) -->
        <div id="loader-center-content" class="flex-1 flex flex-col items-center justify-center transition-all duration-400 px-4">
            <img src="{{ asset('motionnobg.gif') }}" alt="Notisse Motion" class="w-36 sm:w-48 md:w-56 h-auto opacity-95 pointer-events-none">
        </div>

        <!-- Bottom: Black Loading Bar (Flush at bottom edge) -->
        <div class="w-full h-[3.5px] sm:h-[4px] bg-black/15 relative overflow-hidden">
            <div id="loader-progress-bar"
                 class="h-full bg-black"
                 style="transform: scaleX(0);"></div>
        </div>
    </div>

    <script>
        // Immediate Execution of Wiemer-Style Preloader Logic
        (function initWiemerLoader() {
            const loader = document.getElementById('global-loader');
            if (!loader) return;

            const progressBar = document.getElementById('loader-progress-bar');
            const centerContent = document.getElementById('loader-center-content');

            // Temporarily lock scroll while preloader is active
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';

            let isFinishing = false;
            const duration = 4530; // Matches exact motionnobg.gif animation duration (151 frames * 30ms = 4530ms)
            let startTimestamp = null;
            let animationFrameId = null;

            // Smooth natural ease matching the handwriting drawing tempo
            function progressEase(t) {
                return t < 0.5 
                    ? 2 * t * t 
                    : 1 - Math.pow(-2 * t + 2, 2) / 2;
            }

            function updateUI(percent) {
                if (progressBar) {
                    progressBar.style.transform = `scaleX(${percent / 100})`;
                }
            }

            updateUI(0);

            function frameStep(timestamp) {
                if (!startTimestamp) startTimestamp = timestamp;
                const elapsed = timestamp - startTimestamp;
                const rawProgress = Math.min(1, elapsed / duration);
                const eased = progressEase(rawProgress);
                const targetPercent = Math.min(100, eased * 100);

                updateUI(targetPercent);

                if (rawProgress < 1) {
                    animationFrameId = requestAnimationFrame(frameStep);
                } else {
                    updateUI(100);
                    finishLoader();
                }
            }

            animationFrameId = requestAnimationFrame(frameStep);

            let finishTimeout1 = null;
            let finishTimeout2 = null;
            let finishTimeout3 = null;

            function finishLoader() {
                if (isFinishing) return;
                isFinishing = true;

                updateUI(100);

                // Hold briefly when logo animation finishes before curtain pull-up
                finishTimeout1 = setTimeout(() => {
                    if (centerContent) centerContent.classList.add('loader-fade-out');

                    // Signature Wiemer Curtain Pull-Up (translates up off-screen)
                    finishTimeout2 = setTimeout(() => {
                        loader.classList.add('pulled-up');

                        // Unlock page scroll
                        document.documentElement.style.overflow = '';
                        document.body.style.overflow = '';

                        // Remove from layout after slide-up finishes
                        finishTimeout3 = setTimeout(() => {
                            loader.style.display = 'none';
                        }, 1050);
                    }, 220);
                }, 180);
            }

            // Expose a replay helper for testing/previewing
            window.replayWiemerLoader = function() {
                if (animationFrameId) cancelAnimationFrame(animationFrameId);
                clearTimeout(finishTimeout1);
                clearTimeout(finishTimeout2);
                clearTimeout(finishTimeout3);
                loader.style.display = 'flex';
                loader.classList.remove('pulled-up');
                updateUI(0);
                if (centerContent) {
                    centerContent.classList.remove('loader-fade-out');
                    const img = centerContent.querySelector('img');
                    if (img) {
                        const baseSrc = img.src.split('?')[0];
                        img.src = baseSrc + '?t=' + Date.now();
                    }
                }
                document.documentElement.style.overflow = 'hidden';
                document.body.style.overflow = 'hidden';
                isFinishing = false;
                startTimestamp = null;
                animationFrameId = requestAnimationFrame(frameStep);
            };
        })();
    </script>

    <!-- GLOBAL PERSISTENT HEADER (A24-INSPIRED MINIMALIST LUXURY) -->
    <header id="main-header"
        class="fixed top-0 left-0 w-full z-50 bg-transparent px-5 sm:px-8 py-3.5 flex items-center justify-between transition-all duration-400 ease-[cubic-bezier(0.16,1,0.3,1)]">
        <!-- Left: Hamburger Icon -->
        <button id="menu-trigger" aria-label="Open Navigation Menu"
            class="p-2 -ml-2 text-black hover:opacity-60 hover:scale-105 active:scale-95 transition-all duration-300 cursor-pointer focus:outline-none flex items-center justify-center opacity-100 pointer-events-auto translate-x-0">
            <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
            </svg>
        </button>

        <!-- Center: Dual Animated Logo (Switches to Small Logo on Scroll) -->
        <div id="header-logo-container"
            class="header-logo-container logo-state-large header-fade-visible absolute left-1/2 transform -translate-x-1/2 cursor-pointer flex items-center justify-center select-none py-1 h-7 sm:h-8"
            onclick="showPage('view-shop')" title="Back to Shop">
            <!-- Large Brand Wordmark -->
            <img id="header-logo-large" src="{{ asset('logo.png') }}" alt="Notisse Logo"
                class="header-logo-large h-5 sm:h-6 md:h-6.5 w-auto object-contain select-none">
            <!-- Small Brand Monogram / Icon -->
            <img id="header-logo-small" src="{{ asset('logowebkecil.png') }}" alt="Notisse Icon"
                class="header-logo-small absolute h-5 sm:h-5.5 md:h-6 w-auto object-contain select-none">
        </div>

        <!-- Right: SEARCH & CART (PERFECTLY SEJAJAR IN SAME FLEX ROW) -->
        <div id="header-right-menu"
            class="flex items-center opacity-100 pointer-events-auto">
            <button id="search-trigger" aria-label="Search"
                class="header-fade-visible font-space-grotesk font-medium text-xs sm:text-[13px] tracking-[0.16em] uppercase text-black hover:opacity-50 transition-all focus:outline-none cursor-pointer p-0 m-0 h-[16px] leading-[16px] inline-flex items-center">
                SEARCH
            </button>

            <!-- EXACT A24 UNIFIED MORPHING CART TRIGGER -->
            <button id="cart-trigger" aria-label="Cart" onclick="openCart()"
                class="header__link--cart focus:outline-none select-none cursor-pointer opacity-100 pointer-events-auto">
                <span class="header__link--cart__default-label">CART</span>
                <span class="header__link--cart__vertical-label">CART</span>
                <span id="header-cart-badge" class="header__link--cart__counter font-bold">2</span>
            </button>
        </div>
    </header>

    <!-- OVERLAY 1: 1/4 SCREEN WIDTH NAVIGATION SIDEBAR -->
    <div id="overlay-menu-backdrop"
        class="fixed inset-0 bg-black/50 z-[60] hidden opacity-0 overlay-backdrop transition-opacity duration-300"
        onclick="closeMenu()">
        <div id="overlay-menu-sidebar"
            class="absolute left-0 top-0 bottom-0 w-full sm:w-88 md:w-96 lg:w-[28%] min-w-[280px] sm:min-w-[340px] bg-[#d52c2b] text-white p-7 md:p-12 transform -translate-x-full overlay-slide flex flex-col justify-between shadow-2xl"
            onclick="event.stopPropagation()">
            <div class="flex justify-end">
                <button id="menu-close" aria-label="Close Menu"
                    class="text-2xl sm:text-3xl font-light hover:rotate-90 hover:text-gray-200 transition-transform duration-300 focus:outline-none cursor-pointer">
                    &#10005;
                </button>
            </div>

            <nav class="flex flex-col space-y-6 md:space-y-8 my-auto font-space-grotesk">
                <a href="javascript:void(0)" onclick="navigateTo('view-shop')"
                    class="font-space-grotesk text-2xl sm:text-3xl md:text-[32px] font-medium tracking-wider hover:translate-x-2 hover:text-gray-200 transition-all duration-300 w-fit">SHOP</a>
                <a href="javascript:void(0)" onclick="navigateTo('view-artist-collab')"
                    class="font-space-grotesk text-2xl sm:text-3xl md:text-[32px] font-medium tracking-wider hover:translate-x-2 hover:text-gray-200 transition-all duration-300 w-fit">ARTIST COLLAB</a>
                <a href="javascript:void(0)" onclick="navigateTo('view-lookbook-list')"
                    class="font-space-grotesk text-2xl sm:text-3xl md:text-[32px] font-medium tracking-wider hover:translate-x-2 hover:text-gray-200 transition-all duration-300 w-fit">LOOKBOOK</a>
                <a href="javascript:void(0)" onclick="navigateTo('view-editorials-all')"
                    class="font-space-grotesk text-2xl sm:text-3xl md:text-[32px] font-medium tracking-wider hover:translate-x-2 hover:text-gray-200 transition-all duration-300 w-fit">EDITORIAL</a>
                <a href="javascript:void(0)" onclick="navigateTo('view-about')"
                    class="font-space-grotesk text-2xl sm:text-3xl md:text-[32px] font-medium tracking-wider hover:translate-x-2 hover:text-gray-200 transition-all duration-300 w-fit">ABOUT</a>
            </nav>

            <div class="flex items-center space-x-6 sm:space-x-8 font-space-grotesk mb-2">
                <a href="javascript:void(0)" onclick="closeMenu(); handleAccountButtonClick();"
                    class="text-base md:text-lg font-medium tracking-widest text-white/80 hover:text-white transition-all duration-300 w-fit">ACCOUNT</a>
                <a href="javascript:void(0)" onclick="navigateTo('view-contact')"
                    class="text-base md:text-lg font-medium tracking-widest text-white/80 hover:text-white transition-all duration-300 w-fit">CONTACT</a>
            </div>

        </div>
    </div>

    <!-- OVERLAY 2: FULL-SCREEN EDITORIAL LIVE SEARCH -->
    <div id="overlay-search"
        class="fixed inset-0 w-full h-full bg-white z-[90] transform -translate-y-full flex flex-col overflow-y-auto">
        <!-- Search Sticky Top Bar -->
        <div class="sticky top-0 bg-white/95 backdrop-blur-[1px] z-30 px-6 sm:px-10 py-4 sm:py-5 border-b border-gray-100 flex items-center justify-end">
            <button id="search-close" aria-label="Close Search"
                class="group text-lg sm:text-xl text-black hover:text-gray-500 transition-colors cursor-pointer focus:outline-none py-1 px-1">
                <span class="inline-block group-hover:rotate-90 transition-transform duration-300 leading-none">&#10005;</span>
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
                        class="w-full text-xl sm:text-3xl md:text-4xl bg-transparent focus:outline-none placeholder:text-gray-300 font-light font-inter py-1 text-black">
                    <button onclick="handleLiveSearch(document.getElementById('search-input').value)" aria-label="Submit Search"
                        class="ml-3 text-2xl md:text-3xl text-black hover:translate-x-1.5 transition-transform focus:outline-none cursor-pointer">
                        &rarr;
                    </button>
                </div>

                <!-- Quick Filter Suggestion Chips -->
                <div class="flex flex-wrap items-center gap-2 pt-4">
                    <span class="text-[11px] uppercase tracking-wider text-gray-400 font-space-grotesk mr-1 font-medium">Popular:</span>
                    <button onclick="applySearchQuery('Tee')" class="text-[11px] font-space-grotesk uppercase px-3 py-1 border border-gray-200 hover:border-black rounded-full hover:bg-black hover:text-white transition-colors cursor-pointer">Tee</button>
                    <button onclick="applySearchQuery('Raja Diamuk')" class="text-[11px] font-space-grotesk uppercase px-3 py-1 border border-gray-200 hover:border-black rounded-full hover:bg-black hover:text-white transition-colors cursor-pointer">Raja Diamuk</button>
                    <button onclick="applySearchQuery('Tank')" class="text-[11px] font-space-grotesk uppercase px-3 py-1 border border-gray-200 hover:border-black rounded-full hover:bg-black hover:text-white transition-colors cursor-pointer">Tank</button>
                    <button onclick="applySearchQuery('Shirt')" class="text-[11px] font-space-grotesk uppercase px-3 py-1 border border-gray-200 hover:border-black rounded-full hover:bg-black hover:text-white transition-colors cursor-pointer">Shirt</button>
                    <button onclick="applySearchQuery('Blazer')" class="text-[11px] font-space-grotesk uppercase px-3 py-1 border border-gray-200 hover:border-black rounded-full hover:bg-black hover:text-white transition-colors cursor-pointer">Blazer</button>
                </div>
            </div>

            <!-- Dynamic Search Result Header -->
            <div id="search-results-meta"
                class="text-xs uppercase tracking-widest text-gray-400 font-space-grotesk font-semibold mb-6 hidden">
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
                <h2 class="text-2xl md:text-3xl font-semibold tracking-tight font-space-grotesk">My cart</h2>
                <button id="cart-close" aria-label="Close Cart"
                    class="text-3xl font-light hover:rotate-90 transition-transform duration-300 focus:outline-none cursor-pointer">
                    &#10005;
                </button>
            </div>

            <div
                class="grid grid-cols-12 text-xs text-gray-500 font-regular pb-2 border-b border-gray-200 mb-6 font-space-grotesk">
                <div class="col-span-6 md:col-span-5">Product</div>
                <div class="col-span-3 md:col-span-3 text-center">Quantity</div>
                <div class="hidden md:block md:col-span-2 text-right">Price</div>
                <div class="col-span-3 md:col-span-2 text-right">Total</div>
            </div>

            <div id="cart-items-container" class="space-y-6 flex-grow"></div>

            <div class="mt-6 pt-6 border-t border-gray-200 flex flex-col items-end space-y-1">
                <div class="flex items-baseline space-x-6">
                    <span class="text-base md:text-lg font-medium text-gray-900 font-space-grotesk">Subtotal</span>
                    <span id="cart-total-price" class="text-xl md:text-xl font-regular font-space-grotesk">Rp 0,00 IDR</span>
                </div>
                <p class="text-xs text-gray-400 font-space-grotesk">Shipping will be calculated at checkout.</p>
                <div class="pt-4 w-full text-right">
                    <button onclick="window.location.href='/auth/checkout'"
                        class="btn-brush text-sm md:text-base cursor-pointer font-space-grotesk" id="checkout-btn">
                        CHECKOUT
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- OVERLAY 4: ARTWORK OFFERING MODAL (ACQUISITION INQUIRY) -->
    <div id="modal-artwork-offering"
        class="fixed inset-0 bg-black/40 backdrop-blur-[1px] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4 sm:p-6"
        onclick="closeArtworkOfferingModal()">
        <div class="bg-white w-full max-w-lg shadow-2xl border border-gray-200 transform scale-95 transition-transform duration-300 flex flex-col max-h-[92vh] overflow-hidden"
            onclick="event.stopPropagation()">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-white sticky top-0 z-10">
                <div>
                    <span class="text-[10px] font-bold tracking-[0.25em] uppercase text-gray-400 font-space-grotesk block">NOTISSE</span>
                    <h3 class="text-base sm:text-lg font-bold font-space-grotesk text-black tracking-tight">Artwork Offering / Buy Inquiry</h3>
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
                        <span class="text-[9px] font-semibold tracking-widest uppercase bg-black text-white px-1.5 py-0.5 inline-block font-space-grotesk mb-1">Original Piece</span>
                        <p id="offering-artwork-title" class="font-bold text-xs sm:text-sm text-black font-space-grotesk truncate">Mereka Ulang Ungkapan Indah Leila</p>
                        <p class="text-xs text-gray-600 font-inter">Artist: <span id="offering-artwork-artist" class="font-semibold text-black">Mustafa Alatas</span></p>
                        <p id="offering-artwork-specs" class="text-[11px] text-gray-500 font-inter">Oil on canvas &bull; 60x60cm</p>
                    </div>
                </div>

                <!-- Offering Form -->
                <form id="artwork-offering-form" onsubmit="handleArtworkOfferingSubmit(event)" class="space-y-4">
                    <!-- Account Identity Card (Logged in User) -->
                    <div class="p-3.5 bg-neutral-50 border border-gray-200 rounded-sm">
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-400 font-space-grotesk block mb-1.5">Collector Account (Registered in System)</span>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center font-bold text-xs font-space-grotesk flex-shrink-0" id="offering-user-avatar">
                                    JD
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-xs sm:text-sm text-black font-space-grotesk truncate" id="offering-user-name">John Doe</p>
                                    <p class="text-[11px] text-gray-500 font-inter truncate" id="offering-user-email">john.doe@notisse.com</p>
                                </div>
                            </div>
                            <span class="text-[9px] font-semibold tracking-wider uppercase text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded font-space-grotesk flex-shrink-0">
                                Verified Account
                            </span>
                        </div>
                    </div>

                    <!-- Acquisition Price Display -->
                    <div class="p-3.5 bg-neutral-50 border border-gray-200 rounded-sm">
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-400 font-space-grotesk block mb-1">Artwork Price</span>
                        <p id="offering-fixed-price-display" class="font-space-grotesk font-bold text-lg text-black">Rp 7.500.000</p>
                        <input type="hidden" id="offering-buyer-price" value="">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wider uppercase font-space-grotesk text-black mb-1">
                            Acquisition Notes (Optional)
                        </label>
                        <textarea id="offering-buyer-message" rows="3" placeholder="Write additional notes or placement intent for our curatorial team and artist..."
                            class="w-full border border-gray-300 px-3.5 py-2 text-sm font-inter focus:outline-none focus:border-black transition-colors resize-none"></textarea>
                    </div>

                    <div class="pt-2 flex justify-center">
                        <button type="submit" id="offering-submit-btn"
                            class="btn-brush w-full text-xs sm:text-sm font-bold tracking-[0.15em] uppercase cursor-pointer font-space-grotesk flex items-center justify-center space-x-2">
                            <span>CONFIRM ARTWORK ACQUISITION</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </form>

                <p class="text-[11px] text-gray-400 text-center font-inter">
                    Inquiry is recorded in your account registry and forwarded to Notisse curators and the artist.
                </p>
            </div>

            <!-- Success State Body (Official In-App Confirmation) -->
            <div id="artwork-offering-success-body" class="p-6 sm:p-8 hidden text-center space-y-5">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
                    &#10003;
                </div>
                <div>
                    <h4 class="text-lg sm:text-xl font-bold font-space-grotesk text-black">Inquiry Successfully Submitted!</h4>
                    <p class="text-xs text-gray-600 font-inter mt-2 max-w-sm mx-auto leading-relaxed">
                        Your inquiry is officially registered in Notisse system. The curatorial team and artist (<strong class="text-black" id="success-artist-name">Mustafa Alatas</strong>) will review your acquisition request.
                    </p>
                </div>

                <div class="p-4 bg-neutral-50 border border-gray-200 text-left space-y-2 text-xs font-inter">
                    <div class="flex justify-between border-b border-gray-200 pb-1.5">
                        <span class="text-gray-500 font-space-grotesk text-[11px] uppercase">Inquiry Ref:</span>
                        <span class="font-mono font-bold text-black" id="success-offering-ref">#NTS-ART-9281</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Collector:</span>
                        <span class="font-medium text-black" id="success-buyer-name">John Doe (john.doe@notisse.com)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Artwork:</span>
                        <span class="font-medium text-black truncate ml-2" id="success-artwork-title">Mereka Ulang Ungkapan Indah Leila</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Acquisition Value:</span>
                        <span class="font-bold text-emerald-700 font-space-grotesk" id="success-artwork-price">Rp 12.500.000 IDR</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-gray-500">Status:</span>
                        <span class="font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded text-[10px] uppercase font-space-grotesk">Under Curatorial Review</span>
                    </div>
                </div>

                <p class="text-[11px] text-gray-400 font-inter leading-relaxed">
                    Curatorial review outcome and instructions will be notified directly to your account and email.
                </p>

                <div class="pt-2 flex justify-center">
                    <button type="button" onclick="closeArtworkOfferingModal()"
                        class="btn-brush w-full text-xs font-bold tracking-wider uppercase cursor-pointer font-space-grotesk">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- OVERLAY 5: FULL-SCREEN IMAGE LIGHTBOX MODAL (ZOOM SIZE CHART & ARTWORKS) -->
    <div id="modal-image-lightbox"
        class="fixed inset-0 bg-white/95 backdrop-blur-[1px] hidden opacity-0 transition-opacity duration-300 flex flex-col items-center justify-center p-4 sm:p-8"
        onclick="closeLightbox()">
        <div class="absolute top-4 right-6 flex items-center space-x-4 z-20">
            <span id="lightbox-caption" class="text-xs text-gray-500 uppercase font-space-grotesk tracking-widest hidden sm:inline"></span>
            <button onclick="closeLightbox()" aria-label="Close Lightbox"
                class="text-3xl text-black hover:text-gray-600 hover:rotate-90 transition-transform duration-300 focus:outline-none cursor-pointer">
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
                <div id="hero-carousel-slides" class="relative w-full h-full">
                    @if(isset($carousels) && $carousels->count() > 0)
                        @foreach($carousels as $index => $cSlide)
                            @php
                                $cImg = Str::startsWith($cSlide->image, 'http') ? $cSlide->image : (file_exists(public_path('storage/' . $cSlide->image)) ? asset('storage/' . $cSlide->image) : asset($cSlide->image));
                            @endphp
                            <div class="hero-carousel-slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                                <img src="{{ $cImg }}" alt="{{ $cSlide->title ?? 'Slide ' . ($index + 1) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    @else
                        <div class="hero-carousel-slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-100">
                            <img src="{{ asset('home.jpg') }}" alt="Slide 1" class="w-full h-full object-cover">
                        </div>
                        <div class="hero-carousel-slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none">
                            <img src="{{ asset('home1.jpg') }}" alt="Slide 2" class="w-full h-full object-cover">
                        </div>
                        <div class="hero-carousel-slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0 pointer-events-none">
                            <img src="{{ asset('home2.png') }}" alt="Slide 3" class="w-full h-full object-cover">
                        </div>
                    @endif
                </div>

                <!-- OVERLAY MENU PADA CAROUSEL (A24 STYLE) -->
                <div class="absolute bottom-8 left-5 sm:left-8 md:bottom-12 md:left-10 lg:left-12 z-20 flex flex-col pointer-events-none select-none">
                    <a href="javascript:void(0)" onclick="document.getElementById('shop-catalog').scrollIntoView({behavior: 'smooth'})"
                        class="group flex items-start pointer-events-auto cursor-pointer -mb-1.5 sm:-mb-2 md:-mb-2.5 transition-opacity duration-300 text-white hover:opacity-70">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[4.9rem] font-medium tracking-tighter leading-[0.8] font-space-grotesk">Shop</span>
                    </a>
                    <a href="javascript:void(0)" onclick="navigateTo('view-artist-collab')"
                        class="group flex items-start pointer-events-auto cursor-pointer -mb-1.5 sm:-mb-2 md:-mb-2.5 transition-opacity duration-300 text-white hover:opacity-70">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[4.9rem] font-medium tracking-tighter leading-[0.8] font-space-grotesk">Artist Collab</span>
                    </a>
                    <a href="javascript:void(0)" onclick="navigateTo('view-lookbook-list')"
                        class="group flex items-start pointer-events-auto cursor-pointer -mb-1.5 sm:-mb-2 md:-mb-2.5 transition-opacity duration-300 text-white hover:opacity-70">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[4.9rem] font-medium tracking-tighter leading-[0.8] font-space-grotesk">Lookbook</span>
                    </a>
                    <a href="javascript:void(0)" onclick="navigateTo('view-editorials-all')"
                        class="group flex items-start pointer-events-auto cursor-pointer -mb-1.5 sm:-mb-2 md:-mb-2.5 transition-opacity duration-300 text-white hover:opacity-70">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[4.9rem] font-medium tracking-tighter leading-[0.8] font-space-grotesk">Editorial</span>
                    </a>
                    <a href="javascript:void(0)" onclick="navigateTo('view-about')"
                        class="group flex items-start pointer-events-auto cursor-pointer transition-opacity duration-300 text-white hover:opacity-70">
                        <span
                            class="text-5xl sm:text-6xl md:text-7xl lg:text-[4.9rem] font-medium tracking-tighter leading-[0.8] font-space-grotesk">About</span>
                    </a>
                </div>
            </div>


            <!-- PRODUCT CATALOG GRID -->
            <div id="shop-catalog" class="w-full">
                <div class="grid grid-cols-2 lg:grid-cols-4 bg-black gap-[1px] border-b border-black">
                    @foreach($products as $product)
                        <div onclick="openProductDetail({{ $product->id }})"
                            class="group cursor-pointer bg-white flex flex-col justify-between h-full hover:bg-gray-50 transition-colors duration-300">

                            <div class="relative w-full aspect-[3/4] bg-[#f4f4f4] overflow-hidden">
                                @php
                                    $pImg = $product->image ? (Str::startsWith($product->image, 'http') ? $product->image : (file_exists(public_path('storage/' . $product->image)) ? asset('storage/' . $product->image) : asset($product->image))) : asset('footage-baju.jpg');
                                    $pHovImg = $product->footage_image ? (Str::startsWith($product->footage_image, 'http') ? $product->footage_image : (file_exists(public_path('storage/' . $product->footage_image)) ? asset('storage/' . $product->footage_image) : asset($product->footage_image))) : asset('footagebaju2.jpg');
                                @endphp
                                <img src="{{ $pImg }}" alt="{{ $product->name }}"
                                    class="absolute inset-0 w-full h-full object-cover mix-blend-multiply transition-opacity duration-500 ease-out group-hover:opacity-0 z-10">
                                <img src="{{ $pHovImg }}" alt="{{ $product->name }} Hover"
                                    class="absolute inset-0 w-full h-full object-cover mix-blend-multiply transition-all duration-500 ease-out opacity-0 group-hover:opacity-100 group-hover:scale-105 z-0">
                            </div>

                            <div class="p-3 md:p-4 flex flex-col justify-between flex-grow border-t border-black">
                                <div
                                    class="flex flex-col xl:flex-row xl:justify-between items-start xl:items-center text-[10px] md:text-xs font-space-grotesk uppercase font-semibold text-black gap-1">
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
        <section id="view-product-detail" class="view-page w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-16">
            <div class="mb-4 sm:mb-6">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 xl:gap-16 items-start">
                <div id="pdetail-left-col" class="lg:col-span-5 space-y-8 lg:sticky lg:top-24 lg:max-h-[calc(100vh-9.5rem)] lg:overflow-y-hidden pr-2 pb-56 lg:pb-64 no-scrollbar">
                    <div>
                        <h1 id="pdetail-title"
                            class="text-2xl md:text-3xl font-semibold uppercase tracking-wider text-black font-space-grotesk">
                            RAJA DIAMUK MASSA TEE V1
                        </h1>
                        <p id="pdetail-price" class="text-lg md:text-xl font-medium mt-1 font-space-grotesk text-black">
                            RP 250.000,00
                        </p>
                        <p class="text-xs md:text-sm text-gray-600 mt-2 font-inter">
                            by <span id="pdetail-artist" onclick="openArtistProfile('Mustafa Alatas', 'Temanggung')"
                                class="underline hover:text-black cursor-pointer font-medium transition-colors font-inter">Mustafa
                                Alatas</span>
                        </p>
                    </div>

                    <div class="space-y-2 border-t border-b border-gray-200 py-2">
                        <div class="border-b border-gray-200">
                            <button onclick="toggleAccordion('desc')"
                                class="w-full flex justify-between items-center text-left py-3 focus:outline-none cursor-pointer group">
                                <span
                                    class="text-base md:text-lg font-medium text-gray-500 group-hover:text-black transition-colors font-space-grotesk">Product
                                    Description</span>
                                <svg id="acc-icon-desc" class="w-4 h-4 chevron-icon stroke-current text-gray-600"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="acc-content-desc" class="accordion-content">
                                <div
                                    class="accordion-inner pb-4 pt-1 text-xs sm:text-[13px] md:text-sm text-gray-500 font-inter"
                                    id="pdetail-desc-content">
                                    <ul class="list-disc pl-5 space-y-2 leading-relaxed font-inter">
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
                                    class="text-base md:text-lg font-medium text-gray-500 group-hover:text-black transition-colors font-space-grotesk">Size
                                    Guide</span>
                                <svg id="acc-icon-size" class="w-4 h-4 chevron-icon stroke-current text-gray-600"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="acc-content-size" class="accordion-content">
                                <div class="accordion-inner pb-4 pt-2">
                                    <div class="bg-neutral-50 p-2.5 text-center group cursor-zoom-in rounded-sm"
                                        onclick="openLightbox('{{ asset('size chart 1.png') }}', 'NOTISSE APPAREL SIZE CHART')">
                                        <img src="{{ asset('size chart 1.png') }}" alt="Notisse Size Chart"
                                            class="w-full max-w-sm mx-auto h-auto object-contain transition-transform duration-300 group-hover:scale-[1.02] shadow-sm">
                                        <p class="text-[10px] text-gray-500 font-inter uppercase tracking-wider mt-2.5 flex items-center justify-center space-x-1 font-medium">
                                            <svg class="w-3.5 h-3.5 stroke-current inline" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                            <span>Click image to enlarge</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button onclick="toggleAccordion('terms')"
                                class="w-full flex justify-between items-center text-left py-3 focus:outline-none cursor-pointer group">
                                <span
                                    class="text-base md:text-lg font-medium text-gray-500 group-hover:text-black transition-colors font-space-grotesk">Terms
                                    and Condition</span>
                                <svg id="acc-icon-terms" class="w-4 h-4 chevron-icon stroke-current text-gray-600"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="acc-content-terms" class="accordion-content">
                                <div
                                    class="accordion-inner pb-4 pt-1 text-xs sm:text-[13px] md:text-sm text-gray-500 font-inter">
                                    <ul class="list-disc pl-5 space-y-2 leading-relaxed font-inter">
                                        <li>All sales are final once order is confirmed.</li>
                                        <li>Exchanges strictly allowed for manufacturing defects within 3 days.</li>
                                        <li>Shipping fee calculated dynamically upon checkout.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MODEL FIT & MEASUREMENTS (TB, BB, SIZE) -->
                    <div class="pt-1 flex items-center space-x-2 text-xs sm:text-[13px] md:text-sm text-gray-600 font-inter">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p class="tracking-wide">
                            <span class="text-black font-semibold uppercase font-space-grotesk text-xs sm:text-[13px]">Model:</span> Height 178 cm / Weight 68 kg — wearing size <span class="font-bold text-black font-space-grotesk">L</span>
                        </p>
                    </div>

                    <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-6">
                        <div class="relative w-full sm:w-52 font-space-grotesk" id="custom-size-dropdown">
                            <button id="size-dropdown-trigger" onclick="toggleSizeDropdown(event)" type="button"
                                class="w-full border border-black py-2.5 px-3.5 text-xs md:text-sm font-medium uppercase tracking-wider bg-white text-black flex justify-between items-center cursor-pointer hover:bg-black hover:text-white transition-colors duration-200 font-space-grotesk">
                                <span id="selected-size-label" class="font-space-grotesk">Select size: </span>
                                <svg id="size-dropdown-chevron"
                                    class="w-4 h-4 chevron-icon stroke-current ml-2 flex-shrink-0" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="size-dropdown-menu"
                                class="custom-dropdown-menu absolute left-0 right-0 top-full mt-1.5 bg-white border border-black z-30 shadow-xl overflow-hidden font-space-grotesk">
                                <div onclick="selectSize('S')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors border-b border-gray-100 flex justify-between items-center font-space-grotesk">
                                    <span>Small</span><span class="text-xs opacity-50">S</span>
                                </div>
                                <div onclick="selectSize('M')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors border-b border-gray-100 flex justify-between items-center bg-gray-50 font-space-grotesk">
                                    <span>Medium</span><span class="text-xs opacity-50">M</span>
                                </div>
                                <div onclick="selectSize('L')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors border-b border-gray-100 flex justify-between items-center font-space-grotesk">
                                    <span>Large</span><span class="text-xs opacity-50">L</span>
                                </div>
                                <div onclick="selectSize('XL')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors flex justify-between items-center font-space-grotesk">
                                    <span>Extra Large</span><span class="text-xs opacity-50">XL</span>
                                </div>
                            </div>
                        </div>

                        <button onclick="addSelectedToCart()"
                            class="btn-brush text-xs md:text-sm uppercase cursor-pointer font-space-grotesk">
                            ADD TO CART
                        </button>
                    </div>
                </div>

                <div class="lg:col-span-7 w-full space-y-6 md:space-y-8">
                    <div class="w-full aspect-[3/4] bg-white overflow-hidden shadow-sm">
                        <img id="pdetail-image" src="{{ asset('footage-baju.jpg') }}" alt="Front View"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="w-full aspect-[3/4] bg-white overflow-hidden shadow-sm">
                        <img id="pdetail-back-image" src="{{ asset('footage-baju-belakang.jpg') }}" alt="Back Graphic"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="w-full aspect-[3/4] bg-white overflow-hidden shadow-sm">
                        <img id="pdetail-footage-image" src="{{ asset('footagebaju2.jpg') }}"
                            alt="Detail Close up" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 3: ARTIST COLLAB LIST PAGE -->
        <section id="view-artist-collab" class="view-page w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 sm:pt-24 pb-16">
            <div class="mb-16">
                <h2 class="text-xl md:text-2xl font-bold mb-6 tracking-wide text-black font-space-grotesk">Newcomer</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 lg:gap-10 mb-8">
                    @php
                        $ncPortrait = isset($newcomer) && $newcomer->portrait_image ? (Str::startsWith($newcomer->portrait_image, 'http') ? $newcomer->portrait_image : (file_exists(public_path('storage/' . $newcomer->portrait_image)) ? asset('storage/' . $newcomer->portrait_image) : (Str::startsWith($newcomer->portrait_image, 'storage/') ? asset($newcomer->portrait_image) : asset($newcomer->portrait_image)))) : asset('masmus.jpeg');
                        $ncArtwork = isset($newcomer) && $newcomer->artwork_image ? (Str::startsWith($newcomer->artwork_image, 'http') ? $newcomer->artwork_image : (file_exists(public_path('storage/' . $newcomer->artwork_image)) ? asset('storage/' . $newcomer->artwork_image) : (Str::startsWith($newcomer->artwork_image, 'storage/') ? asset($newcomer->artwork_image) : asset($newcomer->artwork_image)))) : asset('home.jpg');
                    @endphp
                    <div class="aspect-[16/10] bg-gray-200 overflow-hidden group cursor-pointer"
                        onclick="openArtistProfile('{{ addslashes($newcomer->name ?? 'Mustafa Alatas') }}', '{{ addslashes($newcomer->city ?? 'Temanggung') }}')">
                        <img src="{{ $ncPortrait }}"
                            alt="{{ $newcomer->name ?? 'Mustafa Portrait' }}"
                            class="w-full h-full object-cover grayscale group-hover:scale-105 transition-all duration-500">
                    </div>
                    <div class="aspect-[16/10] bg-gray-200 overflow-hidden group cursor-pointer"
                        onclick="openArtistProfile('{{ addslashes($newcomer->name ?? 'Mustafa Alatas') }}', '{{ addslashes($newcomer->city ?? 'Temanggung') }}')">
                        <img src="{{ $ncArtwork }}" alt="{{ $newcomer->name ?? 'Mustafa Artwork' }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                    </div>
                </div>

                <div class="max-w-3xl mx-auto text-center my-8 px-2 space-y-4">
                    <h3 class="text-2xl md:text-3xl font-bold text-black font-space-grotesk">About {{ explode(' ', $newcomer->name ?? 'Mustafa')[0] }}</h3>
                    <p class="text-gray-700 text-sm md:text-base leading-relaxed font-regular font-inter">
                        {{ $newcomer->bio ?? 'Mustafa Alatas is a contemporary visual artist based in Temanggung whose distinct brush strokes and visceral storytelling bring deep cultural narratives into modern street apparel.' }}
                    </p>
                    <div class="pt-2">
                        <button onclick="openArtistProfile('{{ addslashes($newcomer->name ?? 'Mustafa Alatas') }}', '{{ addslashes($newcomer->city ?? 'Temanggung') }}')"
                            class="btn-brush text-xs md:text-sm py-2 px-6 cursor-pointer font-space-grotesk">Read More</button>
                    </div>
                </div>
            </div>

            <!-- ARTIST COLLABORATOR SECTION -->
            <div class="pt-8 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-xl md:text-2xl font-bold tracking-wide text-black font-space-grotesk uppercase">
                        Artist Collaborator
                    </h2>
                    <!-- Search Bar Sejajar dengan Tulisan Artist Collaborator -->
                    <div class="relative w-full sm:w-72 md:w-80">
                        <input type="text" id="artist-search-input" oninput="filterArtists(this.value)"
                            placeholder="Search artist or city..."
                            class="w-full bg-transparent border-b border-black py-1.5 pl-7 pr-3 text-xs md:text-sm font-inter tracking-wide focus:outline-none focus:border-[#d52c2b] placeholder-gray-400 transition-colors">
                        <svg class="w-4 h-4 text-black absolute left-0 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-4.35z" />
                        </svg>
                    </div>
                </div>

                <div id="artist-collab-grid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8 lg:gap-10">
                    @if(isset($artists) && $artists->count() > 0)
                        @foreach($artists as $artItem)
                            @php
                                $aPhoto = $artItem->photo ? (Str::startsWith($artItem->photo, 'http') ? $artItem->photo : (file_exists(public_path('storage/' . $artItem->photo)) ? asset('storage/' . $artItem->photo) : asset($artItem->photo))) : 'https://placehold.co/500x500/181818/ffffff?text=' . urlencode($artItem->name);
                            @endphp
                            <div class="artist-card group cursor-pointer" data-artist-name="{{ $artItem->name }}" data-artist-city="{{ $artItem->city }}" onclick="openArtistProfile('{{ addslashes($artItem->name) }}', '{{ addslashes($artItem->city) }}')">
                                <div class="w-full aspect-square bg-gray-200 mb-3.5 overflow-hidden">
                                    <img src="{{ $aPhoto }}"
                                        alt="{{ $artItem->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <h4 class="font-bold text-base md:text-lg text-black font-space-grotesk">{{ $artItem->name }}</h4>
                                <p class="text-xs md:text-sm text-gray-500 font-inter mt-0.5">{{ $artItem->city ?: 'Indonesia' }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="artist-card group cursor-pointer" data-artist-name="Mustafa Alatas" data-artist-city="Temanggung" onclick="openArtistProfile('Mustafa Alatas', 'Temanggung')">
                            <div class="w-full aspect-square bg-gray-200 mb-3.5 overflow-hidden">
                                <img src="https://placehold.co/500x500/181818/ffffff?text=Mustafa+Alatas"
                                    alt="Mustafa Alatas"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <h4 class="font-bold text-base md:text-lg text-black font-space-grotesk">Mustafa Alatas</h4>
                            <p class="text-xs md:text-sm text-gray-500 font-inter mt-0.5">Temanggung</p>
                        </div>
                    @endif

                    <!-- Empty State for Artist Search -->
                    <div id="artist-no-results" class="hidden col-span-full text-center py-12">
                        <p class="text-gray-400 text-sm font-inter">No artist found matching your search.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 3B: ARTIST PROFILE PAGE -->
        <section id="view-artist-detail" class="view-page w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-16">
            <div class="mb-4 sm:mb-6">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO ARTISTS</span>
                </button>
            </div>

            <!-- Artist Header Bio -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 items-start mb-10">
                <div class="md:col-span-4 lg:col-span-3 aspect-[4/5] bg-gray-200 overflow-hidden">
                    <img src="https://placehold.co/600x750/333333/ffffff?text=Mustafa+Alatas" alt="Mustafa Alatas"
                        class="w-full h-full object-cover grayscale">
                </div>

                <div class="md:col-span-8 lg:col-span-9 space-y-4">
                    <h1 id="artist-profile-name" class="text-3xl sm:text-4xl md:text-5xl font-bold font-space-grotesk text-black tracking-tight">
                        Mustafa Alatas</h1>
                    <p id="artist-profile-location" class="text-lg sm:text-xl md:text-2xl text-gray-500 font-inter font-medium tracking-normal">Temanggung</p>
                    <p id="artist-profile-bio" class="text-base sm:text-lg md:text-xl lg:text-[1.2rem] text-gray-800 leading-relaxed font-inter pt-3 font-normal">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                        voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                        non proident, sunt in culpa qui officia deserunt mollit anim id est laborum...
                    </p>
                </div>
            </div>

            <!-- Tab Switcher (Artwork vs Editorial) -->
            <div class="border-b border-gray-200 mb-8 flex space-x-8 sm:space-x-10 text-sm sm:text-base md:text-lg">
                <button id="tab-btn-artwork" onclick="switchArtistTab('artwork')"
                    class="pb-3 font-medium text-gray-400 hover:text-black transition-colors focus:outline-none cursor-pointer font-space-grotesk">
                    Artwork
                </button>
                <button id="tab-btn-editorial" onclick="switchArtistTab('editorial')"
                    class="pb-3 font-medium text-black border-b-2 border-black transition-colors focus:outline-none cursor-pointer font-space-grotesk">
                    Editorial
                </button>
            </div>

            <!-- TAB CONTENT 1: EDITORIAL ARTICLES -->
            <div id="artist-tab-content-editorial" class="space-y-6">
                <h3 class="text-sm md:text-base font-bold font-space-grotesk text-black mb-6" id="editorial-section-title">
                    Articles Featuring Artist
                </h3>

                <div id="artist-editorial-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <!-- Populated dynamically via JS per artist -->
                </div>

                <div id="artist-editorial-empty" class="hidden text-center py-12 bg-gray-50 border border-gray-100 text-gray-400 font-inter text-sm">
                    No editorial articles published for this artist yet.
                </div>
            </div>

            <!-- TAB CONTENT 2: ARTWORK GALLERY -->
            <div id="artist-tab-content-artwork" class="hidden space-y-6">
                <div id="artist-artwork-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Populated dynamically via JS per artist -->
                </div>

                <div id="artist-artwork-empty" class="hidden text-center py-12 bg-gray-50 border border-gray-100 text-gray-400 font-inter text-sm">
                    No physical artworks published for this artist yet.
                </div>
            </div>
        </section>

        <!-- VIEW 3C: ARTWORK DETAIL PAGE (WITH DIRECT OFFERING ACQUISITION PANEL) -->
        <section id="view-artist-artwork-detail" class="view-page w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-16">
            <div class="mb-4 sm:mb-6 flex items-center justify-between">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO ARTIST PROFILE</span>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 xl:gap-16 items-start">
                <div class="lg:col-span-7 xl:col-span-8 aspect-[4/3] bg-[#d9d9d9] overflow-hidden shadow-sm relative group cursor-zoom-in"
                    onclick="openLightbox(document.getElementById('artwork-detail-image').src, document.getElementById('artwork-title').innerText)">
                    <img id="artwork-detail-image" src="https://placehold.co/1000x750/d9d9d9/555555?text=Full+Artwork+View" alt="Full Artwork"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                    <div class="absolute bottom-3 right-3 bg-black/70 backdrop-blur-[1px] text-white text-[10px] font-space-grotesk uppercase px-2.5 py-1 tracking-wider flex items-center space-x-1">
                        <svg class="w-3 h-3 stroke-current inline" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        <span>Click to Enlarge</span>
                    </div>
                </div>

                <div class="lg:col-span-5 xl:col-span-4 space-y-4 lg:sticky lg:top-24">
                    <div>
                        <h2 id="artwork-artist-name" class="text-xl md:text-2xl font-bold font-space-grotesk text-black">
                            Mustafa Alatas</h2>
                    </div>

                    <h3 id="artwork-title" class="text-base md:text-lg italic font-space-grotesk text-black">Mereka Ulang
                        Ungkapan Indah Leila</h3>

                    <div class="text-xs md:text-sm text-gray-700 font-inter space-y-1 py-1 border-y border-gray-100">
                        <p id="artwork-medium">Medium: Oil on canvas</p>
                        <p id="artwork-dimensions">Dimensions: 60x60cm</p>
                    </div>

                    <div class="py-2 border-y border-gray-100 my-2">
                        <span class="text-[10px] font-bold tracking-widest uppercase text-gray-400 font-space-grotesk block mb-0.5">Price</span>
                        <p id="artwork-price-display" class="font-space-grotesk font-bold text-xl md:text-2xl text-black">Rp 7.500.000</p>
                    </div>

                    <p class="text-xs md:text-sm text-gray-700 leading-relaxed font-inter pt-2">
                        Original physical artwork created exclusively by the artist. Accompanied by a certificate of authenticity signed by Notisse curatorial team.
                    </p>

                    <!-- DIRECT OFFERING ACQUISITION PANEL -->
                    <div class="pt-6 border-t border-gray-200 space-y-3.5">
                        <div class="p-3 bg-neutral-50 border border-gray-200 text-xs font-inter text-gray-600 space-y-1">
                            <div class="flex items-center justify-between font-space-grotesk">
                                <span class="font-semibold uppercase tracking-wider text-black text-[11px]">Acquisition Method:</span>
                                <span class="text-emerald-700 font-bold uppercase tracking-wider text-[11px]">Direct Acquisition / Inquiry</span>
                            </div>
                            <p class="text-[11px] text-gray-500 leading-relaxed pt-1">
                                Artwork acquisition differs from standard apparel checkout. Submitting an inquiry connects your acquisition directly to the artist and Notisse curatorial atelier at the designated value.
                            </p>
                        </div>

                        <div class="w-full flex justify-center">
                            <button onclick="triggerArtworkOfferingCurrent()"
                                class="btn-brush w-full text-xs md:text-sm font-bold tracking-[0.15em] uppercase cursor-pointer font-space-grotesk flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4 stroke-current text-white" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12m-6-6h12"/>
                                </svg>
                                <span>INQUIRE ACQUISITION / ACQUIRE ARTWORK</span>
                            </button>
                        </div>

                        <p class="text-[11px] text-gray-400 font-inter text-center leading-relaxed">
                            Your submitted acquisition inquiry will be recorded in the Notisse registry and verified directly with the curatorial team and artist.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 3C: ALL EDITORIALS ARCHIVE PAGE -->
        <section id="view-editorials-all" class="view-page w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-16">
            <div class="mb-4 sm:mb-6">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>
            <div class="mb-10 border-b border-gray-200 pb-5">
                <h1 class="text-2xl md:text-3xl font-bold font-space-grotesk text-black uppercase tracking-wider">Editorials</h1>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                @forelse($editorials as $ed)
                    @php
                        $edImg = $ed->image ? (Str::startsWith($ed->image, 'http') ? $ed->image : (file_exists(public_path('storage/' . $ed->image)) ? asset('storage/' . $ed->image) : asset($ed->image))) : 'https://placehold.co/600x600/222/fff?text=Editorial';
                    @endphp
                    <div onclick="openArtistEditorialDetailById({{ $ed->id }})" class="group cursor-pointer flex flex-col justify-between border border-gray-200 bg-white hover:border-black transition-colors duration-300">
                        <div class="relative w-full aspect-[16/10] bg-neutral-100 overflow-hidden">
                            <img src="{{ $edImg }}" alt="{{ $ed->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-5 flex flex-col justify-between flex-grow space-y-3">
                            <div>
                                <span class="text-[9px] font-bold tracking-widest uppercase text-gray-400 font-space-grotesk block mb-1">
                                    {{ $ed->artist ? $ed->artist->name : 'Notisse Editorial' }}
                                </span>
                                <h3 class="font-space-grotesk font-bold text-sm md:text-base text-black group-hover:underline line-clamp-2">
                                    {{ $ed->title }}
                                </h3>
                                @if($ed->excerpt)
                                    <p class="text-xs text-gray-600 font-inter mt-2 line-clamp-2">
                                        {{ $ed->excerpt }}
                                    </p>
                                @endif
                            </div>
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] font-space-grotesk font-semibold text-black uppercase">
                                <span>Read Essay</span>
                                <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-gray-400 font-inter">
                        No editorial articles published yet.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- VIEW 3D: EDITORIAL ARTICLE DETAIL PAGE -->
        <section id="view-artist-editorial-detail" class="view-page w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-16">
            <!-- Navigation -->
            <div class="mb-4 sm:mb-6">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>

            <!-- Fullscreen / Full-width Editorial Article Layout -->
            <article class="w-full space-y-8 md:space-y-10">
                <header class="space-y-3 pb-6 border-b border-gray-100">
                    <h1 id="editorial-detail-title" class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-space-grotesk text-black leading-[1.14] tracking-tight">
                        Mustafa Alatas Menafsir Kuasa dan Sosial dalam Lukisan Figuratif dan Simbolisme
                    </h1>
                    <div class="flex items-center space-x-3 text-xs sm:text-sm text-gray-500 font-inter pt-1">
                        <span id="editorial-detail-author" class="font-medium text-black">Laksa Dawantara</span>
                    </div>
                </header>

                <!-- Paragraf 1 (DI ATAS FOTO) -->
                <div id="editorial-detail-content-top" class="text-lg sm:text-xl md:text-2xl text-gray-900 leading-relaxed font-inter space-y-4 font-normal">
                    <p>
                        Di balik sapuan kuas yang kasar dan perpaduan pigmen mentah, Mustafa Alatas menyimpan refleksi panjang tentang dinamika kekuasaan dan identitas masyarakat kontemporer.
                    </p>
                </div>

                <!-- 2 FOTO (1 PORTRAIT 3:4 & 1 LANDSCAPE 4:3, SEJAJAR BAWAH & DIPERBESAR) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 my-8 pt-2 items-end">
                    <!-- Foto 1: Portrait (3:4) -->
                    <div class="self-end">
                        <div class="w-full aspect-[3/4] bg-[#d9d9d9] overflow-hidden mb-2.5 shadow-sm">
                            <img id="editorial-detail-portrait" src="{{ asset('masmus.jpeg') }}" alt="Portrait"
                                class="w-full h-full object-cover">
                        </div>
                        <p class="text-xs text-gray-500 font-inter leading-normal">Portrait of Mustafa Alatas.</p>
                    </div>
                    <!-- Foto 2: Landscape (4:3) -->
                    <div class="self-end">
                        <div class="w-full aspect-[4/3] bg-[#d9d9d9] overflow-hidden mb-2.5 shadow-sm">
                            <img id="editorial-detail-artwork" src="{{ asset('artwork.jpeg') }}" alt="Artwork"
                                class="w-full h-full object-cover">
                        </div>
                        <p class="text-xs text-gray-500 font-inter leading-normal">Raja Diamuk Massa, 2024. Courtesy of Medium.</p>
                    </div>
                </div>

                <!-- Paragraf 2 (DI BAWAH FOTO) -->
                <div id="editorial-detail-content-bottom" class="text-base sm:text-lg md:text-xl text-gray-700 leading-relaxed font-inter space-y-6 pt-2">
                    <p>
                        Dalam kolaborasi perdana bersama atelier Notisse, sang seniman asal Temanggung ini mentransformasi gestur visual dari kanvas fisik menuju medium pakaian berpotongan boxy murni katun 16s. Lukisannya tidak sekadar dipindahkan sebagai sablon, melainkan diolah ulang agar serat benang dan tekstur tinta plastisol menyatu harmonis dengan filosofi artistik Notisse.
                    </p>
                </div>

                <!-- Hidden element kept for JS references if any -->
                <img id="editorial-detail-featured" src="{{ asset('home.jpg') }}" alt="Editorial Feature" class="hidden">
            </article>
        </section>

        <!-- VIEW 4: LOOKBOOK INDEX PAGE (LEFT-ALIGNED MINIMALIST TIMELINE) -->
        <section id="view-lookbook-list"
            class="view-page min-h-[75vh] w-full px-6 sm:px-10 md:px-16 lg:px-20 py-16 md:py-24 flex flex-col justify-center items-start">
            <div class="relative pl-8 md:pl-12 my-auto text-left">
                <!-- Vertical Timeline Line -->
                <div class="absolute left-0 top-3 bottom-3 w-[1.5px] bg-gray-300"></div>

                <!-- Timeline Entries -->
                <div class="space-y-12 md:space-y-16">
                    @forelse($lookbooks as $index => $lb)
                    <div onclick="openLookbookDetail({{ $lb->id }})"
                        class="group relative flex items-center cursor-pointer select-none">
                        <!-- Round Dot Node -->
                        <div
                            class="absolute -left-[32px] md:-left-[48px] w-3.5 h-3.5 md:w-4 md:h-4 rounded-full {{ $index === 0 ? 'bg-gray-400 group-hover:bg-black' : 'bg-gray-300 group-hover:bg-black' }} border-2 border-white group-hover:scale-150 transition-all duration-300 shadow-sm">
                        </div>

                        <!-- Lookbook Text -->
                        <span
                            class="text-xl sm:text-2xl md:text-3xl font-light tracking-[0.2em] {{ $index === 0 ? 'text-black' : 'text-gray-400 group-hover:text-black' }} uppercase font-space-grotesk group-hover:translate-x-4 transition-all duration-300">
                            {{ $lb->title ?: ($lb->year . ' LOOKBOOK') }}
                        </span>
                    </div>
                    @empty
                    <div onclick="openLookbookDetail(null)"
                        class="group relative flex items-center cursor-pointer select-none">
                        <div class="absolute -left-[32px] md:-left-[48px] w-3.5 h-3.5 md:w-4 md:h-4 rounded-full bg-gray-400 border-2 border-white group-hover:bg-black group-hover:scale-150 transition-all duration-300 shadow-sm"></div>
                        <span class="text-xl sm:text-2xl md:text-3xl font-light tracking-[0.2em] text-black uppercase font-space-grotesk group-hover:translate-x-4 transition-all duration-300">
                            2025 LOOKBOOK
                        </span>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- VIEW 5: LOOKBOOK DETAIL PAGE WITH WIDE VIDEO AT TOP -->
        <section id="view-lookbook-detail" class="view-page min-h-screen pt-20 md:pt-24 pb-20">
            <div class="w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-2 pb-4">
                <button onclick="goBack()"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO LOOKBOOKS</span>
                </button>
            </div>

            <!-- WIDE CINEMATIC LOOKBOOK CAMPAIGN VIDEO (MELEBAR DI ATAS) -->
            <div id="lookbook-video-wrapper" class="w-full bg-black mb-10 relative overflow-hidden shadow-2xl">
                <div class="w-full aspect-[21/8] min-h-[220px] max-h-[380px] md:max-h-[420px] relative bg-black">
                    <video id="lookbook-video-player"
                        class="w-full h-full object-cover"
                        autoplay loop muted playsinline controls
                        poster="{{ asset('home.jpg') }}">
                        <source id="lookbook-video-source" src="{{ asset('video/editorial.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <!-- Overlaid Thin Blur Glass Tint & Centered Title -->
                    <div class="absolute inset-0 bg-black/15 pointer-events-none flex items-center justify-center p-4 sm:p-8"
                        style="backdrop-filter: blur(1px); -webkit-backdrop-filter: blur(1px);">
                        <h2 id="lookbook-detail-title"
                            class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-medium tracking-[0.22em] text-white uppercase font-space-grotesk text-center drop-shadow-[0_2px_15px_rgba(0,0,0,0.5)] select-none">
                            2025 LOOKBOOK
                        </h2>
                    </div>
                </div>
            </div>

            <div class="w-full px-4 sm:px-6 md:px-10 lg:px-12">
                <div id="lookbook-photos-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-8 md:gap-12">
                    @foreach($fotoshoots as $foto)
                        <div class="w-full group cursor-pointer" onclick="openLightbox('{{ Str::startsWith($foto->image, 'http') ? $foto->image : (file_exists(public_path('storage/' . $foto->image)) ? asset('storage/' . $foto->image) : asset($foto->image)) }}', '{{ addslashes($foto->title ?? '') }}')">
                            <div class="overflow-hidden bg-[#d9d9d9] relative">
                                @php
                                    $lbUrl = Str::startsWith($foto->image, 'http') ? $foto->image : (file_exists(public_path('storage/' . $foto->image)) ? asset('storage/' . $foto->image) : asset($foto->image));
                                @endphp
                                <img src="{{ $lbUrl }}" alt="{{ $foto->title }}"
                                    class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                @if($foto->title)
                                    <div
                                        class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span
                                            class="text-white font-space-grotesk tracking-[0.2em] uppercase text-sm md:text-base">{{ $foto->title }}</span>
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
                    class="text-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl 2xl:text-[5.5vw] font-bold leading-[1.18] sm:leading-[1.18] tracking-tight font-space-grotesk">
                    NOTISSE IS AN ALTERNATIVE MOVEMENT IN ART EXHIBITION. WOVEN INTO THE FABRIC OF EVERYDAY LIFE. SO ART BECOMES PART OF DAILY ACTIVITY NOT A SEPARATE OCCASION.
                </p>
            </div>
        </section>

        <!-- VIEW 6B: CONTACT PAGE -->
        <section id="view-contact"
            class="view-page px-6 sm:px-12 md:px-16 lg:px-20 xl:px-24 py-20 md:py-28 min-h-[calc(100vh-80px)] flex flex-col justify-center items-start w-full">
            <div class="space-y-10 sm:space-y-12 text-left max-w-3xl">
                <!-- Workshop Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-normal text-black tracking-tight font-space-grotesk">Workshop :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide font-inter">
                        Jl Mangga Dua, Desa Sukapura, DayeuhKolot, Bandung.
                    </p>
                </div>

                <!-- Email Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-normal text-black tracking-tight font-space-grotesk">Email :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide font-inter">
                        notissebrand@gmail.com
                    </p>
                </div>

                <!-- Whatsapp Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-normal text-black tracking-tight font-space-grotesk">Whatsapp :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide font-inter">
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
                <h2 class="text-2xl font-bold font-space-grotesk uppercase tracking-wider text-black">Login Account</h2>
                <p class="text-xs text-gray-500 font-inter mt-1">Sign in to access your collection and orders.</p>
            </div>

            <form onsubmit="handleLoginSubmit(event)" class="space-y-4">
                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1 font-space-grotesk">Email</label>
                    <input type="email" required placeholder="name@email.com"
                        class="w-full border border-black rounded-lg p-3 text-sm focus:outline-none font-inter">
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1 font-space-grotesk">Password</label>
                    <input type="password" required placeholder="••••••••"
                        class="w-full border border-black rounded-lg p-3 text-sm focus:outline-none font-inter">
                </div>

                <div class="flex items-center justify-between text-xs font-inter pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="accent-black">
                        <span class="text-gray-600">Remember me</span>
                    </label>
                    <a href="javascript:void(0)" onclick="showToast('Password reset instructions sent to your email.')"
                        class="underline text-gray-500 hover:text-black">Forgot password?</a>
                </div>

                <div class="pt-4 flex justify-center">
                    <button type="submit" class="btn-brush w-full text-sm uppercase cursor-pointer font-space-grotesk">
                        LOG IN
                    </button>
                </div>

                <div class="text-center pt-4">
                    <p class="text-xs text-gray-500 font-inter">
                        Don't have an account yet?
                        <a href="javascript:void(0)" onclick="showPage('view-register')"
                            class="font-bold underline text-black">Register now</a>
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
                <h2 class="text-center font-space-grotesk text-lg sm:text-xl font-medium text-black leading-snug px-2">
                    Join and engage with the creative journey of our artists
                </h2>

                <!-- Registration Form -->
                <form onsubmit="handleRegisterSubmit(event)" class="space-y-4 pt-2">
                    <div>
                        <input type="text" required placeholder="Full Name"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div>
                        <input type="email" required placeholder="Email Address"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div>
                        <input type="password" required placeholder="Password"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div>
                        <input type="password" required placeholder="Confirm Password"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div class="pt-4 flex justify-center">
                        <button type="submit" class="btn-brush w-full text-sm uppercase cursor-pointer font-space-grotesk">
                            CREATE ACCOUNT
                        </button>
                    </div>
                </form>

                <div class="text-center pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-500 font-inter">
                        Already have an account?
                        <a href="javascript:void(0)" onclick="showPage('view-login')"
                            class="font-bold underline text-black">Log in here</a>
                    </p>
                </div>
            </div>
        </section>

        <!-- VIEW 9: LOGGED-IN ACCOUNT DASHBOARD -->
        <section id="view-account-profile" class="view-page w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-24">
            <!-- Back to Shop (Top Left) -->
            <div class="mb-6">
                <button onclick="showPage('view-shop')"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO SHOP</span>
                </button>
            </div>

            <!-- Profile Content Container (Full Width / Full Screen) -->
            <div class="w-full space-y-6">

            <!-- Profile Hero Header Banner -->
            <div class="border border-black bg-white p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
                <div class="flex items-center space-x-5">
                    @if(auth()->check() && auth()->user()->avatar)
                        @php
                            $userAvatar = Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar);
                        @endphp
                        <img src="{{ $userAvatar }}" alt="Avatar" class="w-16 h-16 object-cover border border-black shrink-0">
                    @else
                        <div class="w-16 h-16 bg-black text-white flex items-center justify-center font-bold text-xl font-space-grotesk tracking-wider border border-black shrink-0">
                            {{ auth()->check() ? strtoupper(substr(auth()->user()->name ?? 'N', 0, 2)) : 'NT' }}
                        </div>
                    @endif
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-xl md:text-2xl font-bold font-space-grotesk uppercase tracking-tight text-black" id="account-header-name">
                                {{ auth()->check() ? auth()->user()->name : 'Notisse Member' }}
                            </h1>
                            <span class="text-[9px] font-bold font-space-grotesk tracking-widest uppercase bg-black text-white px-2 py-0.5">VERIFIED CLIENT</span>
                        </div>
                        <p id="user-display-email" class="text-xs text-neutral-500 font-inter">
                            {{ auth()->check() ? auth()->user()->email : 'user@notisse.com' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 self-start md:self-auto">
                    <button onclick="showEditProfile()"
                        class="px-4 py-2 border border-black hover:bg-black hover:text-white transition-colors text-xs font-semibold uppercase tracking-wider font-space-grotesk cursor-pointer">
                        Edit Profile
                    </button>
                    <button onclick="handleLogout()"
                        class="px-4 py-2 bg-neutral-100 hover:bg-red-600 hover:text-white text-neutral-700 transition-colors text-xs font-semibold uppercase tracking-wider font-space-grotesk cursor-pointer">
                        Logout
                    </button>
                </div>
            </div>

            <!-- Profile Section Tabs -->
            <div class="flex border-b border-gray-200 gap-2 sm:gap-6 overflow-x-auto">
                <button onclick="switchAccountTab('orders')" id="acc-tab-orders"
                    class="acc-tab-btn active pb-3 text-xs sm:text-sm font-bold font-space-grotesk uppercase tracking-wider border-b-2 border-black text-black cursor-pointer whitespace-nowrap">
                    Order History
                </button>
                <button onclick="switchAccountTab('address')" id="acc-tab-address"
                    class="acc-tab-btn pb-3 text-xs sm:text-sm font-medium font-space-grotesk uppercase tracking-wider border-b-2 border-transparent text-gray-400 hover:text-black transition-colors cursor-pointer whitespace-nowrap">
                    Address Book
                </button>
                <button onclick="switchAccountTab('offers')" id="acc-tab-offers"
                    class="acc-tab-btn pb-3 text-xs sm:text-sm font-medium font-space-grotesk uppercase tracking-wider border-b-2 border-transparent text-gray-400 hover:text-black transition-colors cursor-pointer whitespace-nowrap">
                    Artwork Inquiries
                </button>
            </div>

            <!-- TAB 1: ORDER HISTORY -->
            <div id="acc-pane-orders" class="space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold font-space-grotesk text-black uppercase tracking-wider">Orders & Purchases</h2>
                        <p class="text-xs text-gray-400 font-inter mt-0.5">Notisse product purchases and order history.</p>
                    </div>
                    <button onclick="fetchOrderHistory()" class="text-xs font-space-grotesk font-semibold uppercase tracking-wider hover:underline text-gray-500 hover:text-black">
                        Refresh
                    </button>
                </div>

                <div id="order-history-container" class="space-y-4">
                    <div class="border border-gray-200 p-8 text-center bg-gray-50">
                        <p class="text-gray-400 font-inter text-sm">Loading orders...</p>
                    </div>
                </div>
            </div>

            <!-- TAB 2: ADDRESS BOOK -->
            <div id="acc-pane-address" class="hidden space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold font-space-grotesk text-black uppercase tracking-wider">Primary Shipping Address</h2>
                        <p class="text-xs text-gray-400 font-inter mt-0.5">This address is used for shipping calculations during checkout.</p>
                    </div>
                    <button onclick="showEditProfile()" class="text-xs font-space-grotesk font-bold uppercase tracking-wider text-black underline">
                        Change Address
                    </button>
                </div>

                <div class="border border-gray-200 p-6 md:p-8 bg-white space-y-4">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-4">
                        <div class="flex items-center gap-4">
                            @if(auth()->check() && auth()->user()->avatar)
                                @php
                                    $userAvatarMobile = Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar);
                                @endphp
                                <img src="{{ $userAvatarMobile }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <span class="text-[10px] font-bold font-space-grotesk tracking-widest uppercase bg-neutral-100 text-neutral-600 px-2 py-0.5 inline-block mb-1.5">User Profile</span>
                                <h3 class="font-bold text-black text-base font-space-grotesk" id="user-display-name">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</h3>
                                <p class="text-xs text-gray-500 font-inter mt-0.5" id="user-display-phone">{{ auth()->check() && auth()->user()->phone ? auth()->user()->phone : 'No phone number set' }}</p>
                                <p class="text-xs text-gray-500 font-inter mt-0.5" id="user-display-birthdate">
                                    {{ auth()->check() && auth()->user()->birthdate ? 'Date of Birth: ' . \Carbon\Carbon::parse(auth()->user()->birthdate)->format('d M Y') : '' }}
                                </p>
                            </div>
                        </div>
                        <button onclick="showEditProfile()" class="text-xs font-semibold uppercase tracking-wider text-black hover:underline font-space-grotesk">
                            Edit
                        </button>
                    </div>
                    <div>
                        <p class="text-xs font-inter text-gray-700 leading-relaxed" id="user-display-address">
                            {{ auth()->check() && auth()->user()->address ? auth()->user()->address : 'No shipping address saved yet. Please add an address for seamless checkout.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- TAB 3: ARTWORK OFFERS INQUIRIES -->
            <div id="acc-pane-offers" class="hidden space-y-6">
                <div>
                    <h2 class="text-base font-bold font-space-grotesk text-black uppercase tracking-wider">Artwork Acquisition Inquiries</h2>
                    <p class="text-xs text-gray-400 font-inter mt-0.5">List of artwork acquisition offers submitted for curatorial review.</p>
                </div>

                <div id="account-art-offers-list" class="space-y-4">
                    <div class="border border-gray-200 p-8 text-center bg-gray-50">
                        <p class="text-gray-400 font-inter text-sm">No artwork acquisition inquiries submitted yet.</p>
                        <button onclick="showPage('view-artist-detail')" class="mt-3 text-xs font-bold font-space-grotesk uppercase underline tracking-wider">
                            Explore Art Gallery
                        </button>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <!-- VIEW 10: EDIT PROFILE / ALAMAT -->
        <section id="view-edit-profile" class="view-page w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-24">
            <div class="mb-6">
                <button onclick="goBack()" class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>

            <div class="max-w-xl mx-auto space-y-6">
                <div class="text-center">
                    <h1 class="text-xl md:text-2xl font-bold font-space-grotesk uppercase text-black">Edit Profile & Address</h1>
                </div>

                <div id="edit-profile-error" class="hidden bg-red-100 text-red-700 p-3 text-sm border border-red-200"></div>
                <div id="edit-profile-success" class="hidden bg-green-100 text-green-700 p-3 text-sm border border-green-200"></div>

                <form onsubmit="handleEditProfileSubmit(event)" class="space-y-6 bg-white border border-gray-200 p-6 md:p-8">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Profile Picture</label>
                        <input type="file" id="edit-avatar" accept="image/*" class="w-full border border-gray-300 p-2 text-sm focus:outline-none focus:border-black font-inter bg-gray-50">
                        <p class="text-[10px] text-gray-500 mt-1 uppercase">Optional. Max 4MB.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Full Name *</label>
                        <input type="text" id="edit-name" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black font-inter">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Phone / WhatsApp *</label>
                        <input type="text" id="edit-phone" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black font-inter">
                    </div>
                    <div class="relative" id="notisse-datepicker-container">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Date of Birth</label>
                        
                        <!-- Hidden actual date value for form submission (YYYY-MM-DD) -->
                        <input type="hidden" id="edit-birthdate" name="birthdate">

                        <!-- Custom Luxury Trigger Field -->
                        <div id="datepicker-trigger" 
                             onclick="toggleNotisseDatePicker(event)"
                             class="w-full border border-gray-300 p-3 text-sm flex items-center justify-between bg-white cursor-pointer hover:border-black focus:border-black transition-colors select-none group">
                            <div class="flex items-center space-x-3 min-w-0">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-black transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="0" ry="0" stroke-width="1.8"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6" stroke-width="1.8"></line>
                                    <line x1="8" y1="2" x2="8" y2="6" stroke-width="1.8"></line>
                                    <line x1="3" y1="10" x2="21" y2="10" stroke-width="1.8"></line>
                                </svg>
                                <span id="datepicker-display-text" class="text-gray-400 font-inter text-sm truncate">Select birth date (DD / MM / YYYY)</span>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <button type="button" id="datepicker-clear-btn" onclick="clearNotisseDatePicker(event)" class="hidden text-gray-400 hover:text-black text-sm font-bold px-1 leading-none cursor-pointer" title="Clear date">
                                    &times;
                                </button>
                                <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-black transition-transform duration-200" id="datepicker-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Bespoke Notisse Calendar Dropdown -->
                        <div id="notisse-calendar-dropdown" 
                             class="hidden absolute z-30 left-0 top-full mt-1.5 w-full sm:w-[340px] bg-white border border-black shadow-2xl p-4 font-space-grotesk select-none transition-all duration-200">
                            
                            <!-- Calendar Header: Month & Year Fast Jump -->
                            <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-100">
                                <button type="button" onclick="changeNotisseMonth(-1, event)" class="p-1.5 text-black hover:bg-neutral-100 transition-colors cursor-pointer" title="Previous Month">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>

                                <!-- Month & Year Dropdowns -->
                                <div class="flex items-center space-x-2">
                                    <select id="cal-month-select" onchange="onMonthSelectChange(this.value)" class="bg-neutral-100 hover:bg-neutral-200 text-black font-space-grotesk font-bold text-xs uppercase px-2 py-1.5 border-none focus:outline-none cursor-pointer transition-colors">
                                        <option value="0">JANUARY</option>
                                        <option value="1">FEBRUARY</option>
                                        <option value="2">MARCH</option>
                                        <option value="3">APRIL</option>
                                        <option value="4">MAY</option>
                                        <option value="5">JUNE</option>
                                        <option value="6">JULY</option>
                                        <option value="7">AUGUST</option>
                                        <option value="8">SEPTEMBER</option>
                                        <option value="9">OCTOBER</option>
                                        <option value="10">NOVEMBER</option>
                                        <option value="11">DECEMBER</option>
                                    </select>

                                    <select id="cal-year-select" onchange="onYearSelectChange(this.value)" class="bg-neutral-100 hover:bg-neutral-200 text-black font-space-grotesk font-bold text-xs uppercase px-2 py-1.5 border-none focus:outline-none cursor-pointer transition-colors">
                                        <!-- Populated dynamically via JS -->
                                    </select>
                                </div>

                                <button type="button" onclick="changeNotisseMonth(1, event)" class="p-1.5 text-black hover:bg-neutral-100 transition-colors cursor-pointer" title="Next Month">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>

                            <!-- Day Names -->
                            <div class="grid grid-cols-7 text-center text-[10px] font-space-grotesk font-bold uppercase text-gray-400 mb-2 tracking-wider">
                                <span>SU</span>
                                <span>MO</span>
                                <span>TU</span>
                                <span>WE</span>
                                <span>TH</span>
                                <span>FR</span>
                                <span>SA</span>
                            </div>

                            <!-- Days Grid -->
                            <div id="cal-days-grid" class="grid grid-cols-7 gap-1 text-center font-space-grotesk text-xs">
                                <!-- Populated dynamically -->
                            </div>

                            <!-- Calendar Footer -->
                            <div class="flex items-center justify-between pt-3 mt-3 border-t border-gray-100">
                                <button type="button" onclick="clearNotisseDatePicker(event)" class="text-gray-400 hover:text-black font-space-grotesk font-semibold uppercase tracking-wider text-[10px] cursor-pointer">
                                    Clear
                                </button>
                                <button type="button" onclick="closeNotisseDatePicker()" class="bg-black text-white px-3.5 py-1.5 text-[10px] font-space-grotesk font-bold uppercase tracking-widest hover:bg-neutral-800 transition-colors cursor-pointer">
                                    Done
                                </button>
                            </div>

                        </div>
                    </div>
                    
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Search District / City *</label>
                        <input type="text" id="edit-area-search" class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black font-inter" placeholder="Type district or city name..." autocomplete="off">
                        <ul id="edit-area-results" class="absolute z-20 w-full bg-white border border-gray-300 shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></ul>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Full Address *</label>
                        <textarea id="edit-address" required class="w-full border border-gray-300 p-3 text-sm h-24 focus:outline-none focus:border-black font-inter resize-none" placeholder="Street name, unit, house number, landmarks..."></textarea>
                    </div>

                    <input type="hidden" id="edit-area-id" required>

                    <div class="pt-4 border-t border-gray-100 flex gap-3">
                        <button type="button" onclick="goBack()" class="w-1/3 border border-gray-300 py-3.5 text-xs font-bold font-space-grotesk uppercase hover:bg-gray-50 transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" id="edit-profile-btn" class="w-2/3 bg-black text-white font-bold font-space-grotesk uppercase py-3.5 text-xs tracking-wider hover:bg-neutral-800 transition-colors cursor-pointer">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </section>

    </main>

    <!-- RUANG KOSONG AGAR BISA DI-SCROLL KE BAWAH -->
    <div class="h-[150vh] w-full bg-white"></div>

    <!-- MARQUEE TEXT GLOBAL -->
    <div id="global-marquee"
        class="fixed bottom-0 left-0 z-30 w-full border-t border-black overflow-hidden flex whitespace-nowrap py-2 md:py-2.5 bg-black shadow-lg">
        @php
            $marqueeStr = (isset($settings['marquee_text']) && !empty($settings['marquee_text']) && $settings['marquee_text'] !== 'NOTISSE WORLD WIDE SHIPPING') 
                ? $settings['marquee_text'] 
                : 'NOTISSE WORLDWIDE SHIPPING';
        @endphp
        <div
            class="animate-marquee inline-block font-space-grotesk font-bold text-xs md:text-sm tracking-[0.22em] text-[#d52c2b] uppercase">
            {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span>&nbsp;
        </div>
        <div
            class="animate-marquee inline-block font-space-grotesk font-bold text-xs md:text-sm tracking-[0.22em] text-[#d52c2b] uppercase">
            {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span>&nbsp;
        </div>
    </div>
    <!-- TOAST NOTIFICATION -->
    <div id="toast-notification"
        class="fixed top-24 left-1/2 -translate-x-1/2 bg-black text-white px-6 py-3 rounded-full shadow-lg z-[60] flex items-center space-x-3 transition-all duration-300 opacity-0 translate-y-4 hidden font-space-grotesk text-sm tracking-wide">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span id="toast-message" class="font-medium">Notification message</span>
    </div>

    <!-- JAVASCRIPT CONTROLLER -->
    <script>
        // --- HELPER: Resolve Image URL ---
        function resolveImageUrl(path, fallback = '') {
            if (!path) return fallback;
            if (path.startsWith('http://') || path.startsWith('https://')) return path;
            if (path.startsWith('/storage/')) return path;
            if (path.startsWith('storage/')) return '/' + path;
            if (path.startsWith('products/') || path.startsWith('artists/') || path.startsWith('artworks/') || path.startsWith('editorials/') || path.startsWith('lookbooks/') || path.startsWith('site-media/') || path.startsWith('avatars/')) {
                return '/storage/' + path;
            }
            return path.startsWith('/') ? path : '/' + path;
        }

        let currentSelectedSize = "M";
        let currentSlideIndex = 0;
        let slideInterval = null;

        let lastScrollY = window.scrollY;
        const scrollDelta = 8;

        // Transparent Header with Smooth Fade on Scroll Down & Reveal on Scroll Up
        function updateHeaderState() {
            const activePage = document.querySelector('.view-page.active-view');
            const isShopPage = !activePage || activePage.id === 'view-shop';
            const mainHeader = document.getElementById('main-header');
            const logoContainer = document.getElementById('header-logo-container');
            const menuLeft = document.getElementById('menu-trigger');
            const searchTriggerBtn = document.getElementById('search-trigger');
            const cartTrigger = document.getElementById('cart-trigger');
            const currentScrollY = window.scrollY;
            const diff = currentScrollY - lastScrollY;

            if (!mainHeader) return;

            // Ensure header is always transparent
            mainHeader.classList.remove('bg-white/90', 'backdrop-blur-[1px]', 'border-b', 'border-black/[0.08]', 'shadow-[0_2px_12px_rgba(0,0,0,0.03)]');
            mainHeader.classList.add('bg-transparent', 'border-transparent');
            mainHeader.style.transform = 'translateY(0)';

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
                // At the very top (window.scrollY <= 40):
                // ALL header controls are visible & interactive!
                if (menuLeft) {
                    menuLeft.classList.remove('opacity-0', 'pointer-events-none', '-translate-x-3');
                    menuLeft.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
                }
                if (searchTriggerBtn) {
                    searchTriggerBtn.classList.remove('opacity-0', 'pointer-events-none', 'header-fade-hidden');
                    searchTriggerBtn.classList.add('opacity-100', 'pointer-events-auto', 'header-fade-visible');
                }
                if (logoContainer) {
                    logoContainer.classList.remove('header-fade-hidden', 'logo-state-small');
                    logoContainer.classList.add('header-fade-visible', 'logo-state-large');
                }
                if (cartTrigger && !isOverlayOpen) {
                    cartTrigger.classList.remove('opacity-0', 'pointer-events-none', 'state-minimal');
                    cartTrigger.classList.add('opacity-100', 'pointer-events-auto');
                }
            } else {
                // Scrolled down beyond top (> 40px):
                // 1. Burger menu ALWAYS stays visible & interactive
                if (menuLeft) {
                    menuLeft.classList.remove('opacity-0', 'pointer-events-none', '-translate-x-3');
                    menuLeft.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
                }

                // 2. Center logo smoothly FADES OUT completely (like A24 Shop)
                if (logoContainer) {
                    logoContainer.classList.add('header-fade-hidden');
                    logoContainer.classList.remove('header-fade-visible');
                }

                // 3. Search trigger smoothly FADES OUT in place
                if (searchTriggerBtn) {
                    searchTriggerBtn.classList.add('header-fade-hidden');
                    searchTriggerBtn.classList.remove('header-fade-visible');
                }

                // 4. Cart button smoothly fades horizontal label out & vertical label in (A24 minimal vertical layout),
                // remaining fixed at the top right to follow the scroll ("ngikut")
                if (cartTrigger && !isOverlayOpen) {
                    cartTrigger.classList.remove('opacity-0', 'pointer-events-none');
                    cartTrigger.classList.add('opacity-100', 'pointer-events-auto', 'state-minimal');
                }
            }
            lastScrollY = currentScrollY <= 0 ? 0 : currentScrollY;
        }

        let isHeaderTicking = false;
        function handleHeaderScroll() {
            if (!isHeaderTicking) {
                requestAnimationFrame(() => {
                    updateHeaderState();
                    isHeaderTicking = false;
                });
                isHeaderTicking = true;
            }
        }

        window.addEventListener('scroll', handleHeaderScroll, { passive: true });
        updateHeaderState();

        let navigationHistory = ['view-shop'];
        let isAuthenticated = localStorage.getItem('notisse_auth') === 'true' || {{ auth()->check() ? 'true' : 'false' }};
        let loggedInUser = @json(auth()->user());
        if (!loggedInUser && localStorage.getItem('notisse_user')) {
            try { loggedInUser = JSON.parse(localStorage.getItem('notisse_user')); } catch(e){}
        }

        let productsData = @json($products);
        let artistsData = @json($artists ?? []);
        let editorialsData = @json($editorials ?? []);
        let lookbooksData = @json($lookbooks ?? []);
        let currentProduct = null;
        let cartState = [];
        
        async function fetchCart() {
            try {
                const response = await fetch('/api/cart', { headers: { 'Accept': 'application/json' }});
                cartState = await response.json();
                renderCartItems();
            } catch (e) {
                console.error("Failed to load cart:", e);
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
                        btn.className = "acc-tab-btn active pb-3 text-xs sm:text-sm font-bold font-space-grotesk uppercase tracking-wider border-b-2 border-black text-black cursor-pointer whitespace-nowrap";
                        pane.classList.remove('hidden');
                    } else {
                        btn.className = "acc-tab-btn pb-3 text-xs sm:text-sm font-medium font-space-grotesk uppercase tracking-wider border-b-2 border-transparent text-gray-400 hover:text-black transition-colors cursor-pointer whitespace-nowrap";
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

        async function renderArtworkOffers() {
            const container = document.getElementById('account-art-offers-list');
            if (!container) return;
            
            let offers = [];
            try {
                const response = await fetch('/my-artwork-offers');
                if (response.ok) {
                    const serverOffers = await response.json();
                    if (Array.isArray(serverOffers) && serverOffers.length > 0) {
                        offers = serverOffers.map(o => ({
                            refId: o.reference_number || '#NTS-ART',
                            date: o.created_at,
                            status: o.status || 'Dalam Tinjauan Kurasi',
                            offerPrice: (Number(o.offering_price) || 0).toLocaleString('id-ID'),
                            artwork: {
                                title: o.artwork?.title || 'Notisse Artwork',
                                artist: o.artwork?.artist?.name || 'Curated Artist',
                                image: o.artwork?.image || '/footage-baju.jpg'
                            }
                        }));
                    }
                }
            } catch (e) {
                console.warn('Could not fetch artwork offers from backend, falling back to localStorage');
            }

            if (offers.length === 0) {
                try {
                    offers = JSON.parse(localStorage.getItem('notisse_artwork_offers') || '[]');
                } catch (e) {
                    offers = [];
                }
            }
            
            if (offers.length === 0) {
                container.innerHTML = `
                    <div class="border border-gray-200 p-8 text-center bg-gray-50">
                        <p class="text-gray-400 font-inter text-sm">No artwork acquisition inquiries submitted yet.</p>
                        <button onclick="showPage('view-artist-detail')" class="mt-3 text-xs font-bold font-space-grotesk uppercase underline tracking-wider cursor-pointer">
                            Explore Art Gallery & Editorial
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = offers.map((offer) => {
                const artwork = offer.artwork || {};
                const displayDate = offer.date ? new Date(offer.date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';
                return `
                    <div class="border border-gray-200 p-6 bg-white space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-gray-100 gap-2">
                            <div>
                                <span class="text-[10px] font-mono font-bold tracking-widest uppercase bg-black text-white px-2 py-0.5 inline-block">${offer.refId || '#NTS-ART'}</span>
                                <span class="text-xs text-gray-400 font-inter ml-2">${displayDate}</span>
                            </div>
                            <span class="text-[10px] font-semibold tracking-wider uppercase text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded font-space-grotesk w-fit">
                                ${offer.status || 'Under Curatorial Review'}
                            </span>
                        </div>
                        <div class="flex items-start space-x-4">
                            <img src="${resolveImageUrl(artwork.image, '/footage-baju.jpg')}" alt="${artwork.title || 'Artwork'}" class="w-16 h-20 object-cover bg-gray-100 border border-gray-200 flex-shrink-0">
                            <div class="space-y-1 min-w-0 flex-1">
                                <h4 class="font-bold text-sm text-black font-space-grotesk truncate">${artwork.title || 'Notisse Artwork'}</h4>
                                <p class="text-xs text-gray-500 font-inter">Artist: <span class="font-semibold text-black">${artwork.artist || 'Curated Artist'}</span></p>
                                <p class="text-xs text-emerald-700 font-space-grotesk font-bold pt-1">Acquisition Value: Rp ${offer.offerPrice} IDR</p>
                                ${offer.message ? `<p class="text-xs text-gray-500 font-inter italic bg-neutral-50 p-2 border border-neutral-100 mt-2">"${offer.message}"</p>` : ''}
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
                            <p class="text-gray-500 font-inter text-sm">No recent orders found.</p>
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
                                    <img src="${resolveImageUrl(item.product?.image || item.image, '{{ asset('footage-baju.jpg') }}')}" alt="${item.product_name}" class="w-full h-full object-cover">
                                </div>
                                <div class="text-xs font-inter">
                                    <p class="font-bold text-black font-space-grotesk uppercase">${item.product_name}</p>
                                    <p class="text-gray-500">Size: ${item.size} | Qty: ${item.quantity}</p>
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
                            <button onclick="payOrder(${order.id})" class="text-[10px] text-white bg-black px-3 py-1.5 font-bold uppercase tracking-wider rounded font-space-grotesk mt-2 w-full hover:bg-neutral-800 transition-colors cursor-pointer">Pay Now</button>
                            <button onclick="cancelOrder(${order.id})" class="text-[10px] text-red-600 font-bold uppercase tracking-wider hover:underline font-space-grotesk mt-2 w-full text-center cursor-pointer">Cancel Order</button>
                        `;
                    } else if (st === 'cancelled') {
                        statusClass = "bg-red-600 text-white";
                    }

                    const placedDate = new Date(order.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });

                    container.innerHTML += `
                    <div class="border border-gray-200 rounded-lg p-6 space-y-4 bg-gray-50">
                        <div class="flex justify-between items-start text-xs font-inter pb-3 border-b border-gray-200">
                            <div>
                                <p class="font-bold text-black font-space-grotesk">ORDER #NTS-${order.id}</p>
                                <p class="text-gray-400">Placed on ${placedDate}</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="px-2.5 py-1 ${statusClass} font-bold rounded text-[10px] tracking-wider uppercase font-space-grotesk">${order.status}</span>
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
                console.error("Failed to load orders:", e);
            }
        }

        async function cancelOrder(id) {
            if (!confirm("Are you sure you want to cancel this order?")) return;
            try {
                const response = await fetch(`/my-orders/${id}/cancel`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });
                if (response.ok) {
                    showToast("Order cancelled successfully.");
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast("Failed to cancel order.");
                }
            } catch (e) {
                console.error(e);
                showToast("A system error occurred.");
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
                    showToast(data.error || "Failed to process payment");
                }
            } catch (err) {
                console.error(err);
                showToast("A system error occurred.");
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
                document.getElementById('user-display-address').innerText = loggedInUser.address ? loggedInUser.address : 'No shipping address saved yet.';
            }
            showToast('Login Successful!');
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
                document.getElementById('user-display-address').innerText = loggedInUser.address ? loggedInUser.address : 'No shipping address saved yet.';
            }
            showToast('Account Created Successfully!');
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
            overlaySearch.classList.add('translate-y-0');
            document.body.style.overflow = 'hidden';
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 250);
                handleLiveSearch(searchInput.value || "");
            }
        }

        function closeSearch() {
            overlaySearch.classList.remove('translate-y-0');
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
                    <div class="col-span-full text-center py-20 text-gray-400 font-inter text-sm space-y-2">
                        <p class="text-base text-black font-space-grotesk uppercase font-semibold">No products found matching "<span class="underline">${query}</span>"</p>
                        <p class="text-xs text-gray-400 font-inter">Try searching for keywords like "Tee", "Tank", "Shirt", or "Blazer".</p>
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
                            <img src="${resolveImageUrl(item.image, '{{ asset('footage-baju.jpg') }}')}" alt="${item.name}" 
                                onerror="this.src='{{ asset('footage-baju.jpg') }}'"
                                class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="flex flex-col justify-between flex-grow pt-1 border-t border-gray-100">
                            <h4 class="font-space-grotesk font-semibold text-xs sm:text-sm text-black group-hover:underline truncate uppercase">${item.name}</h4>
                            <p class="font-space-grotesk text-xs text-gray-500 mt-1">${item.price}</p>
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
                    imgEl.src = resolveImageUrl(currentProduct.image, '{{ asset('footage-baju.jpg') }}');
                }
            }

            // Update Back View Image
            const backImgEl = document.getElementById('pdetail-back-image');
            if (backImgEl) {
                if (currentProduct.back_image) {
                    backImgEl.src = resolveImageUrl(currentProduct.back_image, '{{ asset('footage-baju-belakang.jpg') }}');
                } else {
                    backImgEl.src = "{{ asset('footage-baju-belakang.jpg') }}";
                }
            }

            // Update On-Model Footage Image
            const footImgEl = document.getElementById('pdetail-footage-image');
            if (footImgEl) {
                if (currentProduct.footage_image) {
                    footImgEl.src = resolveImageUrl(currentProduct.footage_image, '{{ asset('footagebaju2.jpg') }}');
                } else {
                    footImgEl.src = "{{ asset('footagebaju2.jpg') }}";
                }
            }

            // Update Size Chart Image & Lightbox Click
            const sizeChartBox = document.getElementById('pdetail-size-box');
            const sizeChartImg = document.getElementById('pdetail-size-img');
            const chartSrc = resolveImageUrl(currentProduct.size_chart_image, "{{ asset('size chart 1.png') }}");
            if (sizeChartImg) sizeChartImg.src = chartSrc;
            if (sizeChartBox) sizeChartBox.onclick = () => openLightbox(chartSrc, (currentProduct.name || 'NOTISSE APPAREL') + ' SIZE CHART');

            // Update Artist byline
            const artistEl = document.getElementById('pdetail-artist');
            if (artistEl) {
                const artistName = currentProduct.artist || 'Mustafa Alatas';
                artistEl.innerText = artistName;
                artistEl.onclick = () => openArtistProfile(artistName, 'Temanggung');
            }

            // Update Description & Fabric Specs (Consistent with Terms and Condition style)
            const descContent = document.getElementById('pdetail-desc-content');
            if (descContent) {
                let bulletItems = [];

                if (currentProduct.specs) {
                    if (currentProduct.specs.includes('•')) {
                        currentProduct.specs.split('•').forEach(s => {
                            const trimmed = s.trim();
                            if (trimmed) bulletItems.push(trimmed);
                        });
                    } else if (currentProduct.specs.includes('\n')) {
                        currentProduct.specs.split('\n').forEach(s => {
                            const trimmed = s.trim();
                            if (trimmed) bulletItems.push(trimmed);
                        });
                    } else {
                        const trimmed = currentProduct.specs.trim();
                        if (trimmed) bulletItems.push(trimmed);
                    }
                }

                const descText = currentProduct.description || "Cotton Combed 16s\nRaster Plastisol Screen Printing\nDigitized Painting";
                descText.split('\n').forEach(line => {
                    const cleanLine = line.replace(/^[•\-\*\s]+/, '').trim();
                    if (cleanLine && !bulletItems.some(item => item.toLowerCase() === cleanLine.toLowerCase())) {
                        bulletItems.push(cleanLine);
                    }
                });

                if (bulletItems.length === 0) {
                    bulletItems = [
                        "Heavyweight 24s Cotton Combed construction",
                        "High-density screenprinted artwork on back and chest",
                        "Boxy relaxed fit with reinforced ribbed collar",
                        "Crafted & printed in Bandung, Indonesia"
                    ];
                }

                let html = `<ul class="list-disc pl-5 space-y-2 leading-relaxed font-inter text-xs sm:text-[13px] md:text-sm text-gray-500">`;
                bulletItems.forEach(item => {
                    html += `<li>${item}</li>`;
                });
                html += `</ul>`;

                descContent.innerHTML = html;
            }

            const sizeMenu = document.getElementById('size-dropdown-menu');
            if (sizeMenu) {
                sizeMenu.innerHTML = '';
                const sizeNameMap = {
                    'S': 'Small',
                    'M': 'Medium',
                    'L': 'Large',
                    'XL': 'Extra Large',
                    'XXL': 'Double Extra Large',
                    '2XL': 'Double Extra Large',
                    'XXXL': 'Triple Extra Large',
                    '3XL': 'Triple Extra Large'
                };
                if (currentProduct.stocks && currentProduct.stocks.length > 0) {
                    currentProduct.stocks.forEach(stock => {
                        const sUpper = (stock.size || '').toUpperCase().trim();
                        const fullName = sizeNameMap[sUpper] || stock.size;
                        if (stock.stock > 0) {
                            sizeMenu.innerHTML += `
                                <div onclick="selectSize('${stock.size}')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors border-b border-gray-100 flex justify-between items-center font-space-grotesk">
                                    <span>${fullName}</span><span class="text-xs opacity-50">${stock.size}</span>
                                </div>
                            `;
                        } else {
                            sizeMenu.innerHTML += `
                                <div class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider border-b border-gray-100 flex justify-between items-center font-space-grotesk text-gray-300 cursor-not-allowed">
                                    <span>${fullName} (Out of Stock)</span><span class="text-xs opacity-50">${stock.size}</span>
                                </div>
                            `;
                        }
                    });
                } else {
                    sizeMenu.innerHTML = '<div class="px-4 py-2.5 text-xs font-space-grotesk text-gray-400">Out of Stock</div>';
                }
            }

            const sizeLabel = document.getElementById('selected-size-label');
            if (sizeLabel) sizeLabel.innerText = 'Select size: ';
            currentSelectedSize = null;

            // Reset all accordions and size dropdown to collapsed state
            document.querySelectorAll('#pdetail-left-col .accordion-content').forEach(c => c.classList.remove('expanded'));
            document.querySelectorAll('#pdetail-left-col .chevron-icon').forEach(i => i.classList.remove('rotated'));
            closeSizeDropdown();
            updateProductDetailScroll();

            showPage('view-product-detail');
        }

        function openArtistProfile(name, location) {
            const artist = (typeof artistsData !== 'undefined' && artistsData) ? artistsData.find(a => (a.name || '').toLowerCase() === (name || '').toLowerCase()) : null;

            document.getElementById('artist-profile-name').innerText = name;
            document.getElementById('artist-profile-location').innerText = artist && artist.city ? artist.city : (location || 'Indonesia');
            document.getElementById('editorial-section-title').innerText = "Articles Featuring " + name;

            if (artist) {
                if (artist.bio) {
                    const bioEl = document.getElementById('artist-profile-bio') || document.querySelector('#view-artist-detail .md\\:col-span-8 p.text-gray-700') || document.querySelector('#view-artist-detail .md\\:col-span-8 p.text-gray-800');
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
                        const edImg = resolveImageUrl(ed.image, 'https://placehold.co/600x600/d9d9d9/555555?text=Editorial');
                        const card = document.createElement('div');
                        card.className = 'group cursor-pointer';
                        card.onclick = () => openArtistEditorialDetailById(ed.id);
                        card.innerHTML = `
                            <div class="w-full aspect-square bg-[#d9d9d9] mb-3 overflow-hidden">
                                <img src="${edImg}" alt="${ed.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <h4 class="font-bold text-xs md:text-sm text-black font-space-grotesk group-hover:underline leading-snug">
                                ${ed.title}
                            </h4>
                            <div class="mt-2.5 flex items-center justify-between text-[11px] font-space-grotesk">
                                <span class="text-black font-semibold hover:underline" onclick="event.stopPropagation(); openArtistEditorialDetailById(${ed.id})">Read Preview &rarr;</span>
                                <span onclick="event.stopPropagation(); navigateTo('view-editorials-all')" class="text-brandRed hover:underline font-bold inline-flex items-center gap-1 cursor-pointer">
                                    <span>Editorial Page</span>
                                    <span>&nearr;</span>
                                </span>
                            </div>
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
                        const artImg = resolveImageUrl(artw.image, 'https://placehold.co/600x800/d9d9d9/555555?text=Artwork');
                        const card = document.createElement('div');
                        card.className = 'group cursor-pointer';
                        card.onclick = () => openArtistArtworkDetail(artw.title, name, artw.medium, artw.dimensions, artImg, artw.price);
                        card.innerHTML = `
                            <div class="w-full aspect-square bg-[#d9d9d9] mb-3.5 overflow-hidden relative">
                                <img src="${artImg}" alt="${artw.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <h4 class="font-bold text-sm sm:text-base text-black font-space-grotesk group-hover:underline">
                                ${artw.title}
                            </h4>
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
                btnArtwork.className = 'pb-3 font-medium text-black border-b-2 border-black transition-colors focus:outline-none cursor-pointer font-space-grotesk';
                btnEditorial.className = 'pb-3 font-medium text-gray-400 hover:text-black transition-colors focus:outline-none cursor-pointer font-space-grotesk';
                contentArtwork.classList.remove('hidden');
                contentEditorial.classList.add('hidden');
            } else {
                btnEditorial.className = 'pb-3 font-medium text-black border-b-2 border-black transition-colors focus:outline-none cursor-pointer font-space-grotesk';
                btnArtwork.className = 'pb-3 font-medium text-gray-400 hover:text-black transition-colors focus:outline-none cursor-pointer font-space-grotesk';
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

        function openArtistArtworkDetail(title, artist, medium, dimensions, image, price) {
            currentActiveArtwork = {
                title: title || 'Artwork',
                artist: artist || 'Mustafa Alatas',
                medium: medium || 'Oil on canvas',
                dimensions: dimensions || '60x60cm',
                image: image || 'https://placehold.co/1000x750/d9d9d9/555555?text=Full+Artwork+View',
                price: price || 0
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

            const priceEl = document.getElementById('artwork-price-display');
            if (priceEl) {
                const pVal = Number(currentActiveArtwork.price || 0);
                priceEl.innerText = pVal > 0 ? 'Rp ' + pVal.toLocaleString('id-ID') : 'Price on Inquiry';
            }

            showPage('view-artist-artwork-detail');
        }

        /* ARTWORK OFFERING MODAL LOGIC */
        function triggerArtworkOffering(title, artist, medium, dimensions, image, price) {
            currentActiveArtwork = {
                title: title || 'Artwork',
                artist: artist || 'Mustafa Alatas',
                medium: medium || 'Oil on canvas',
                dimensions: dimensions || '60x60cm',
                image: image || 'https://placehold.co/400x500/222/fff?text=Artwork',
                price: price || 0
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

            const curArt = currentActiveArtwork || {};
            if (prevImg) prevImg.src = resolveImageUrl(curArt.image, 'https://placehold.co/400x500/222/fff?text=Artwork');
            if (prevTitle) prevTitle.innerText = curArt.title || 'Artwork';
            if (prevArtist) prevArtist.innerText = curArt.artist || 'Mustafa Alatas';
            if (prevSpecs) prevSpecs.innerText = `${curArt.medium || 'Oil on canvas'} • ${curArt.dimensions || '60x60cm'}`;

            // Populate User Account Info from logged-in session / storage
            let activeUser = loggedInUser;
            try {
                const storedUser = localStorage.getItem('notisse_user');
                if (storedUser) activeUser = JSON.parse(storedUser);
            } catch (e) {}

            const userName = (activeUser && activeUser.name) ? activeUser.name : 'Collector / Guest';
            const userEmail = (activeUser && activeUser.email) ? activeUser.email : 'guest@notisse.com';

            const userNameEl = document.getElementById('offering-user-name');
            const userEmailEl = document.getElementById('offering-user-email');
            const userAvatarEl = document.getElementById('offering-user-avatar');

            if (userNameEl) userNameEl.innerText = userName;
            if (userEmailEl) userEmailEl.innerText = userEmail;
            if (userAvatarEl) {
                const initials = userName.split(' ').filter(Boolean).map(n => n[0]).join('').substring(0, 2).toUpperCase();
                userAvatarEl.innerText = initials || 'NT';
            }

            // Populate price
            const fixedPriceDisplay = document.getElementById('offering-fixed-price-display');
            const priceInput = document.getElementById('offering-buyer-price');
            const messageInput = document.getElementById('offering-buyer-message');
            const pVal = Number(curArt.price || 0);
            const formatted = pVal > 0 ? 'Rp ' + pVal.toLocaleString('id-ID') : 'Rp 0';
            if (fixedPriceDisplay) fixedPriceDisplay.innerText = formatted;
            if (priceInput) priceInput.value = pVal > 0 ? pVal : '0';
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
                showToast("Please enter an offer amount.");
                return;
            }

            const rawPrice = priceStr.replace(/\D/g, '');

            let activeUser = loggedInUser;
            try {
                const storedUser = localStorage.getItem('notisse_user');
                if (storedUser) activeUser = JSON.parse(storedUser);
            } catch (err) {}

            if (!activeUser) {
                showToast("Please login or create an account first.");
                return;
            }

            const btn = document.querySelector('#artwork-offering-form-body button[type="submit"]');
            const origText = btn.innerText;
            btn.innerText = "SUBMITTING...";
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

                    showToast("Acquisition inquiry submitted successfully!");
                } else {
                    showToast(data.message || "Failed to submit acquisition inquiry.");
                }
            } catch (err) {
                console.error(err);
                showToast("A system error occurred while submitting inquiry.");
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

            if (typeof editorialsData !== 'undefined' && editorialsData && editorialsData.length > 0) {
                foundEd = editorialsData.find(e => e.id == id);
                if (foundEd && foundEd.artist) {
                    foundArtist = foundEd.artist;
                }
            }

            if (!foundEd && typeof artistsData !== 'undefined' && artistsData) {
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

                const topContent = document.getElementById('editorial-detail-content-top');
                const bottomContent = document.getElementById('editorial-detail-content-bottom');
                const rawText = foundEd.content || foundEd.excerpt || '';
                const paragraphs = rawText.split('\n').map(p => p.trim()).filter(Boolean);

                if (topContent) topContent.innerHTML = '';
                if (bottomContent) bottomContent.innerHTML = '';

                if (paragraphs.length <= 1) {
                    if (topContent && paragraphs[0]) {
                        const pEl = document.createElement('p');
                        pEl.className = 'leading-relaxed text-base sm:text-lg md:text-[1.05rem] text-gray-800 font-inter';
                        pEl.innerText = paragraphs[0];
                        topContent.appendChild(pEl);
                    }
                    if (bottomContent && foundEd.excerpt && foundEd.excerpt !== paragraphs[0]) {
                        const pEl = document.createElement('p');
                        pEl.className = 'leading-relaxed text-base sm:text-lg md:text-[1.05rem] text-gray-800 font-inter';
                        pEl.innerText = foundEd.excerpt;
                        bottomContent.appendChild(pEl);
                    }
                } else {
                    // Paragraf 1 di atas foto
                    if (topContent) {
                        const pEl = document.createElement('p');
                        pEl.className = 'leading-relaxed text-base sm:text-lg md:text-[1.05rem] text-gray-800 font-inter';
                        pEl.innerText = paragraphs[0];
                        topContent.appendChild(pEl);
                    }
                    // Paragraf 2 di bawah foto
                    if (bottomContent) {
                        for (let i = 1; i < paragraphs.length; i++) {
                            const pEl = document.createElement('p');
                            pEl.className = 'leading-relaxed text-base sm:text-lg md:text-[1.05rem] text-gray-800 font-inter';
                            pEl.innerText = paragraphs[i];
                            bottomContent.appendChild(pEl);
                        }
                    }
                }

                const edImg = resolveImageUrl(foundEd.image, '/home.jpg');
                const portraitEl = document.getElementById('editorial-detail-portrait');
                if (portraitEl) {
                    portraitEl.src = (foundArtist && foundArtist.photo) 
                        ? resolveImageUrl(foundArtist.photo, (edImg.includes('masmus') ? edImg : '/masmus.jpeg')) 
                        : (edImg.includes('masmus') ? edImg : '/masmus.jpeg');
                }

                let artImg = '/artwork.jpeg';
                if (foundArtist && foundArtist.artworks && foundArtist.artworks.length > 0) {
                    const firstArt = foundArtist.artworks[0].image;
                    artImg = resolveImageUrl(firstArt, '/artwork.jpeg');
                } else if (foundArtist && foundArtist.artwork_preview) {
                    artImg = resolveImageUrl(foundArtist.artwork_preview, '/artwork.jpeg');
                }
                const artworkEl = document.getElementById('editorial-detail-artwork');
                if (artworkEl) artworkEl.src = artImg;

                let featuredImg = '/home.jpg';
                if (foundEd.image && !foundEd.image.includes('masmus') && (!foundArtist || foundEd.image !== foundArtist.photo)) {
                    featuredImg = edImg;
                } else if (foundArtist && foundArtist.artworks && foundArtist.artworks.length > 2) {
                    const featArt = foundArtist.artworks[2].image;
                    featuredImg = resolveImageUrl(featArt, '/home.jpg');
                }
                const featuredEl = document.getElementById('editorial-detail-featured');
                if (featuredEl) {
                    featuredEl.src = featuredImg;
                }
            }

            showPage('view-artist-editorial-detail');
        }

        /* LOOKBOOK DYNAMIC LOADER & DETAIL */
        function openLookbookDetail(id) {
            let lb = null;
            if (typeof lookbooksData !== 'undefined' && lookbooksData && lookbooksData.length > 0) {
                if (id) {
                    lb = lookbooksData.find(l => l.id == id) || lookbooksData[0];
                } else {
                    lb = lookbooksData[0];
                }
            }

            const titleEl = document.getElementById('lookbook-detail-title');
            if (titleEl) {
                titleEl.innerText = lb ? (lb.title || (lb.year + ' LOOKBOOK')) : '2025 LOOKBOOK';
            }

            const videoPlayer = document.getElementById('lookbook-video-player');
            const videoSource = document.getElementById('lookbook-video-source');
            if (videoPlayer && videoSource) {
                let vSrc = (lb && lb.video_url) ? resolveImageUrl(lb.video_url) : "{{ asset('video/editorial.mp4') }}";
                videoSource.src = vSrc;
                videoPlayer.load();
                videoPlayer.play().catch(() => {});
            }

            const grid = document.getElementById('lookbook-photos-grid');
            if (grid && lb && lb.fotoshoots) {
                grid.innerHTML = '';
                if (lb.fotoshoots.length > 0) {
                    lb.fotoshoots.forEach(f => {
                        const imgUrl = resolveImageUrl(f.image);
                        const escapedTitle = (f.title || '').replace(/'/g, "\\'");
                        grid.innerHTML += `
                            <div class="w-full group cursor-pointer" onclick="openLightbox('${imgUrl}', '${escapedTitle}')">
                                <div class="overflow-hidden bg-[#d9d9d9] relative">
                                    <img src="${imgUrl}" alt="${f.title || ''}"
                                        class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                    ${f.title ? `
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <span class="text-white font-space-grotesk tracking-[0.2em] uppercase text-sm md:text-base">${f.title}</span>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    });
                } else {
                    grid.innerHTML = '<div class="col-span-2 py-16 text-center text-gray-400 font-inter">No photos uploaded for this lookbook album yet.</div>';
                }
            }

            showPage('view-lookbook-detail');
        }

        function showToast(message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'bg-black text-white px-5 py-3 rounded text-xs font-space-grotesk uppercase tracking-wider shadow-xl transition-all duration-300 opacity-0 transform translate-y-2';
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
            const slides = document.querySelectorAll('.hero-carousel-slide');
            slides.forEach((slide, index) => {
                if (index === currentSlideIndex) {
                    slide.classList.remove('opacity-0', 'pointer-events-none');
                    slide.classList.add('opacity-100');
                } else {
                    slide.classList.remove('opacity-100');
                    slide.classList.add('opacity-0', 'pointer-events-none');
                }
            });
        }

        function nextSlide() {
            const slides = document.querySelectorAll('.hero-carousel-slide');
            if (slides.length <= 1) return;
            currentSlideIndex = (currentSlideIndex + 1) % slides.length;
            updateCarouselUI();
        }

        function prevSlide() {
            const slides = document.querySelectorAll('.hero-carousel-slide');
            if (slides.length <= 1) return;
            currentSlideIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
            updateCarouselUI();
        }

        function goToSlide(index) {
            currentSlideIndex = index;
            updateCarouselUI();
        }

        function startAutoSlide() {
            stopAutoSlide();
            slideInterval = setInterval(nextSlide, 3500);
        }

        function stopAutoSlide() {
            if (slideInterval) clearInterval(slideInterval);
        }

        function updateProductDetailScroll() {
            const leftCol = document.getElementById('pdetail-left-col');
            if (!leftCol) return;
            const hasExpandedAccordion = document.querySelector('#pdetail-left-col .accordion-content.expanded') !== null;
            const isSizeDropdownActive = document.querySelector('#size-dropdown-menu.active') !== null;
            if (hasExpandedAccordion || isSizeDropdownActive) {
                leftCol.classList.remove('lg:overflow-y-hidden');
                leftCol.classList.add('lg:overflow-y-auto');
            } else {
                leftCol.classList.remove('lg:overflow-y-auto');
                leftCol.classList.add('lg:overflow-y-hidden');
                leftCol.scrollTop = 0;
            }
        }

        function toggleSizeDropdown(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('size-dropdown-menu');
            if (menu) {
                menu.classList.toggle('active');
                updateProductDetailScroll();

                if (menu.classList.contains('active')) {
                    const leftCol = document.getElementById('pdetail-left-col');
                    if (leftCol) {
                        setTimeout(() => {
                            leftCol.scrollBy({ top: 160, behavior: 'smooth' });
                        }, 50);
                    }
                }
            }
        }

        function closeSizeDropdown() {
            const menu = document.getElementById('size-dropdown-menu');
            if (menu && menu.classList.contains('active')) {
                menu.classList.remove('active');
                updateProductDetailScroll();
            }
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
            updateProductDetailScroll();
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('custom-size-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                closeSizeDropdown();
            }
        });

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
                container.innerHTML = `<div class="text-center py-16 text-gray-400 text-sm font-inter">Your cart is empty.</div>`;
            } else {
                let html = '';
                cartState.forEach((item, index) => {
                    const itemPrice = item.unitPrice || item.price || 0;
                    const itemTotal = itemPrice * item.qty;
                    totalAmount += itemTotal;
                    totalItemCount += item.qty;

                    let itemImg = resolveImageUrl(item.image, "{{ asset('footage-baju.jpg') }}");

                    html += `
                        <div class="grid grid-cols-12 items-center text-xs md:text-sm py-2 border-b border-gray-100 cart-animated-item" style="animation-delay: ${index * 70}ms">
                            <div class="col-span-6 md:col-span-5 flex items-center space-x-3">
                                <div class="w-12 h-16 bg-gray-100 flex-shrink-0 border border-gray-200 overflow-hidden">
                                    <img src="${itemImg}" alt="${item.name}" class="w-full h-full object-cover" onerror="this.src='{{ asset('footage-baju.jpg') }}'">
                                </div>
                                <div>
                                    <p class="font-semibold uppercase text-black text-xs md:text-sm font-space-grotesk">${item.name}</p>
                                    <p class="text-xs text-gray-500 font-inter">Size: ${item.size}</p>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-3 flex justify-center">
                                <div class="flex items-center border border-gray-400">
                                    <button onclick="updateCartQty(${index}, -1)" class="px-2 py-1 hover:bg-gray-100 font-bold font-inter cursor-pointer">−</button>
                                    <span class="px-3 py-1 font-semibold font-inter">${item.qty}</span>
                                    <button onclick="updateCartQty(${index}, 1)" class="px-2 py-1 hover:bg-gray-100 font-bold font-inter cursor-pointer">+</button>
                                </div>
                            </div>
                            <div class="hidden md:block md:col-span-2 text-right font-space-grotesk">${formatRupiah(itemPrice)}</div>
                            <div class="col-span-3 md:col-span-2 text-right font-medium font-space-grotesk">${formatRupiah(itemTotal)}</div>
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
            showToast("Cross-sell item feature is temporarily unavailable");
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
                    showToast(data.message || 'Failed to add to cart');
                }
            } catch(e) {
                console.error(e);
                showToast('A system error occurred.');
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
                    console.error("Failed to fetch areas", e);
                }
            }, 500);
        }

        async function fetchShippingRates(areaId) {
            const shippingOptions = document.getElementById('shipping-options');
            const shippingContainer = document.getElementById('shipping-options-container');

            if (shippingOptions) shippingOptions.innerHTML = '<span class="text-gray-400">Calculating shipping rate...</span>';
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
                    if (shippingOptions) shippingOptions.innerHTML = '<span class="text-red-500">Courier unavailable for this area.</span>';
                }
            } catch (e) {
                console.error("Failed to fetch shipping rate", e);
                if (shippingOptions) shippingOptions.innerHTML = '<span class="text-red-500">Connection error.</span>';
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

        // ====== BESPOKE NOTISSE DATE PICKER LOGIC ======
        let calCurrentYear = 2000;
        let calCurrentMonth = 0; // 0-11
        let calSelectedDate = null; // YYYY-MM-DD
        const calMonthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        function initNotisseDatePicker() {
            const yearSelect = document.getElementById('cal-year-select');
            if (!yearSelect) return;

            const currentYear = new Date().getFullYear();
            yearSelect.innerHTML = '';
            for (let y = currentYear; y >= 1930; y--) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                yearSelect.appendChild(opt);
            }

            // Sync with existing value if set
            const hiddenInput = document.getElementById('edit-birthdate');
            if (hiddenInput && hiddenInput.value) {
                setNotisseDatePickerValue(hiddenInput.value);
            } else {
                calCurrentYear = 2000;
                calCurrentMonth = 0;
                renderNotisseCalendarGrid();
            }

            // Close on click outside
            document.addEventListener('click', function(e) {
                const container = document.getElementById('notisse-datepicker-container');
                if (container && !container.contains(e.target)) {
                    closeNotisseDatePicker();
                }
            });
        }

        function toggleNotisseDatePicker(e) {
            if (e) e.stopPropagation();
            const dropdown = document.getElementById('notisse-calendar-dropdown');
            const chevron = document.getElementById('datepicker-chevron');
            if (!dropdown) return;
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
                renderNotisseCalendarGrid();
            } else {
                closeNotisseDatePicker();
            }
        }

        function closeNotisseDatePicker() {
            const dropdown = document.getElementById('notisse-calendar-dropdown');
            const chevron = document.getElementById('datepicker-chevron');
            if (dropdown) dropdown.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }

        function setNotisseDatePickerValue(dateStr) {
            const hiddenInput = document.getElementById('edit-birthdate');
            const displayText = document.getElementById('datepicker-display-text');
            const clearBtn = document.getElementById('datepicker-clear-btn');

            if (!dateStr) {
                if (hiddenInput) hiddenInput.value = '';
                if (displayText) {
                    displayText.innerText = 'Select birth date (DD / MM / YYYY)';
                    displayText.classList.remove('text-black', 'font-medium');
                    displayText.classList.add('text-gray-400');
                }
                if (clearBtn) clearBtn.classList.add('hidden');
                calSelectedDate = null;
                calCurrentYear = 2000;
                calCurrentMonth = 0;
            } else {
                if (hiddenInput) hiddenInput.value = dateStr;
                calSelectedDate = dateStr;
                const parts = dateStr.split('-');
                if (parts.length === 3) {
                    const y = parseInt(parts[0]);
                    const m = parseInt(parts[1]) - 1;
                    const d = parseInt(parts[2]);
                    calCurrentYear = y;
                    calCurrentMonth = m;

                    if (displayText) {
                        displayText.innerText = `${d} ${calMonthNames[m]} ${y}`;
                        displayText.classList.remove('text-gray-400');
                        displayText.classList.add('text-black', 'font-medium');
                    }
                    if (clearBtn) clearBtn.classList.remove('hidden');
                }
            }
            renderNotisseCalendarGrid();
        }

        function clearNotisseDatePicker(e) {
            if (e) e.stopPropagation();
            setNotisseDatePickerValue('');
        }

        function changeNotisseMonth(delta, e) {
            if (e) e.stopPropagation();
            calCurrentMonth += delta;
            if (calCurrentMonth < 0) {
                calCurrentMonth = 11;
                calCurrentYear--;
            } else if (calCurrentMonth > 11) {
                calCurrentMonth = 0;
                calCurrentYear++;
            }
            renderNotisseCalendarGrid();
        }

        function onMonthSelectChange(val) {
            calCurrentMonth = parseInt(val);
            renderNotisseCalendarGrid();
        }

        function onYearSelectChange(val) {
            calCurrentYear = parseInt(val);
            renderNotisseCalendarGrid();
        }

        function renderNotisseCalendarGrid() {
            const monthSelect = document.getElementById('cal-month-select');
            const yearSelect = document.getElementById('cal-year-select');
            const daysGrid = document.getElementById('cal-days-grid');
            if (!daysGrid) return;

            if (monthSelect) monthSelect.value = calCurrentMonth;
            if (yearSelect) yearSelect.value = calCurrentYear;

            daysGrid.innerHTML = '';

            const firstDayIndex = new Date(calCurrentYear, calCurrentMonth, 1).getDay(); // 0 is Sunday
            const totalDaysInMonth = new Date(calCurrentYear, calCurrentMonth + 1, 0).getDate();
            const prevMonthDays = new Date(calCurrentYear, calCurrentMonth, 0).getDate();

            // Previous month overflow days
            for (let i = firstDayIndex - 1; i >= 0; i--) {
                const dayNum = prevMonthDays - i;
                const cell = document.createElement('div');
                cell.className = 'w-8 h-8 mx-auto flex items-center justify-center text-gray-300 text-[11px] pointer-events-none select-none';
                cell.textContent = dayNum;
                daysGrid.appendChild(cell);
            }

            // Current month days
            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

            for (let day = 1; day <= totalDaysInMonth; day++) {
                const dateString = `${calCurrentYear}-${String(calCurrentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isSelected = (calSelectedDate === dateString);
                const isToday = (todayStr === dateString);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = day;

                let classes = 'w-8 h-8 mx-auto flex items-center justify-center text-xs transition-colors cursor-pointer ';
                if (isSelected) {
                    classes += 'bg-black text-white font-bold ring-2 ring-black';
                } else if (isToday) {
                    classes += 'border border-black font-bold text-black hover:bg-black hover:text-white';
                } else {
                    classes += 'text-black hover:bg-neutral-100 font-medium';
                }

                btn.className = classes;
                btn.onclick = (e) => {
                    e.stopPropagation();
                    selectNotisseDate(dateString);
                };
                daysGrid.appendChild(btn);
            }

            // Next month overflow padding (to complete 35 or 42 grid cells)
            const filledCells = firstDayIndex + totalDaysInMonth;
            const remaining = (filledCells % 7 === 0) ? 0 : 7 - (filledCells % 7);
            for (let j = 1; j <= remaining; j++) {
                const cell = document.createElement('div');
                cell.className = 'w-8 h-8 mx-auto flex items-center justify-center text-gray-300 text-[11px] pointer-events-none select-none';
                cell.textContent = j;
                daysGrid.appendChild(cell);
            }
        }

        function selectNotisseDate(dateString) {
            setNotisseDatePickerValue(dateString);
            closeNotisseDatePicker();
        }

        document.addEventListener('DOMContentLoaded', initNotisseDatePicker);

        // ====== EDIT PROFILE LOGIC ======
        let editAreaSearchTimeout = null;
        const editAreaSearchInput = document.getElementById('edit-area-search');
        const editAreaResultsBox = document.getElementById('edit-area-results');

        function showEditProfile() {
            if (!loggedInUser) return;
            document.getElementById('edit-name').value = loggedInUser.name || '';
            document.getElementById('edit-phone').value = loggedInUser.phone || '';
            setNotisseDatePickerValue(loggedInUser.birthdate || '');
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
                                    li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm font-inter border-b border-gray-100';
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

            const formData = new FormData();
            formData.append('name', document.getElementById('edit-name').value);
            formData.append('phone', document.getElementById('edit-phone').value);
            formData.append('birthdate', document.getElementById('edit-birthdate').value);
            formData.append('address', document.getElementById('edit-address').value);
            formData.append('area_id', document.getElementById('edit-area-id').value);

            const avatarFile = document.getElementById('edit-avatar').files[0];
            if (avatarFile) {
                formData.append('avatar', avatarFile);
            }

            if (!formData.get('area_id')) {
                errorBox.innerText = 'Please select a district/city from the search list.';
                errorBox.classList.remove('hidden');
                return;
            }

            btn.innerText = 'SAVING...';
            btn.disabled = true;

            try {
                const response = await fetch('/update-profile', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                if (response.ok && data.success) {
                    successBox.innerText = 'Profile updated successfully.';
                    successBox.classList.remove('hidden');
                    
                    // Update frontend state
                    loggedInUser.name = formData.get('name');
                    loggedInUser.phone = formData.get('phone');
                    loggedInUser.birthdate = data.user?.birthdate || formData.get('birthdate');
                    loggedInUser.address = formData.get('address');
                    loggedInUser.area_id = formData.get('area_id');
                    
                    localStorage.setItem('notisse_user', JSON.stringify(loggedInUser));
                    document.getElementById('user-display-name').innerText = loggedInUser.name;
                    document.getElementById('user-display-address').innerText = loggedInUser.address;
                    const birthEl = document.getElementById('user-display-birthdate');
                    if (birthEl && loggedInUser.birthdate) {
                        birthEl.innerText = 'Date of Birth: ' + loggedInUser.birthdate;
                    }
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);
                } else {
                    errorBox.innerText = data.message || 'Failed to save changes.';
                    errorBox.classList.remove('hidden');
                }
            } catch (err) {
                errorBox.innerText = 'A network error occurred.';
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