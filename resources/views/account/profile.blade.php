@extends('layouts.app')

@section('title', 'My Account - Notisse')

@section('content')
<!-- VIEW 9: LOGGED-IN ACCOUNT DASHBOARD -->
        <section id="view-account-profile" class="view-page active-view w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-24">
            <!-- Back to Shop (Top Left) -->
            <div class="mb-6">
                <button onclick="window.location.href='/shop'"
                    class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK TO SHOP</span>
                </button>
            </div>

            <!-- Profile Content Container (Full Width / Full Screen) -->
            <div class="w-full space-y-6">

            <!-- Profile Hero Header Banner -->
            <div class="border border-black bg-white p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
                <div class="flex items-center space-x-5">
                    @if(auth()->check() && auth()->user()->avatar)
                        @php
                            $userAvatar = Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar);
                        @endphp
                        <img src="{{ $userAvatar }}" alt="Avatar" class="w-16 h-16 object-cover border border-black shrink-0">
                    @else
                        <div class="w-16 h-16 bg-black text-white flex items-center justify-center font-bold text-xl font-space-grotesk tracking-wider border border-black shrink-0">
                            {{ auth()->check() ? strtoupper(substr(auth()->user()->name ?? 'N', 0, 2)) : 'NT' }}
                        </div>
                    @endif
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-xl md:text-2xl font-bold font-space-grotesk uppercase tracking-tight text-black" id="account-header-name">
                                {{ auth()->check() ? auth()->user()->name : 'Notisse Member' }}
                            </h1>
                            <span class="text-[9px] font-bold font-space-grotesk tracking-widest uppercase bg-black text-white px-2 py-0.5">VERIFIED CLIENT</span>
                        </div>
                        <p id="user-display-email" class="text-xs text-neutral-500 font-inter">
                            {{ auth()->check() ? auth()->user()->email : 'user@notisse.com' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 self-start md:self-auto">
                    <button onclick="showEditProfile()"
                        class="px-4 py-2 border border-black hover:bg-black hover:text-white transition-colors text-xs font-semibold uppercase tracking-wider font-space-grotesk cursor-pointer">
                        Edit Profile
                    </button>
                    <button onclick="handleLogout()"
                        class="px-4 py-2 bg-neutral-100 hover:bg-red-600 hover:text-white text-neutral-700 transition-colors text-xs font-semibold uppercase tracking-wider font-space-grotesk cursor-pointer">
                        Logout
                    </button>
                </div>
            </div>

            <!-- Profile Section Tabs -->
            <div class="flex border-b border-gray-200 gap-2 sm:gap-6 overflow-x-auto">
                <button onclick="switchAccountTab('orders')" id="acc-tab-orders"
                    class="acc-tab-btn active pb-3 text-xs sm:text-sm font-bold font-space-grotesk uppercase tracking-wider border-b-2 border-black text-black cursor-pointer whitespace-nowrap">
                    Order History
                </button>
                <button onclick="switchAccountTab('address')" id="acc-tab-address"
                    class="acc-tab-btn pb-3 text-xs sm:text-sm font-medium font-space-grotesk uppercase tracking-wider border-b-2 border-transparent text-gray-400 hover:text-black transition-colors cursor-pointer whitespace-nowrap">
                    Address Book
                </button>
                <button onclick="switchAccountTab('offers')" id="acc-tab-offers"
                    class="acc-tab-btn pb-3 text-xs sm:text-sm font-medium font-space-grotesk uppercase tracking-wider border-b-2 border-transparent text-gray-400 hover:text-black transition-colors cursor-pointer whitespace-nowrap">
                    Artwork Inquiries
                </button>
            </div>

            <!-- TAB 1: ORDER HISTORY -->
            <div id="acc-pane-orders" class="space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold font-space-grotesk text-black uppercase tracking-wider">Orders & Purchases</h2>
                        <p class="text-xs text-gray-400 font-inter mt-0.5">Notisse product purchases and order history.</p>
                    </div>
                    <button onclick="fetchOrderHistory()" class="text-xs font-space-grotesk font-semibold uppercase tracking-wider hover:underline text-gray-500 hover:text-black">
                        Refresh
                    </button>
                </div>

                <div id="order-history-container" class="space-y-4">
                    <div class="border border-gray-200 p-8 text-center bg-gray-50">
                        <p class="text-gray-400 font-inter text-sm">Loading orders...</p>
                    </div>
                </div>
            </div>

            <!-- TAB 2: ADDRESS BOOK -->
            <div id="acc-pane-address" class="hidden space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold font-space-grotesk text-black uppercase tracking-wider">Primary Shipping Address</h2>
                        <p class="text-xs text-gray-400 font-inter mt-0.5">This address is used for shipping calculations during checkout.</p>
                    </div>
                    <button onclick="showEditProfile()" class="text-xs font-space-grotesk font-bold uppercase tracking-wider text-black underline">
                        Change Address
                    </button>
                </div>

                <div class="border border-gray-200 p-6 md:p-8 bg-white space-y-4">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-4">
                        <div class="flex items-center gap-4">
                            @if(auth()->check() && auth()->user()->avatar)
                                @php
                                    $userAvatarMobile = Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar);
                                @endphp
                                <img src="{{ $userAvatarMobile }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <span class="text-[10px] font-bold font-space-grotesk tracking-widest uppercase bg-neutral-100 text-neutral-600 px-2 py-0.5 inline-block mb-1.5">User Profile</span>
                                <h3 class="font-bold text-black text-base font-space-grotesk" id="user-display-name">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</h3>
                                <p class="text-xs text-gray-500 font-inter mt-0.5" id="user-display-phone">{{ auth()->check() && auth()->user()->phone ? auth()->user()->phone : 'No phone number set' }}</p>
                                <p class="text-xs text-gray-500 font-inter mt-0.5" id="user-display-birthdate">
                                    {{ auth()->check() && auth()->user()->birthdate ? 'Date of Birth: ' . \Carbon\Carbon::parse(auth()->user()->birthdate)->format('d M Y') : '' }}
                                </p>
                            </div>
                        </div>
                        <button onclick="showEditProfile()" class="text-xs font-semibold uppercase tracking-wider text-black hover:underline font-space-grotesk">
                            Edit
                        </button>
                    </div>
                    <div>
                        <p class="text-xs font-inter text-gray-700 leading-relaxed" id="user-display-address">
                            {{ auth()->check() && auth()->user()->address ? auth()->user()->address : 'No shipping address saved yet. Please add an address for seamless checkout.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- TAB 3: ARTWORK OFFERS INQUIRIES -->
            <div id="acc-pane-offers" class="hidden space-y-6">
                <div>
                    <h2 class="text-base font-bold font-space-grotesk text-black uppercase tracking-wider">Artwork Acquisition Inquiries</h2>
                    <p class="text-xs text-gray-400 font-inter mt-0.5">List of artwork acquisition offers submitted for curatorial review.</p>
                </div>

                <div id="account-art-offers-list" class="space-y-4">
                    <div class="border border-gray-200 p-8 text-center bg-gray-50">
                        <p class="text-gray-400 font-inter text-sm">No artwork acquisition inquiries submitted yet.</p>
                        <button onclick="window.location.href='/artists'" class="mt-3 text-xs font-bold font-space-grotesk uppercase underline tracking-wider">
                            Explore Art Gallery
                        </button>
                    </div>
                </div>
            </div>
            </div>
        </section>
@endsection
