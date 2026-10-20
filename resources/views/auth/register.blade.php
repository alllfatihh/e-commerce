        <!-- VIEW 8: REGISTER / DAFTAR AKUN PAGE -->
        <section id="view-register" class="view-page {{ ($activeView ?? '') === 'view-register' ? 'active-view' : '' }} px-6 py-12 max-w-md mx-auto">
            <div class="bg-white border border-black rounded-2xl p-8 sm:p-10 shadow-2xl space-y-6">
                <!-- Signature N/Notisse Logo Top Center -->
                <div class="flex justify-center">
                    <img src="{{ asset('logowebkecil.png') }}" alt="Notisse" class="h-12 w-auto object-contain">
                </div>

                <!-- Headline Tagline -->
                <h2 class="text-center font-space-grotesk text-lg sm:text-xl font-medium text-black leading-snug px-2">
                    Join and engage with the creative journey of our artists
                </h2>

                <!-- Express Social Sign Up -->
                <div class="space-y-3 pt-2">
                    <a href="/auth/google?from=account" id="google-register-btn" onclick="window.location.href='/auth/google?from=account'" class="w-full flex items-center justify-center space-x-3 bg-white hover:bg-neutral-50 border border-black rounded-xl py-3 px-4 transition-all shadow-sm group cursor-pointer relative z-10">
                        <svg class="w-4 h-4 pointer-events-none" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span class="font-space-grotesk font-bold text-xs uppercase tracking-wider text-black pointer-events-none">Sign up with Google</span>
                    </a>

                    <div class="relative my-3">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase">
                            <span class="bg-white px-3 text-gray-400 font-space-grotesk text-[10px] tracking-widest">Or create with email</span>
                        </div>
                    </div>
                </div>

                <!-- Registration Form -->
                <form onsubmit="handleRegisterSubmit(event)" class="space-y-4">
                    <div id="register-error-msg" class="hidden p-3 bg-red-50 border-l-4 border-red-600 text-red-800 text-xs font-inter font-medium leading-relaxed"></div>

                    <div>
                        <input type="text" id="reg-name" required placeholder="Full Name"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div>
                        <input type="email" id="reg-email" required placeholder="Email Address"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div>
                        <input type="password" id="reg-password" required minlength="6" placeholder="Password (Min. 6 Characters)"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div>
                        <input type="password" id="reg-password-confirm" required minlength="6" placeholder="Confirm Password"
                            class="w-full border border-black rounded-xl p-3.5 text-sm focus:outline-none font-inter placeholder-gray-400">
                    </div>

                    <div class="pt-4 flex justify-center">
                        <button type="submit" id="register-submit-btn" class="btn-brush w-full text-sm uppercase cursor-pointer font-space-grotesk">
                            CREATE ACCOUNT
                        </button>
                    </div>
                </form>

                <div class="text-center pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-500 font-inter">
                        Already have an account?
                        <a href="javascript:void(0)" onclick="showPage('view-login')"
                            class="font-bold underline text-black">Log in here</a>
                    </p>
                </div>
            </div>
        </section>

