@extends('layouts.app')

@section('title', 'Contact & Workshop - Notisse')

@section('content')
<!-- VIEW 6B: CONTACT PAGE -->
        <section id="view-contact"
            class="view-page active-view px-6 sm:px-12 md:px-16 lg:px-20 xl:px-24 py-20 md:py-28 min-h-[calc(100vh-80px)] flex flex-col justify-center items-start w-full">
            <div class="space-y-10 sm:space-y-12 text-left max-w-3xl">
                <!-- Workshop Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-normal text-black tracking-tight font-space-grotesk">Workshop :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide font-inter">
                        Jl Mangga Dua, Desa Sukapura, DayeuhKolot, Bandung.
                    </p>
                </div>

                <!-- Email Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-normal text-black tracking-tight font-space-grotesk">Email :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide font-inter">
                        notissebrand@gmail.com
                    </p>
                </div>

                <!-- Whatsapp Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-normal text-black tracking-tight font-space-grotesk">Whatsapp :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide font-inter">
                        +62 856 0771 4704
                    </p>
                </div>
            </div>
        </section>
@endsection
