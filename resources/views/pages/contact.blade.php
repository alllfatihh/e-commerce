        <!-- VIEW 6B: CONTACT PAGE -->
        <section id="view-contact"
            class="view-page {{ ($activeView ?? '') === 'view-contact' ? 'active-view' : '' }} px-6 sm:px-12 md:px-16 lg:px-20 xl:px-24 py-20 md:py-28 min-h-[calc(100vh-80px)] flex flex-col justify-center items-start w-full">
            <div class="space-y-10 sm:space-y-12 text-left max-w-3xl">
                <!-- Workshop Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-normal text-black tracking-tight font-space-grotesk">Workshop :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide font-inter">
                        {{ !empty($settings['contact_workshop']) ? $settings['contact_workshop'] : ($settings['workshop_address'] ?? 'Jl Mangga Dua, Desa Sukapura, DayeuhKolot, Bandung.') }}
                    </p>
                </div>

                <!-- Email Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-normal text-black tracking-tight font-space-grotesk">Email :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide font-inter">
                        @php $cEmail = !empty($settings['contact_email']) ? $settings['contact_email'] : 'notissebrand@gmail.com'; @endphp
                        <a href="mailto:{{ $cEmail }}" class="hover:underline">
                            {{ $cEmail }}
                        </a>
                    </p>
                </div>

                <!-- Whatsapp Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-normal text-black tracking-tight font-space-grotesk">Whatsapp :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide font-inter">
                        @php
                            $rawWa = !empty($settings['contact_phone']) ? $settings['contact_phone'] : '+62 856 0771 4704';
                            $waClean = preg_replace('/[^0-9]/', '', $rawWa);
                        @endphp
                        <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener noreferrer" class="hover:underline">
                            {{ $rawWa }}
                        </a>
                    </p>
                </div>

                @if(!empty($settings['instagram_link']) && $settings['instagram_link'] !== '#')
                <!-- Instagram Section -->
                <div class="space-y-2">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-normal text-black tracking-tight font-space-grotesk">Instagram :</h3>
                    <p class="text-gray-700 text-sm sm:text-base md:text-lg font-light tracking-wide font-inter">
                        @php
                            $igUrl = $settings['instagram_link'];
                            $igLabel = $igUrl;
                            if (str_contains($igUrl, 'instagram.com/')) {
                                $igParts = explode('instagram.com/', $igUrl);
                                $igLabel = '@' . trim(explode('?', $igParts[1] ?? '')[0], '/');
                            }
                        @endphp
                        <a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer" class="hover:underline">
                            {{ $igLabel }}
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </section>

