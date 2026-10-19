        <!-- VIEW 3B: ARTIST PROFILE PAGE -->
        <section id="view-artist-detail" class="view-page {{ ($activeView ?? '') === 'view-artist-detail' ? 'active-view' : '' }} w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-16">
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
                    <img id="artist-profile-photo" src="https://placehold.co/600x750/333333/ffffff?text=Mustafa+Alatas" alt="Mustafa Alatas"
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

