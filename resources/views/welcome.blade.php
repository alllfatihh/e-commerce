<!DOCTYPE html>
<html lang="id">

<head>
    @include('partials.head')
    @include('partials.styles')
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

    {{-- Main Application Page Views (Organized Per Module Folder) --}}
    <main class="flex-grow w-full relative">
        {{-- 1. Shop & Products --}}
        @include('shop.catalog')
        @include('shop.detail')

        {{-- 2. Artist Collaborations & Physical Artworks --}}
        @include('artists.collab')
        @include('artists.detail')
        @include('artists.artwork')

        {{-- 3. Editorial Articles & Stories --}}
        @include('editorials.archive')
        @include('editorials.detail')

        {{-- 4. Lookbooks & Collection Timelines --}}
        @include('lookbooks.timeline')
        @include('lookbooks.detail')

        {{-- 5. Brand Information & Contact --}}
        @include('pages.about')
        @include('pages.contact')

        {{-- 6. Customer Authentication & Accounts --}}
        @include('auth.login')
        @include('auth.register')
        @include('account.profile')
        @include('account.edit')
    </main>

    {{-- Global Footer & Marquee --}}
    @include('partials.footer')

    {{-- Frontend JavaScript Controller --}}
    @include('partials.scripts')
</body>

</html>
