<!DOCTYPE html>
<html lang="id">

<head>
    @include('partials.head')
    @include('partials.styles')
    @stack('styles')
</head>

<body class="min-h-screen flex flex-col justify-between relative bg-white text-black antialiased selection:bg-black selection:text-white">

    {{-- Global Preloader & Header --}}
    @include('partials.loader')
    @include('partials.header')

    {{-- Overlays & Modals --}}
    @include('partials.overlays.menu-drawer')
    @include('partials.overlays.search-modal')
    @include('partials.overlays.cart-drawer')
    @include('partials.overlays.artwork-offering-modal')
    @include('partials.overlays.lightbox-modal')
    @include('partials.overlays.toast')

    {{-- Dynamic Page Content --}}
    <main class="flex-grow w-full relative">
        @yield('content')
    </main>

    {{-- Global Footer & Marquee --}}
    @include('partials.footer')

    {{-- Frontend JavaScript Controller --}}
    @include('partials.scripts')
    @stack('scripts')
</body>

</html>
