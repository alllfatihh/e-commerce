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
        @include('partials.views.shop.catalog')
        @include('partials.views.shop.detail')

        {{-- 2. Artist Collaborations & Physical Artworks --}}
        @include('partials.views.artist.collab')
        @include('partials.views.artist.detail')
        @include('partials.views.artist.artwork')

        {{-- 3. Editorial Articles & Stories --}}
        @include('partials.views.editorial.archive')
        @include('partials.views.editorial.detail')

        {{-- 4. Lookbooks & Collection Timelines --}}
        @include('partials.views.lookbook.timeline')
        @include('partials.views.lookbook.detail')

        {{-- 5. Brand Information & Contact --}}
        @include('partials.views.pages.about')
        @include('partials.views.pages.contact')

        {{-- 6. Customer Authentication & Accounts --}}
        @include('partials.views.auth.login')
        @include('partials.views.auth.register')
        @include('partials.views.account.profile')
        @include('partials.views.account.edit')
    </main>

    {{-- Global Footer & Marquee --}}
    @include('partials.footer')

    {{-- Frontend JavaScript Controller --}}
    @include('partials.scripts')
</body>

</html>
