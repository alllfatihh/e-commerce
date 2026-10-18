        <!-- VIEW 7: LOGIN PAGE -->
        <section id="view-login" class="view-page px-6 py-12 max-w-md mx-auto">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('logowebkecil.png') }}" alt="Notisse Logo" class="h-12 w-auto object-contain">
                </div>
                <h2 class="text-2xl font-bold font-space-grotesk uppercase tracking-wider text-black">Login Account</h2>
                <p class="text-xs text-gray-500 font-inter mt-1">Sign in to access your collection and orders.</p>
            </div>

            <!-- Express Social Sign In -->
            <div class="space-y-3 mb-4">
                <a href="/auth/google?from=login" id="google-login-btn" onclick="window.location.href='/auth/google?from=login'" class="w-full flex items-center justify-center space-x-3 bg-white hover:bg-neutral-50 border border-black rounded-lg py-3 px-4 transition-all shadow-sm group cursor-pointer relative z-10">
                    <svg class="w-4 h-4 pointer-events-none" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span class="font-space-grotesk font-bold text-xs uppercase tracking-wider text-black pointer-events-none">Sign in with Google</span>
                </a>

                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white px-3 text-gray-400 font-space-grotesk text-[10px] tracking-widest">Or with email</span>
                    </div>
                </div>
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
                        <a href="javascript:void(0)" onclick="showPage('view-register')"
                            class="font-bold underline text-black">Register now</a>
                    </p>
                </div>
            </form>
        </section>

