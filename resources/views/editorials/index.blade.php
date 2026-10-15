@extends('layouts.app')

@section('title', 'Editorial Archive - Notisse')

@section('content')
<!-- VIEW 3C: ALL EDITORIALS ARCHIVE PAGE -->
        <section id="view-editorials-all" class="view-page active-view w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-16">
            <div class="mb-4 sm:mb-6">
                <button onclick="window.location.href='/shop'"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>
            <div class="mb-10 border-b border-gray-200 pb-5">
                <h1 class="text-2xl md:text-3xl font-bold font-space-grotesk text-black uppercase tracking-wider">Editorials</h1>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                @forelse($editorials as $ed)
                    @php
                        $edImg = $ed->image ? (Str::startsWith($ed->image, 'http') ? $ed->image : (file_exists(public_path('storage/' . $ed->image)) ? asset('storage/' . $ed->image) : asset($ed->image))) : 'https://placehold.co/600x600/222/fff?text=Editorial';
                    @endphp
                    <div onclick="openArtistEditorialDetailById({{ $ed->id }})" class="group cursor-pointer flex flex-col justify-between border border-gray-200 bg-white hover:border-black transition-colors duration-300">
                        <div class="relative w-full aspect-[16/10] bg-neutral-100 overflow-hidden">
                            <img src="{{ $edImg }}" alt="{{ $ed->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-5 flex flex-col justify-between flex-grow space-y-3">
                            <div>
                                <span class="text-[9px] font-bold tracking-widest uppercase text-gray-400 font-space-grotesk block mb-1">
                                    {{ $ed->artist ? $ed->artist->name : 'Notisse Editorial' }}
                                </span>
                                <h3 class="font-space-grotesk font-bold text-sm md:text-base text-black group-hover:underline line-clamp-2">
                                    {{ $ed->title }}
                                </h3>
                                @if($ed->excerpt)
                                    <p class="text-xs text-gray-600 font-inter mt-2 line-clamp-2">
                                        {{ $ed->excerpt }}
                                    </p>
                                @endif
                            </div>
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] font-space-grotesk font-semibold text-black uppercase">
                                <span>Read Essay</span>
                                <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-gray-400 font-inter">
                        No editorial articles published yet.
                    </div>
                @endforelse
            </div>
        </section>
@endsection
