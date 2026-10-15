@extends('layouts.app')

@section('title', 'Login - Notisse')

@section('content')
<!-- VIEW 7: LOGIN PAGE -->
        <section id="view-login" class="view-page active-view px-6 pt-24 py-12 max-w-md mx-auto">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('logowebkecil.png') }}" alt="Notisse Logo" class="h-12 w-auto object-contain">
                </div>
                <h2 class="text-2xl font-bold font-space-grotesk uppercase tracking-wider text-black">Login Account</h2>
                <p class="text-xs text-gray-500 font-inter mt-1">Sign in to access your collection and orders.</p>
            </div>

            <form onsubmit="handleLoginSubmit(event)" class="space-y-4">
                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1 font-space-grotesk">Email</label>
                    <input type="email" required placeholder="name@email.com"
                        class="w-full border border-black rounded-lg p-3 text-sm focus:outline-none font-inter">
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1 font-space-grotesk">Password</label>
                    <input type="password" required placeholder="••••••••"
                        class="w-full border border-black rounded-lg p-3 text-sm focus:outline-none font-inter">
                </div>

                <div class="flex items-center justify-between text-xs font-inter pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="accent-black">
                        <span class="text-gray-600">Remember me</span>
                    </label>
                    <a href="javascript:void(0)" onclick="showToast('Password reset instructions sent to your email.')"
                        class="underline text-gray-500 hover:text-black">Forgot password?</a>
                </div>

                <div class="pt-4 flex justify-center">
                    <button type="submit" class="btn-brush w-full text-sm uppercase cursor-pointer font-space-grotesk">
                        LOG IN
                    </button>
                </div>

                <div class="text-center pt-4">
                    <p class="text-xs text-gray-500 font-inter">
                        Don't have an account yet?
                        <a href="javascript:void(0)" href="/register"
                            class="font-bold underline text-black">Register now</a>
                    </p>
                </div>
            </form>
        </section>
@endsection
