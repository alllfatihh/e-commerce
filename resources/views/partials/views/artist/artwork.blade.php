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

