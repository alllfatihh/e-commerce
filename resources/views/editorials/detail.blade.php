        <!-- VIEW 3D: EDITORIAL ARTICLE DETAIL PAGE -->
        <section id="view-artist-editorial-detail" class="view-page {{ ($activeView ?? '') === 'view-artist-editorial-detail' ? 'active-view' : '' }} w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-16">
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

