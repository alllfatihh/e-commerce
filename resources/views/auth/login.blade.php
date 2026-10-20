        <!-- VIEW 7: LOGIN PAGE -->
        <section id="view-login" class="view-page {{ ($activeView ?? '') === 'view-login' ? 'active-view' : '' }} w-full">
            <div class="min-h-screen pt-20 pb-14 flex flex-col justify-center items-center px-4 sm:px-6 w-full">
                <div class="w-full max-w-md mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold font-space-grotesk uppercase tracking-wider text-black">Login Account</h2>
                        <p class="text-xs text-gray-500 font-inter mt-1.5">Sign in to access your collection and orders.</p>
                    </div>

                    <!-- Express Social Sign In -->
                    <div class="space-y-3 mb-5">
                        <a href="/auth/google?from=login" id="google-login-btn" onclick="window.location.href='/auth/google?from=login'" class="w-full flex items-center justify-center space-x-3 bg-white hover:bg-neutral-50 border border-black rounded-lg py-3.5 px-4 transition-all shadow-sm group cursor-pointer relative z-10">
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
                        <div id="login-error-msg" class="hidden p-3 bg-red-50 border-l-4 border-red-600 text-red-800 text-xs font-inter font-medium leading-relaxed"></div>

                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1 font-space-grotesk">Email</label>
                            <input type="email" id="login-email" required placeholder="name@email.com"
                                class="w-full border border-black rounded-lg p-3 text-sm focus:outline-none font-inter">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1 font-space-grotesk">Password</label>
                            <div class="relative">
                                <input type="password" id="login-password" required placeholder="••••••••"
                                    class="w-full border border-black rounded-lg p-3 pr-11 text-sm focus:outline-none font-inter">
                                <button type="button" onclick="togglePasswordVisibility('login-password', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black focus:outline-none p-1 transition-colors cursor-pointer"
                                    title="Tampilkan / Sembunyikan Password" aria-label="Toggle Password Visibility">
                                    <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <svg class="w-5 h-5 eye-slash-icon hidden" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs font-inter pt-1">
                            <label class="flex items-center space-x-2 cursor-pointer select-none">
                                <input type="checkbox" id="login-remember" class="accent-black">
                                <span class="text-gray-600">Remember me</span>
                            </label>
                            <a href="javascript:void(0)" onclick="showToast('Silakan masuk dengan akun Google atau hubungi admin.')"
                                class="underline text-gray-500 hover:text-black transition-colors">Forgot password?</a>
                        </div>

                        <div class="pt-4 flex justify-center w-full">
                            <button type="submit" id="login-submit-btn" class="btn-brush w-full text-sm uppercase cursor-pointer font-space-grotesk">
                                LOG IN
                            </button>
                        </div>

                        <div class="text-center pt-3">
                            <p class="text-xs text-gray-500 font-inter">
                                Don't have an account yet?
                                <a href="javascript:void(0)" onclick="showPage('view-register')"
                                    class="font-bold underline text-black hover:opacity-75 transition-opacity">Register now</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </section>

