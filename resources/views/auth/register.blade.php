@extends('layouts.app')

@section('title', 'Register - Notisse')

@section('content')
<!-- VIEW 8: REGISTER / DAFTAR AKUN PAGE -->
        <section id="view-register" class="view-page active-view px-6 pt-24 py-12 max-w-md mx-auto">
            <div class="bg-white border border-black rounded-2xl p-8 sm:p-10 shadow-2xl space-y-6">
                <!-- Signature N/Notisse Logo Top Center -->
                <div class="flex justify-center">
                    <img src="{{ asset('logowebkecil.png') }}" alt="Notisse" class="h-12 w-auto object-contain">
                </div>

                <!-- Headline Tagline -->
                <h2 class="text-center font-space-grotesk text-lg sm:text-xl font-medium text-black leading-snug px-2">
                    Join and engage with the creative journey of our artists
                </h2>

                <!-- Registration Form -->
                <form onsubmit="handleRegisterSubmit(event)" class="space-y-4 pt-2">
                    <div>
                        <input type="text" required placeholder="Full Name"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div>
                        <input type="email" required placeholder="Email Address"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div>
                        <input type="password" required placeholder="Password"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div>
                        <input type="password" required placeholder="Confirm Password"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div class="pt-4 flex justify-center">
                        <button type="submit" class="btn-brush w-full text-sm uppercase cursor-pointer font-space-grotesk">
                            CREATE ACCOUNT
                        </button>
                    </div>
                </form>

                <div class="text-center pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-500 font-inter">
                        Already have an account?
                        <a href="javascript:void(0)" href="/login"
                            class="font-bold underline text-black">Log in here</a>
                    </p>
                </div>
            </div>
        </section>
@endsection
