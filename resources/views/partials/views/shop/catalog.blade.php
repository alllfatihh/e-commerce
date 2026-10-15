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

