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
            onclick="if (typeof showPage === 'function' && document.getElementById('view-shop')) { showPage('view-shop'); } else { window.location.href='/'; }" title="Back to Shop">
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
