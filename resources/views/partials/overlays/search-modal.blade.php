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

