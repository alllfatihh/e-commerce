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

        #header-logo-container {
            position: absolute !important;
            left: 50% !important;
            top: 50% !important;
            transform: translate(-50%, -50%) !important;
            z-index: 20;
            pointer-events: auto;
        }

        #search-trigger {
            margin-right: 14px !important;
        }

        @media (min-width: 640px) {
            #search-trigger {
                margin-right: 32px !important;
            }
        }

        @media (max-width: 640px) {
            .header__link--cart {
                width: 58px !important;
            }
            .header__link--cart::before {
                width: 17px !important;
            }
            .header__link--cart__default-label {
                right: 21px !important;
                font-size: 11px !important;
                letter-spacing: 0.1em !important;
                width: 36px !important;
            }
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
