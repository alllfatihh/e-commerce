        <!-- VIEW 4: LOOKBOOK INDEX PAGE (LEFT-ALIGNED MINIMALIST TIMELINE) -->
        <section id="view-lookbook-list"
            class="view-page {{ ($activeView ?? '') === 'view-lookbook-list' ? 'active-view' : '' }} min-h-[75vh] w-full px-6 sm:px-10 md:px-16 lg:px-20 py-16 md:py-24 flex flex-col justify-center items-start">
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

