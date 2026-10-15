@extends('layouts.app')

@section('title', (isset($lookbook) ? $lookbook->title : 'Lookbook Collection') . ' - Notisse')

@section('content')
<!-- VIEW 5: LOOKBOOK DETAIL PAGE WITH WIDE VIDEO AT TOP -->
        <section id="view-lookbook-detail" class="view-page active-view min-h-screen pt-20 md:pt-24 pb-20">
            <div class="w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-2 pb-4">
                <button onclick="window.location.href='/lookbooks'"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO LOOKBOOKS</span>
                </button>
            </div>

            <!-- WIDE CINEMATIC LOOKBOOK CAMPAIGN VIDEO (MELEBAR DI ATAS) -->
            <div id="lookbook-video-wrapper" class="w-full bg-black mb-10 relative overflow-hidden shadow-2xl">
                <div class="w-full aspect-[21/8] min-h-[220px] max-h-[380px] md:max-h-[420px] relative bg-black">
                    <video id="lookbook-video-player"
                        class="w-full h-full object-cover"
                        autoplay loop muted playsinline controls
                        poster="{{ asset('home.jpg') }}">
                        <source id="lookbook-video-source" src="{{ asset('video/editorial.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <!-- Overlaid Thin Blur Glass Tint & Centered Title -->
                    <div class="absolute inset-0 bg-black/15 pointer-events-none flex items-center justify-center p-4 sm:p-8"
                        style="backdrop-filter: blur(1px); -webkit-backdrop-filter: blur(1px);">
                        <h2 id="lookbook-detail-title"
                            class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-medium tracking-[0.22em] text-white uppercase font-space-grotesk text-center drop-shadow-[0_2px_15px_rgba(0,0,0,0.5)] select-none">
                            2025 LOOKBOOK
                        </h2>
                    </div>
                </div>
            </div>

            <div class="w-full px-4 sm:px-6 md:px-10 lg:px-12">
                <div id="lookbook-photos-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-8 md:gap-12">
                    @foreach($fotoshoots as $foto)
                        <div class="w-full group cursor-pointer" onclick="openLightbox('{{ Str::startsWith($foto->image, 'http') ? $foto->image : (file_exists(public_path('storage/' . $foto->image)) ? asset('storage/' . $foto->image) : asset($foto->image)) }}', '{{ addslashes($foto->title ?? '') }}')">
                            <div class="overflow-hidden bg-[#d9d9d9] relative">
                                @php
                                    $lbUrl = Str::startsWith($foto->image, 'http') ? $foto->image : (file_exists(public_path('storage/' . $foto->image)) ? asset('storage/' . $foto->image) : asset($foto->image));
                                @endphp
                                <img src="{{ $lbUrl }}" alt="{{ $foto->title }}"
                                    class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                @if($foto->title)
                                    <div
                                        class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span
                                            class="text-white font-space-grotesk tracking-[0.2em] uppercase text-sm md:text-base">{{ $foto->title }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(isset($lookbook))
            openLookbookDetail({!! json_encode($lookbook->year) !!}, {!! json_encode($lookbook->title) !!});
        @endif
    });
</script>
@endpush
