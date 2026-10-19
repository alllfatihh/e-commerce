        <!-- VIEW 2: PRODUCT DETAIL PAGE -->
        <section id="view-product-detail" class="view-page {{ ($activeView ?? '') === 'view-product-detail' ? 'active-view' : '' }} w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-16">
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

