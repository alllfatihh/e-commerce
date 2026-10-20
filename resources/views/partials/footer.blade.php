    <!-- RUANG KOSONG AGAR BISA DI-SCROLL KE BAWAH -->
    <div class="h-[150vh] w-full bg-white"></div>

    <!-- MARQUEE TEXT GLOBAL -->
    <div id="global-marquee"
        class="fixed bottom-0 left-0 z-30 w-full border-t border-black overflow-hidden flex whitespace-nowrap py-2 md:py-2.5 bg-black shadow-lg">
        @php
            $marqueeStr = !empty($settings['marquee_text']) 
                ? $settings['marquee_text'] 
                : 'NOTISSE WORLDWIDE SHIPPING';
        @endphp
        <div
            class="animate-marquee inline-block font-space-grotesk font-bold text-xs md:text-sm tracking-[0.22em] text-[#d52c2b] uppercase">
            {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span>&nbsp;
        </div>
        <div
            class="animate-marquee inline-block font-space-grotesk font-bold text-xs md:text-sm tracking-[0.22em] text-[#d52c2b] uppercase">
            {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span> {{ $marqueeStr }} <span class="mx-3 md:mx-5 font-light opacity-50">///</span>&nbsp;
        </div>
    </div>
