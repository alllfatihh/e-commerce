        <!-- VIEW 9: LOGGED-IN ACCOUNT DASHBOARD -->
        <section id="view-account-profile" class="view-page {{ ($activeView ?? '') === 'view-account-profile' ? 'active-view' : '' }} w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-24">
            <!-- Back to Shop (Top Left) -->
            <div class="mb-6">
                <button onclick="showPage('view-shop')"
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
                            $rawAvatar = auth()->user()->avatar;
                            $userAvatar = Str::startsWith($rawAvatar, ['http://', 'https://']) ? $rawAvatar : asset(ltrim($rawAvatar, '/'));
                        @endphp
                        <img src="{{ $userAvatar }}" alt="Avatar" class="w-16 h-16 object-cover border border-black shrink-0" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                        <div class="w-16 h-16 bg-black text-white hidden items-center justify-center font-bold text-xl font-space-grotesk tracking-wider border border-black shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'N', 0, 2)) }}
                        </div>
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

            <!-- TAB 2: ADDRESS BOOK (UP TO 3 SAVED ADDRESSES) -->
            <div id="acc-pane-address" class="hidden space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h2 class="text-base font-bold font-space-grotesk text-black uppercase tracking-wider">Saved Shipping Addresses</h2>
                            <span id="address-counter-badge" class="text-[10px] font-space-grotesk font-bold tracking-widest uppercase bg-neutral-100 border border-gray-200 px-2 py-0.5 text-black">
                                {{ auth()->check() ? auth()->user()->addresses()->count() : 0 }} / 3 ALAMAT
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 font-inter mt-1">Kelola hingga 3 alamat pengiriman untuk mempermudah pemilihan kurir & checkout.</p>
                    </div>
                    <button type="button" id="btn-add-address-trigger" onclick="openAddressModal()" class="px-4 py-2.5 bg-black hover:bg-neutral-800 text-white font-space-grotesk font-bold text-xs uppercase tracking-wider transition-all duration-200 flex items-center justify-center space-x-2 cursor-pointer shrink-0 shadow-sm {{ (auth()->check() && auth()->user()->addresses()->count() >= 3) ? 'hidden' : '' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>Add New Address</span>
                    </button>
                </div>

                <!-- Addresses Cards Grid -->
                <div id="saved-addresses-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @if(auth()->check() && auth()->user()->addresses->count() > 0)
                        @foreach(auth()->user()->addresses as $addr)
                            <div class="border {{ $addr->is_primary ? 'border-black ring-1 ring-black' : 'border-gray-200' }} bg-white p-5 flex flex-col justify-between space-y-4 hover:shadow-sm transition-all relative">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-widest px-2.5 py-0.5 bg-neutral-100 text-neutral-800 border border-gray-200">
                                            {{ $addr->label }}
                                        </span>
                                        @if($addr->is_primary)
                                            <span class="text-[9px] font-space-grotesk font-bold uppercase tracking-widest px-2 py-0.5 bg-black text-white">
                                                PRIMARY
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-black text-sm font-space-grotesk uppercase">{{ $addr->recipient_name }}</h3>
                                        <p class="text-xs text-gray-500 font-inter mt-0.5">{{ $addr->phone }}</p>
                                    </div>
                                    <div class="text-xs font-inter text-gray-700 leading-relaxed border-t border-gray-100 pt-2.5">
                                        <p class="line-clamp-3">{{ $addr->address }}</p>
                                        <div class="mt-2 text-[11px] text-gray-400 font-inter">
                                            <span class="font-semibold text-gray-600">Wilayah:</span> {{ $addr->area_name ?: $addr->area_id }}
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 pt-3 flex items-center justify-between text-xs font-space-grotesk">
                                    @if(!$addr->is_primary)
                                        <button type="button" onclick="setPrimaryAddress({{ $addr->id }})" class="text-[10px] font-bold uppercase tracking-wider text-neutral-600 hover:text-black underline cursor-pointer">
                                            Set as Primary
                                        </button>
                                    @else
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Default</span>
                                    @endif
                                    <div class="flex items-center space-x-3">
                                        <button type="button" onclick="editAddressModal({{ $addr->id }})" class="text-[10px] font-bold uppercase tracking-wider text-black hover:underline cursor-pointer">
                                            Edit
                                        </button>
                                        <button type="button" onclick="deleteAddressConfirm({{ $addr->id }})" class="text-[10px] font-bold uppercase tracking-wider text-red-500 hover:text-red-700 underline cursor-pointer">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full border border-gray-200 p-8 text-center bg-gray-50 space-y-3">
                            <p class="text-gray-400 font-inter text-sm">Belum ada alamat pengiriman yang tersimpan.</p>
                            <button type="button" onclick="openAddressModal()" class="px-4 py-2 bg-black text-white text-xs font-bold font-space-grotesk uppercase tracking-wider cursor-pointer">
                                + Tambah Alamat Sekarang
                            </button>
                        </div>
                    @endif
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
                        <button onclick="showPage('view-artist-detail')" class="mt-3 text-xs font-bold font-space-grotesk uppercase underline tracking-wider">
                            Explore Art Gallery
                        </button>
                    </div>
                </div>
            </div>
            </div>
        </section>

