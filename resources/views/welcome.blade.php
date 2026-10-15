<!DOCTYPE html>
<html lang="id">

<head>
    @include('partials.head')
    @include('partials.styles')
</head>

<body class="min-h-screen flex flex-col justify-between relative bg-white text-black antialiased selection:bg-black selection:text-white">

    @include('partials.loader')
    @include('partials.header')

    {{-- Overlays & Modals --}}
    @include('partials.overlays.menu-drawer')
    @include('partials.overlays.search-modal')
    @include('partials.overlays.cart-drawer')
    @include('partials.overlays.artwork-offering-modal')
    @include('partials.overlays.lightbox-modal')
    @include('partials.overlays.toast')

    {{-- Main Application Page Views --}}
    <main class="flex-grow w-full relative">
        @include('partials.views.shop')
        @include('partials.views.product-detail')
        @include('partials.views.artist-collab')
        @include('partials.views.artist-detail')
        @include('partials.views.artist-artwork-detail')
        @include('partials.views.editorials-all')
        @include('partials.views.editorial-detail')
        @include('partials.views.lookbook-list')
        @include('partials.views.lookbook-detail')
        @include('partials.views.about')
        @include('partials.views.contact')
        @include('partials.views.auth-login')
        @include('partials.views.auth-register')
        @include('partials.views.account-profile')
        @include('partials.views.account-edit-profile')
    </main>

    @include('partials.footer')

    @include('partials.scripts')
</body>

</html>
