@extends('layouts.app')

@section('title', 'Artist Collaborations - Notisse')

@section('content')
<!-- VIEW 3: ARTIST COLLAB LIST PAGE -->
        <section id="view-artist-collab" class="view-page active-view w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 sm:pt-24 pb-16">
            <div class="mb-16">
                <h2 class="text-xl md:text-2xl font-bold mb-6 tracking-wide text-black font-space-grotesk">Newcomer</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 lg:gap-10 mb-8">
                    @php
                        $ncPortrait = isset($newcomer) && $newcomer->portrait_image ? (Str::startsWith($newcomer->portrait_image, 'http') ? $newcomer->portrait_image : (file_exists(public_path('storage/' . $newcomer->portrait_image)) ? asset('storage/' . $newcomer->portrait_image) : (Str::startsWith($newcomer->portrait_image, 'storage/') ? asset($newcomer->portrait_image) : asset($newcomer->portrait_image)))) : asset('masmus.jpeg');
                        $ncArtwork = isset($newcomer) && $newcomer->artwork_image ? (Str::startsWith($newcomer->artwork_image, 'http') ? $newcomer->artwork_image : (file_exists(public_path('storage/' . $newcomer->artwork_image)) ? asset('storage/' . $newcomer->artwork_image) : (Str::startsWith($newcomer->artwork_image, 'storage/') ? asset($newcomer->artwork_image) : asset($newcomer->artwork_image)))) : asset('home.jpg');
                    @endphp
                    <div class="aspect-[16/10] bg-gray-200 overflow-hidden group cursor-pointer"
                        onclick="window.location.href='/artists/{{ $newcomer->id ?? 1 }}'">
                        <img src="{{ $ncPortrait }}"
                            alt="{{ $newcomer->name ?? 'Mustafa Portrait' }}"
                            class="w-full h-full object-cover grayscale group-hover:scale-105 transition-all duration-500">
                    </div>
                    <div class="aspect-[16/10] bg-gray-200 overflow-hidden group cursor-pointer"
                        onclick="window.location.href='/artists/{{ $newcomer->id ?? 1 }}'">
                        <img src="{{ $ncArtwork }}" alt="{{ $newcomer->name ?? 'Mustafa Artwork' }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                    </div>
                </div>

                <div class="max-w-3xl mx-auto text-center my-8 px-2 space-y-4">
                    <h3 class="text-2xl md:text-3xl font-bold text-black font-space-grotesk">About {{ explode(' ', $newcomer->name ?? 'Mustafa')[0] }}</h3>
                    <p class="text-gray-700 text-sm md:text-base leading-relaxed font-regular font-inter">
                        {{ $newcomer->bio ?? 'Mustafa Alatas is a contemporary visual artist based in Temanggung whose distinct brush strokes and visceral storytelling bring deep cultural narratives into modern street apparel.' }}
                    </p>
                    <div class="pt-2">
                        <button onclick="window.location.href='/artists/{{ $newcomer->id ?? 1 }}'"
                            class="btn-brush text-xs md:text-sm py-2 px-6 cursor-pointer font-space-grotesk">Read More</button>
                    </div>
                </div>
            </div>

            <!-- ARTIST COLLABORATOR SECTION -->
            <div class="pt-8 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-xl md:text-2xl font-bold tracking-wide text-black font-space-grotesk uppercase">
                        Artist Collaborator
                    </h2>
                    <!-- Search Bar Sejajar dengan Tulisan Artist Collaborator -->
                    <div class="relative w-full sm:w-72 md:w-80">
                        <input type="text" id="artist-search-input" oninput="filterArtists(this.value)"
                            placeholder="Search artist or city..."
                            class="w-full bg-transparent border-b border-black py-1.5 pl-7 pr-3 text-xs md:text-sm font-inter tracking-wide focus:outline-none focus:border-[#d52c2b] placeholder-gray-400 transition-colors">
                        <svg class="w-4 h-4 text-black absolute left-0 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-4.35z" />
                        </svg>
                    </div>
                </div>

                <div id="artist-collab-grid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8 lg:gap-10">
                    @if(isset($artists) && $artists->count() > 0)
                        @foreach($artists as $artItem)
                            @php
                                $aPhoto = $artItem->photo ? (Str::startsWith($artItem->photo, 'http') ? $artItem->photo : (file_exists(public_path('storage/' . $artItem->photo)) ? asset('storage/' . $artItem->photo) : asset($artItem->photo))) : 'https://placehold.co/500x500/181818/ffffff?text=' . urlencode($artItem->name);
                            @endphp
                            <div class="artist-card group cursor-pointer" data-artist-name="{{ $artItem->name }}" data-artist-city="{{ $artItem->city }}" onclick="window.location.href='/artists/{{ $artItem->id }}'">
                                <div class="w-full aspect-square bg-gray-200 mb-3.5 overflow-hidden">
                                    <img src="{{ $aPhoto }}"
                                        alt="{{ $artItem->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <h4 class="font-bold text-base md:text-lg text-black font-space-grotesk">{{ $artItem->name }}</h4>
                                <p class="text-xs md:text-sm text-gray-500 font-inter mt-0.5">{{ $artItem->city ?: 'Indonesia' }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="artist-card group cursor-pointer" data-artist-name="Mustafa Alatas" data-artist-city="Temanggung" onclick="openArtistProfile('Mustafa Alatas', 'Temanggung')">
                            <div class="w-full aspect-square bg-gray-200 mb-3.5 overflow-hidden">
                                <img src="https://placehold.co/500x500/181818/ffffff?text=Mustafa+Alatas"
                                    alt="Mustafa Alatas"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <h4 class="font-bold text-base md:text-lg text-black font-space-grotesk">Mustafa Alatas</h4>
                            <p class="text-xs md:text-sm text-gray-500 font-inter mt-0.5">Temanggung</p>
                        </div>
                    @endif

                    <!-- Empty State for Artist Search -->
                    <div id="artist-no-results" class="hidden col-span-full text-center py-12">
                        <p class="text-gray-400 text-sm font-inter">No artist found matching your search.</p>
                    </div>
                </div>
            </div>
        </section>
@endsection
