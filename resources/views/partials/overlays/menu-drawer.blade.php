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
                <a href="javascript:void(0)" onclick="if(typeof navigateTo === 'function'){ navigateTo('view-shop'); } else { window.location.href='/shop'; }"
                    class="font-space-grotesk text-2xl sm:text-3xl md:text-[32px] font-medium tracking-wider hover:translate-x-2 hover:text-gray-200 transition-all duration-300 w-fit">SHOP</a>
                <a href="javascript:void(0)" onclick="if(typeof navigateTo === 'function'){ navigateTo('view-artist-collab'); } else { window.location.href='/artists'; }"
                    class="font-space-grotesk text-2xl sm:text-3xl md:text-[32px] font-medium tracking-wider hover:translate-x-2 hover:text-gray-200 transition-all duration-300 w-fit">ARTIST COLLAB</a>
                <a href="javascript:void(0)" onclick="if(typeof navigateTo === 'function'){ navigateTo('view-lookbook-list'); } else { window.location.href='/lookbooks'; }"
                    class="font-space-grotesk text-2xl sm:text-3xl md:text-[32px] font-medium tracking-wider hover:translate-x-2 hover:text-gray-200 transition-all duration-300 w-fit">LOOKBOOK</a>
                <a href="javascript:void(0)" onclick="if(typeof navigateTo === 'function'){ navigateTo('view-editorials-all'); } else { window.location.href='/editorials'; }"
                    class="font-space-grotesk text-2xl sm:text-3xl md:text-[32px] font-medium tracking-wider hover:translate-x-2 hover:text-gray-200 transition-all duration-300 w-fit">EDITORIAL</a>
                <a href="javascript:void(0)" onclick="if(typeof navigateTo === 'function'){ navigateTo('view-about'); } else { window.location.href='/about'; }"
                    class="font-space-grotesk text-2xl sm:text-3xl md:text-[32px] font-medium tracking-wider hover:translate-x-2 hover:text-gray-200 transition-all duration-300 w-fit">ABOUT</a>
            </nav>

            <div class="flex items-center space-x-6 sm:space-x-8 font-space-grotesk mb-2">
                <a href="javascript:void(0)" onclick="if(typeof handleAccountButtonClick === 'function'){ closeMenu(); handleAccountButtonClick(); } else { window.location.href='/account'; }"
                    class="text-base md:text-lg font-medium tracking-widest text-white/80 hover:text-white transition-all duration-300 w-fit">ACCOUNT</a>
                <a href="javascript:void(0)" onclick="if(typeof navigateTo === 'function'){ navigateTo('view-contact'); } else { window.location.href='/contact'; }"
                    class="text-base md:text-lg font-medium tracking-widest text-white/80 hover:text-white transition-all duration-300 w-fit">CONTACT</a>
            </div>

        </div>
    </div>

