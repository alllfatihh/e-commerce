    <!-- JAVASCRIPT CONTROLLER -->
    <script>
        // --- HELPER: Resolve Image URL ---
        function resolveImageUrl(path, fallback = '') {
            if (!path) return fallback;
            if (path.startsWith('http://') || path.startsWith('https://')) return path;
            if (path.startsWith('/storage/')) return path;
            if (path.startsWith('storage/')) return '/' + path;
            if (path.startsWith('products/') || path.startsWith('artists/') || path.startsWith('artworks/') || path.startsWith('editorials/') || path.startsWith('lookbooks/') || path.startsWith('site-media/') || path.startsWith('avatars/')) {
                return '/storage/' + path;
            }
            return path.startsWith('/') ? path : '/' + path;
        }

        let currentSelectedSize = "M";
        let currentSlideIndex = 0;
        let slideInterval = null;

        let lastScrollY = window.scrollY;
        const scrollDelta = 8;

        // Transparent Header with Smooth Fade on Scroll Down & Reveal on Scroll Up
        function updateHeaderState() {
            const activePage = document.querySelector('.view-page.active-view');
            const isShopPage = !activePage || activePage.id === 'view-shop';
            const mainHeader = document.getElementById('main-header');
            const logoContainer = document.getElementById('header-logo-container');
            const menuLeft = document.getElementById('menu-trigger');
            const searchTriggerBtn = document.getElementById('search-trigger');
            const cartTrigger = document.getElementById('cart-trigger');
            const currentScrollY = window.scrollY;
            const diff = currentScrollY - lastScrollY;

            if (!mainHeader) return;

            // Ensure header is always transparent
            mainHeader.classList.remove('bg-white/90', 'backdrop-blur-[1px]', 'border-b', 'border-black/[0.08]', 'shadow-[0_2px_12px_rgba(0,0,0,0.03)]');
            mainHeader.classList.add('bg-transparent', 'border-transparent');
            mainHeader.style.transform = 'translateY(0)';

            // Check if any modal/overlay is currently opened
            const overlayBackdrop = document.getElementById('overlay-menu-backdrop');
            const overlaySearch = document.getElementById('overlay-search');
            const overlayCart = document.getElementById('overlay-cart');
            const modalOffering = document.getElementById('modal-artwork-offering');
            const modalLightbox = document.getElementById('modal-image-lightbox');
            const isOverlayOpen = (overlayBackdrop && !overlayBackdrop.classList.contains('hidden')) ||
                                  (overlaySearch && !overlaySearch.classList.contains('-translate-y-full')) ||
                                  (overlayCart && !overlayCart.classList.contains('hidden')) ||
                                  (modalOffering && !modalOffering.classList.contains('hidden')) ||
                                  (modalLightbox && !modalLightbox.classList.contains('hidden'));

            if (currentScrollY <= 40) {
                // At the very top (window.scrollY <= 40):
                // ALL header controls are visible & interactive!
                if (menuLeft) {
                    menuLeft.classList.remove('opacity-0', 'pointer-events-none', '-translate-x-3');
                    menuLeft.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
                }
                if (searchTriggerBtn) {
                    searchTriggerBtn.classList.remove('opacity-0', 'pointer-events-none', 'header-fade-hidden');
                    searchTriggerBtn.classList.add('opacity-100', 'pointer-events-auto', 'header-fade-visible');
                }
                if (logoContainer) {
                    logoContainer.classList.remove('header-fade-hidden', 'logo-state-small');
                    logoContainer.classList.add('header-fade-visible', 'logo-state-large');
                }
                if (cartTrigger && !isOverlayOpen) {
                    cartTrigger.classList.remove('opacity-0', 'pointer-events-none', 'state-minimal');
                    cartTrigger.classList.add('opacity-100', 'pointer-events-auto');
                }
            } else {
                // Scrolled down beyond top (> 40px):
                // 1. Burger menu ALWAYS stays visible & interactive
                if (menuLeft) {
                    menuLeft.classList.remove('opacity-0', 'pointer-events-none', '-translate-x-3');
                    menuLeft.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
                }

                // 2. Center logo smoothly FADES OUT completely (like A24 Shop)
                if (logoContainer) {
                    logoContainer.classList.add('header-fade-hidden');
                    logoContainer.classList.remove('header-fade-visible');
                }

                // 3. Search trigger smoothly FADES OUT in place
                if (searchTriggerBtn) {
                    searchTriggerBtn.classList.add('header-fade-hidden');
                    searchTriggerBtn.classList.remove('header-fade-visible');
                }

                // 4. Cart button smoothly fades horizontal label out & vertical label in (A24 minimal vertical layout),
                // remaining fixed at the top right to follow the scroll ("ngikut")
                if (cartTrigger && !isOverlayOpen) {
                    cartTrigger.classList.remove('opacity-0', 'pointer-events-none');
                    cartTrigger.classList.add('opacity-100', 'pointer-events-auto', 'state-minimal');
                }
            }
            lastScrollY = currentScrollY <= 0 ? 0 : currentScrollY;
        }

        let isHeaderTicking = false;
        function handleHeaderScroll() {
            if (!isHeaderTicking) {
                requestAnimationFrame(() => {
                    updateHeaderState();
                    isHeaderTicking = false;
                });
                isHeaderTicking = true;
            }
        }

        window.addEventListener('scroll', handleHeaderScroll, { passive: true });
        updateHeaderState();

        let navigationHistory = ['view-shop'];
        let isAuthenticated = localStorage.getItem('notisse_auth') === 'true' || {{ auth()->check() ? 'true' : 'false' }};
        let loggedInUser = @json(auth()->user());
        if (loggedInUser) {
            localStorage.setItem('notisse_user', JSON.stringify(loggedInUser));
            localStorage.setItem('notisse_auth', 'true');
        } else if (localStorage.getItem('notisse_user')) {
            try { loggedInUser = JSON.parse(localStorage.getItem('notisse_user')); } catch(e){}
        }

        let productsData = @json($products ?? []);
        let artistsData = @json($artists ?? []);
        let editorialsData = @json($editorials ?? []);
        let lookbooksData = @json($lookbooks ?? []);
        let currentProduct = null;
        let cartState = [];
        
        async function fetchCart() {
            try {
                const response = await fetch('/api/cart', { headers: { 'Accept': 'application/json' }});
                cartState = await response.json();
                renderCartItems();
            } catch (e) {
                console.error("Failed to load cart:", e);
            }
        }
        
        document.addEventListener('DOMContentLoaded', fetchCart);

        function saveCartState() {
            // No-op, managed by backend now
        }

        const overlayMenuBackdrop = document.getElementById('overlay-menu-backdrop');
        const overlayMenuSidebar = document.getElementById('overlay-menu-sidebar');
        const overlaySearch = document.getElementById('overlay-search');
        const overlayCart = document.getElementById('overlay-cart');
        const cartDrawerContent = document.getElementById('cart-drawer-content');

        const menuTrigger = document.getElementById('menu-trigger');
        const menuClose = document.getElementById('menu-close');
        const searchTrigger = document.getElementById('search-trigger');
        const searchClose = document.getElementById('search-close');
        const cartTrigger = document.getElementById('cart-trigger');
        const cartClose = document.getElementById('cart-close');

        const PAGE_URL_MAP = {
            'view-shop': '/',
            'view-artist-collab': '/artist',
            'view-editorials-all': '/editorial',
            'view-lookbook-list': '/lookbook',
            'view-about': '/about',
            'view-contact': '/contact',
            'view-login': '/login',
            'view-register': '/register',
            'view-account-profile': '/account',
            'view-edit-profile': '/account/edit',
            'view-account-edit': '/account/edit',
        };

        function showPage(pageId, isBacking = false, updateUrl = true) {
            const activePage = document.querySelector('.view-page.active-view');
            const currentActiveId = activePage ? activePage.id : null;

            if (!isBacking && currentActiveId && currentActiveId !== pageId) {
                navigationHistory.push(currentActiveId);
            }

            document.querySelectorAll('.view-page').forEach(page => page.classList.remove('active-view'));
            const targetPage = document.getElementById(pageId);
            if (targetPage) targetPage.classList.add('active-view');
            closeSizeDropdown();
            window.scrollTo({ top: 0, behavior: 'auto' });
            lastScrollY = 0;
            const mainHeader = document.getElementById('main-header');
            if (mainHeader) {
                mainHeader.style.transform = 'translateY(0)';
                mainHeader.classList.remove('header-hidden');
                mainHeader.classList.add('header-visible');
            }
            updateHeaderState();

            // Save active view in sessionStorage
            sessionStorage.setItem('notisse_active_view', pageId);

            // Update browser URL so page refresh always stays on the current view
            if (updateUrl && PAGE_URL_MAP[pageId]) {
                let targetUrl = PAGE_URL_MAP[pageId];
                if (pageId === 'view-account-profile') {
                    const savedTab = sessionStorage.getItem('notisse_active_acc_tab');
                    if (savedTab && savedTab !== 'orders') {
                        targetUrl += `#${savedTab}`;
                    }
                }
                if (window.location.pathname !== targetUrl && (window.location.pathname + window.location.hash) !== targetUrl) {
                    history.pushState({ pageId: pageId }, '', targetUrl);
                }
            }
        }

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else if (navigationHistory.length > 0) {
                const previousPageId = navigationHistory.pop();
                showPage(previousPageId, true);
            } else {
                showPage('view-shop', true);
            }
        }

        function navigateTo(pageId) {
            closeMenu();
            showPage(pageId);
        }

        window.addEventListener('popstate', (event) => {
            const state = event.state;
            if (state && state.pageId) {
                if (state.pageId === 'view-product-detail' && state.productId) {
                    openProductDetail(state.productId, null, null, false);
                } else if (state.pageId === 'view-artist-detail' && state.artistId) {
                    openArtistProfile(state.artistId, null, false);
                } else if (state.pageId === 'view-artist-artwork-detail' && state.artworkId) {
                    openArtistArtworkDetailById(state.artworkId, false);
                } else if (state.pageId === 'view-artist-editorial-detail' && state.editorialId) {
                    openArtistEditorialDetailById(state.editorialId, false);
                } else if (state.pageId === 'view-lookbook-detail' && state.lookbookId) {
                    openLookbookDetail(state.lookbookId, false);
                } else {
                    showPage(state.pageId, true, false);
                    if (state.pageId === 'view-account-profile' && state.tab) {
                        switchAccountTab(state.tab);
                    }
                }
            } else {
                const path = window.location.pathname;
                if (path === '/' || path === '/shop') {
                    showPage('view-shop', true, false);
                } else if (path.startsWith('/product/') || path.startsWith('/products/')) {
                    const pid = path.replace(/^\/products?\//, '');
                    openProductDetail(pid, null, null, false);
                } else if (path === '/artist' || path === '/artists' || path === '/artist-collab') {
                    showPage('view-artist-collab', true, false);
                } else if (path.startsWith('/artist/') || path.startsWith('/artists/')) {
                    const aid = path.replace(/^\/artists?\//, '');
                    openArtistProfile(aid, null, false);
                } else if (path.startsWith('/artwork/') || path.startsWith('/artworks/')) {
                    const wid = path.replace(/^\/artworks?\//, '');
                    openArtistArtworkDetailById(wid, false);
                } else if (path === '/editorial' || path === '/editorials') {
                    showPage('view-editorials-all', true, false);
                } else if (path.startsWith('/editorial/') || path.startsWith('/editorials/')) {
                    const eid = path.replace(/^\/editorials?\//, '');
                    openArtistEditorialDetailById(eid, false);
                } else if (path === '/lookbook' || path === '/lookbooks') {
                    showPage('view-lookbook-list', true, false);
                } else if (path.startsWith('/lookbook/') || path.startsWith('/lookbooks/')) {
                    const lid = path.replace(/^\/lookbooks?\//, '');
                    openLookbookDetail(lid, false);
                } else if (path === '/about') {
                    showPage('view-about', true, false);
                } else if (path === '/contact') {
                    showPage('view-contact', true, false);
                } else if (path === '/login') {
                    showPage('view-login', true, false);
                } else if (path === '/register') {
                    showPage('view-register', true, false);
                } else if (path === '/account') {
                    showPage('view-account-profile', true, false);
                    const hash = window.location.hash.replace('#', '');
                    if (hash) switchAccountTab(hash);
                } else if (path === '/account/edit') {
                    showPage('view-edit-profile', true, false);
                } else {
                    showPage('view-shop', true, false);
                }
            }

            if (window.location.hash === '#cart' || (state && state.cartOpen)) {
                openCart(false);
            } else if (overlayCart && !overlayCart.classList.contains('hidden') && window.location.hash !== '#cart') {
                closeCart(false);
            }
        });

        /* ACCOUNT FUNCTIONALITIES */
        function handleAccountButtonClick() {
            if (loggedInUser) {
                showPage('view-account-profile');
                fetchOrderHistory();
            } else {
                if (document.getElementById('view-login')) {
                    showPage('view-login');
                } else {
                    window.location.href = '/login';
                }
            }
        }

        function switchAccountTab(tab) {
            const tabs = ['orders', 'address', 'offers'];
            tabs.forEach(t => {
                const btn = document.getElementById(`acc-tab-${t}`);
                const pane = document.getElementById(`acc-pane-${t}`);
                if (btn && pane) {
                    if (t === tab) {
                        btn.className = "acc-tab-btn active pb-3 text-xs sm:text-sm font-bold font-space-grotesk uppercase tracking-wider border-b-2 border-black text-black cursor-pointer whitespace-nowrap";
                        pane.classList.remove('hidden');
                    } else {
                        btn.className = "acc-tab-btn pb-3 text-xs sm:text-sm font-medium font-space-grotesk uppercase tracking-wider border-b-2 border-transparent text-gray-400 hover:text-black transition-colors cursor-pointer whitespace-nowrap";
                        pane.classList.add('hidden');
                    }
                }
            });

            sessionStorage.setItem('notisse_active_acc_tab', tab);
            if (window.location.pathname === '/account' || window.location.pathname === '/') {
                history.replaceState({ pageId: 'view-account-profile', tab: tab }, '', `/account#${tab}`);
            }

            if (tab === 'orders') {
                fetchOrderHistory();
            } else if (tab === 'offers') {
                renderArtworkOffers();
            }
        }

        async function renderArtworkOffers() {
            const container = document.getElementById('account-art-offers-list');
            if (!container) return;
            
            let offers = [];
            try {
                const response = await fetch('/my-artwork-offers');
                if (response.ok) {
                    const serverOffers = await response.json();
                    if (Array.isArray(serverOffers) && serverOffers.length > 0) {
                        offers = serverOffers.map(o => ({
                            refId: o.reference_number || '#NTS-ART',
                            date: o.created_at,
                            status: o.status || 'Dalam Tinjauan Kurasi',
                            offerPrice: (Number(o.offering_price) || 0).toLocaleString('id-ID'),
                            artwork: {
                                title: o.artwork?.title || 'Notisse Artwork',
                                artist: o.artwork?.artist?.name || 'Curated Artist',
                                image: o.artwork?.image || '/footage-baju.jpg'
                            }
                        }));
                    }
                }
            } catch (e) {
                console.warn('Could not fetch artwork offers from backend, falling back to localStorage');
            }

            if (offers.length === 0) {
                try {
                    offers = JSON.parse(localStorage.getItem('notisse_artwork_offers') || '[]');
                } catch (e) {
                    offers = [];
                }
            }
            
            if (offers.length === 0) {
                container.innerHTML = `
                    <div class="border border-gray-200 p-8 text-center bg-gray-50">
                        <p class="text-gray-400 font-inter text-sm">No artwork acquisition inquiries submitted yet.</p>
                        <button onclick="showPage('view-artist-detail')" class="mt-3 text-xs font-bold font-space-grotesk uppercase underline tracking-wider cursor-pointer">
                            Explore Art Gallery & Editorial
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = offers.map((offer) => {
                const artwork = offer.artwork || {};
                const displayDate = offer.date ? new Date(offer.date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';
                return `
                    <div class="border border-gray-200 p-6 bg-white space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-gray-100 gap-2">
                            <div>
                                <span class="text-[10px] font-mono font-bold tracking-widest uppercase bg-black text-white px-2 py-0.5 inline-block">${offer.refId || '#NTS-ART'}</span>
                                <span class="text-xs text-gray-400 font-inter ml-2">${displayDate}</span>
                            </div>
                            <span class="text-[10px] font-semibold tracking-wider uppercase text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded font-space-grotesk w-fit">
                                ${offer.status || 'Under Curatorial Review'}
                            </span>
                        </div>
                        <div class="flex items-start space-x-4">
                            <img src="${resolveImageUrl(artwork.image, '/footage-baju.jpg')}" alt="${artwork.title || 'Artwork'}" class="w-16 h-20 object-cover bg-gray-100 border border-gray-200 flex-shrink-0">
                            <div class="space-y-1 min-w-0 flex-1">
                                <h4 class="font-bold text-sm text-black font-space-grotesk truncate">${artwork.title || 'Notisse Artwork'}</h4>
                                <p class="text-xs text-gray-500 font-inter">Artist: <span class="font-semibold text-black">${artwork.artist || 'Curated Artist'}</span></p>
                                <p class="text-xs text-emerald-700 font-space-grotesk font-bold pt-1">Acquisition Value: Rp ${offer.offerPrice} IDR</p>
                                ${offer.message ? `<p class="text-xs text-gray-500 font-inter italic bg-neutral-50 p-2 border border-neutral-100 mt-2">"${offer.message}"</p>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).reverse().join('');
        }


        async function fetchOrderHistory() {
            try {
                const response = await fetch('/my-orders');
                const orders = await response.json();
                
                const container = document.getElementById('order-history-container');
                if (!container) return;
                container.innerHTML = '';
                
                if (orders.length === 0) {
                    container.innerHTML = `
                        <div class="border border-gray-200 rounded-lg p-6 space-y-4 bg-gray-50 text-center">
                            <p class="text-gray-500 font-inter text-sm">No recent orders found.</p>
                        </div>
                    `;
                    return;
                }
                
                orders.forEach(order => {
                    let itemsHtml = '';
                    if (order.items && order.items.length > 0) {
                        order.items.forEach(item => {
                            itemsHtml += `
                            <div class="flex items-center space-x-4 mt-4">
                                <div class="w-12 h-16 bg-gray-100 flex-shrink-0 border border-gray-200 overflow-hidden">
                                    <img src="${resolveImageUrl(item.product?.image || item.image, '{{ asset('footage-baju.jpg') }}')}" alt="${item.product_name}" class="w-full h-full object-cover">
                                </div>
                                <div class="text-xs font-inter">
                                    <p class="font-bold text-black font-space-grotesk uppercase">${item.product_name}</p>
                                    <p class="text-gray-500">Size: ${item.size} | Qty: ${item.quantity}</p>
                                    <p class="font-semibold text-black mt-1">Rp ${parseInt(item.price).toLocaleString('id-ID')},00</p>
                                </div>
                            </div>
                            `;
                        });
                    }

                    let statusClass = "bg-black text-white";
                    let actionButtons = '';
                    const st = (order.status || '').toLowerCase();
                    if (st === 'paid') {
                        statusClass = "bg-green-600 text-white";
                    } else if (st === 'pending') {
                        statusClass = "bg-yellow-500 text-black";
                        actionButtons = `
                            <button onclick="payOrder(${order.id})" class="text-[10px] text-white bg-black px-3 py-1.5 font-bold uppercase tracking-wider rounded font-space-grotesk mt-2 w-full hover:bg-neutral-800 transition-colors cursor-pointer">Pay Now</button>
                            <button onclick="cancelOrder(${order.id})" class="text-[10px] text-red-600 font-bold uppercase tracking-wider hover:underline font-space-grotesk mt-2 w-full text-center cursor-pointer">Cancel Order</button>
                        `;
                    } else if (st === 'cancelled') {
                        statusClass = "bg-red-600 text-white";
                    }

                    const placedDate = new Date(order.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });

                    container.innerHTML += `
                    <div class="border border-gray-200 rounded-lg p-6 space-y-4 bg-gray-50">
                        <div class="flex justify-between items-start text-xs font-inter pb-3 border-b border-gray-200">
                            <div>
                                <p class="font-bold text-black font-space-grotesk">ORDER #NTS-${order.id}</p>
                                <p class="text-gray-400">Placed on ${placedDate}</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="px-2.5 py-1 ${statusClass} font-bold rounded text-[10px] tracking-wider uppercase font-space-grotesk">${order.status}</span>
                                <div class="mt-2 w-full flex flex-col gap-1">
                                    ${actionButtons}
                                </div>
                            </div>
                        </div>
                        ${itemsHtml}
                    </div>
                    `;
                });
            } catch(e) {
                console.error("Failed to load orders:", e);
            }
        }

        async function cancelOrder(id) {
            if (!confirm("Are you sure you want to cancel this order?")) return;
            try {
                const response = await fetch(`/my-orders/${id}/cancel`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });
                if (response.ok) {
                    showToast("Order cancelled successfully.");
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast("Failed to cancel order.");
                }
            } catch (e) {
                console.error(e);
                showToast("A system error occurred.");
            }
        }

        async function payOrder(id) {
            try {
                const response = await fetch(`/my-orders/${id}/pay`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                const data = await response.json();
                
                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: async function(result) {
                            showToast("Payment success!");
                            await fetch('/midtrans/local-success', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ order_id: result.order_id })
                            });
                            fetchOrderHistory();
                        },
                        onPending: async function(result) {
                            showToast("Waiting your payment...");
                            await fetch('/midtrans/local-success', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ order_id: result.order_id })
                            });
                            fetchOrderHistory();
                            showToast("Payment success (Simulated)!");
                        },
                        onError: function(result) {
                            showToast("Payment failed!");
                        },
                        onClose: function() {
                            showToast('You closed the popup without finishing the payment');
                        }
                    });
                } else {
                    showToast(data.error || "Failed to process payment");
                }
            } catch (err) {
                console.error(err);
                showToast("A system error occurred.");
            }
        }

        function handleLoginSubmit(e) {
            e.preventDefault();
            isAuthenticated = true;
            localStorage.setItem('notisse_auth', 'true');
            localStorage.setItem('notisse_user', JSON.stringify(loggedInUser));
            document.getElementById('user-display-email').innerText = loggedInUser.email;
            document.getElementById('user-display-name').innerText = loggedInUser.name;
            if (document.getElementById('user-display-address')) {
                document.getElementById('user-display-address').innerText = loggedInUser.address ? loggedInUser.address : 'No shipping address saved yet.';
            }
            showToast('Login Successful!');
            showPage('view-account-profile');
            fetchOrderHistory();
        }

        function handleRegisterSubmit(e) {
            e.preventDefault();
            isAuthenticated = true;
            localStorage.setItem('notisse_auth', 'true');
            localStorage.setItem('notisse_user', JSON.stringify(loggedInUser));
            document.getElementById('user-display-email').innerText = loggedInUser.email;
            document.getElementById('user-display-name').innerText = loggedInUser.name;
            if (document.getElementById('user-display-address')) {
                document.getElementById('user-display-address').innerText = loggedInUser.address ? loggedInUser.address : 'No shipping address saved yet.';
            }
            showToast('Account Created Successfully!');
            showPage('view-account-profile');
            fetchOrderHistory();
        }

        async function handleLogout() {
            try {
                await fetch('/logout', { method: 'POST' });
            } catch (e) {
                console.error(e);
            }
            isAuthenticated = false;
            loggedInUser = null;
            localStorage.removeItem('notisse_auth');
            localStorage.removeItem('notisse_user');
            window.location.reload();
        }

        function openMenu() {
            overlayMenuBackdrop.classList.remove('hidden');
            setTimeout(() => {
                overlayMenuBackdrop.classList.remove('opacity-0');
                overlayMenuSidebar.classList.remove('-translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            overlayMenuSidebar.classList.add('-translate-x-full');
            overlayMenuBackdrop.classList.add('opacity-0');
            setTimeout(() => {
                overlayMenuBackdrop.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        menuTrigger.addEventListener('click', openMenu);
        menuClose.addEventListener('click', closeMenu);

        /* LOGIKA REAL-TIME / INSTANT LIVE SEARCH */
        /* LOGIKA REAL-TIME / INSTANT LIVE SEARCH (FULL-SCREEN EDITORIAL) */
        function openSearch() {
            overlaySearch.classList.remove('-translate-y-full');
            overlaySearch.classList.add('translate-y-0');
            document.body.style.overflow = 'hidden';
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 250);
                handleLiveSearch(searchInput.value || "");
            }
        }

        function closeSearch() {
            overlaySearch.classList.remove('translate-y-0');
            overlaySearch.classList.add('-translate-y-full');
            document.body.style.overflow = 'auto';
        }

        function applySearchQuery(query) {
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.value = query;
                handleLiveSearch(query);
            }
        }

        searchTrigger.addEventListener('click', openSearch);
        searchClose.addEventListener('click', closeSearch);

        // Close on ESC key
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeSearch();
                closeMenu();
                closeCart();
                closeArtworkOfferingModal();
                closeLightbox();
            }
        });

        function handleLiveSearch(query) {
            const gridContainer = document.getElementById('search-results-grid');
            const metaContainer = document.getElementById('search-results-meta');
            const countSpan = document.getElementById('search-results-count');

            const cleanQuery = (query || "").trim().toLowerCase();

            // Filter produk berdasarkan nama produk atau artis/deskripsi
            const filteredProducts = cleanQuery === "" 
                ? productsData 
                : productsData.filter(item => {
                    return item.name.toLowerCase().includes(cleanQuery) || 
                           (item.artist && item.artist.toLowerCase().includes(cleanQuery));
                });

            if (countSpan) countSpan.innerText = filteredProducts.length;
            if (metaContainer) {
                metaContainer.classList.remove('hidden');
                metaContainer.innerHTML = cleanQuery === "" 
                    ? `ALL COLLECTION PRODUCTS (<span id="search-results-count">${filteredProducts.length}</span>)` 
                    : `SEARCH RESULTS (<span id="search-results-count">${filteredProducts.length}</span>)`;
            }

            if (filteredProducts.length === 0) {
                gridContainer.innerHTML = `
                    <div class="col-span-full text-center py-20 text-gray-400 font-inter text-sm space-y-2">
                        <p class="text-base text-black font-space-grotesk uppercase font-semibold">No products found matching "<span class="underline">${query}</span>"</p>
                        <p class="text-xs text-gray-400 font-inter">Try searching for keywords like "Tee", "Tank", "Shirt", or "Blazer".</p>
                    </div>
                `;
                return;
            }

            // Render daftar produk secara instan ke grid dengan gambar asli
            let html = '';
            filteredProducts.forEach(item => {
                const escapedName = item.name.replace(/'/g, "\\'");
                html += `
                    <div onclick="selectSearchResult('${escapedName}', '${item.price}', '${item.image}')" 
                        class="group cursor-pointer flex flex-col justify-between bg-white border border-gray-100 hover:border-black transition-all duration-300 p-2.5 sm:p-3 shadow-sm hover:shadow-md">
                        <div class="relative w-full aspect-[3/4] bg-[#f4f4f4] mb-3 overflow-hidden">
                            <img src="${resolveImageUrl(item.image, '{{ asset('footage-baju.jpg') }}')}" alt="${item.name}" 
                                onerror="this.src='{{ asset('footage-baju.jpg') }}'"
                                class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="flex flex-col justify-between flex-grow pt-1 border-t border-gray-100">
                            <h4 class="font-space-grotesk font-semibold text-xs sm:text-sm text-black group-hover:underline truncate uppercase">${item.name}</h4>
                            <p class="font-space-grotesk text-xs text-gray-500 mt-1">${item.price}</p>
                        </div>
                    </div>
                `;
            });

            gridContainer.innerHTML = html;
        }

        function selectSearchResult(name, price, image) {
            closeSearch();
            openProductDetail(name, price, image);
        }

        /* CART DRAWER LOGIC */
        function openCart(updateUrl = true) {
            renderCartItems();
            overlayCart.classList.remove('hidden');
            const cartTrigger = document.getElementById('cart-trigger');
            if (cartTrigger) cartTrigger.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                overlayCart.classList.remove('opacity-0');
                cartDrawerContent.classList.remove('translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
            
            if (loggedInUser && loggedInUser.area_id && cartState.length > 0) {
                fetchShippingRates(loggedInUser.area_id);
            }

            if (updateUrl && !window.location.hash.includes('cart')) {
                history.pushState({ cartOpen: true }, '', window.location.pathname + '#cart');
            }
            try {
                sessionStorage.setItem('notisse_cart_open', 'true');
            } catch(e) {}
        }

        function closeCart(updateUrl = true) {
            cartDrawerContent.classList.add('translate-x-full');
            overlayCart.classList.add('opacity-0');
            setTimeout(() => {
                overlayCart.classList.add('hidden');
                document.body.style.overflow = 'auto';
                updateHeaderState();
            }, 300);

            if (updateUrl && window.location.hash === '#cart') {
                history.pushState({}, '', window.location.pathname);
            }
            try {
                sessionStorage.removeItem('notisse_cart_open');
            } catch(e) {}
        }

        cartTrigger.addEventListener('click', () => openCart(true));
        cartClose.addEventListener('click', () => closeCart(true));

        function openProductDetail(productIdOrName, price, image, updateUrl = true) {
            let product = null;
            if (typeof productIdOrName === 'number') {
                product = productsData ? productsData.find(p => p.id === productIdOrName) : null;
            } else if (typeof productIdOrName === 'string') {
                product = productsData ? productsData.find(p => p.name === productIdOrName || String(p.id) === productIdOrName) : null;
            }

            if (product) {
                currentProduct = product;
            } else {
                currentProduct = {
                    id: typeof productIdOrName === 'number' ? productIdOrName : 1,
                    name: productIdOrName || 'Product',
                    price: price ? (typeof price === 'string' ? parseFloat(price.replace(/[^0-9]/g, '')) : price) : 250000,
                    stocks: [
                        { size: 'S', stock: 5 },
                        { size: 'M', stock: 10 },
                        { size: 'L', stock: 5 },
                        { size: 'XL', stock: 2 }
                    ]
                };
            }

            const titleEl = document.getElementById('pdetail-title');
            if (titleEl) titleEl.innerText = currentProduct.name;

            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(currentProduct.price);
            const priceEl = document.getElementById('pdetail-price');
            if (priceEl) priceEl.innerText = formattedPrice;

            if (image) {
                const imgEl = document.getElementById('pdetail-image');
                if (imgEl) imgEl.src = image;
            } else if (currentProduct.image) {
                const imgEl = document.getElementById('pdetail-image');
                if (imgEl) {
                    imgEl.src = resolveImageUrl(currentProduct.image, '{{ asset('footage-baju.jpg') }}');
                }
            }

            // Update Back View Image
            const backImgEl = document.getElementById('pdetail-back-image');
            if (backImgEl) {
                if (currentProduct.back_image) {
                    backImgEl.src = resolveImageUrl(currentProduct.back_image, '{{ asset('footage-baju-belakang.jpg') }}');
                } else {
                    backImgEl.src = "{{ asset('footage-baju-belakang.jpg') }}";
                }
            }

            // Update On-Model Footage Image
            const footImgEl = document.getElementById('pdetail-footage-image');
            if (footImgEl) {
                if (currentProduct.footage_image) {
                    footImgEl.src = resolveImageUrl(currentProduct.footage_image, '{{ asset('footagebaju2.jpg') }}');
                } else {
                    footImgEl.src = "{{ asset('footagebaju2.jpg') }}";
                }
            }

            // Update Size Chart Image & Lightbox Click
            const sizeChartBox = document.getElementById('pdetail-size-box');
            const sizeChartImg = document.getElementById('pdetail-size-img');
            const chartSrc = resolveImageUrl(currentProduct.size_chart_image, "{{ asset('size chart 1.png') }}");
            if (sizeChartImg) sizeChartImg.src = chartSrc;
            if (sizeChartBox) sizeChartBox.onclick = () => openLightbox(chartSrc, (currentProduct.name || 'NOTISSE APPAREL') + ' SIZE CHART');

            // Update Artist byline
            const artistEl = document.getElementById('pdetail-artist');
            if (artistEl) {
                const artistName = currentProduct.artist || 'Mustafa Alatas';
                artistEl.innerText = artistName;
                artistEl.onclick = () => openArtistProfile(artistName, 'Temanggung');
            }

            // Update Description & Fabric Specs (Consistent with Terms and Condition style)
            const descContent = document.getElementById('pdetail-desc-content');
            if (descContent) {
                let bulletItems = [];

                if (currentProduct.specs) {
                    if (currentProduct.specs.includes('•')) {
                        currentProduct.specs.split('•').forEach(s => {
                            const trimmed = s.trim();
                            if (trimmed) bulletItems.push(trimmed);
                        });
                    } else if (currentProduct.specs.includes('\n')) {
                        currentProduct.specs.split('\n').forEach(s => {
                            const trimmed = s.trim();
                            if (trimmed) bulletItems.push(trimmed);
                        });
                    } else {
                        const trimmed = currentProduct.specs.trim();
                        if (trimmed) bulletItems.push(trimmed);
                    }
                }

                const descText = currentProduct.description || "Cotton Combed 16s\nRaster Plastisol Screen Printing\nDigitized Painting";
                descText.split('\n').forEach(line => {
                    const cleanLine = line.replace(/^[•\-\*\s]+/, '').trim();
                    if (cleanLine && !bulletItems.some(item => item.toLowerCase() === cleanLine.toLowerCase())) {
                        bulletItems.push(cleanLine);
                    }
                });

                if (bulletItems.length === 0) {
                    bulletItems = [
                        "Heavyweight 24s Cotton Combed construction",
                        "High-density screenprinted artwork on back and chest",
                        "Boxy relaxed fit with reinforced ribbed collar",
                        "Crafted & printed in Bandung, Indonesia"
                    ];
                }

                let html = `<ul class="list-disc pl-5 space-y-2 leading-relaxed font-inter text-xs sm:text-[13px] md:text-sm text-gray-500">`;
                bulletItems.forEach(item => {
                    html += `<li>${item}</li>`;
                });
                html += `</ul>`;

                descContent.innerHTML = html;
            }

            const sizeMenu = document.getElementById('size-dropdown-menu');
            if (sizeMenu) {
                sizeMenu.innerHTML = '';
                const sizeNameMap = {
                    'S': 'Small',
                    'M': 'Medium',
                    'L': 'Large',
                    'XL': 'Extra Large',
                    'XXL': 'Double Extra Large',
                    '2XL': 'Double Extra Large',
                    'XXXL': 'Triple Extra Large',
                    '3XL': 'Triple Extra Large'
                };
                if (currentProduct.stocks && currentProduct.stocks.length > 0) {
                    currentProduct.stocks.forEach(stock => {
                        const sUpper = (stock.size || '').toUpperCase().trim();
                        const fullName = sizeNameMap[sUpper] || stock.size;
                        if (stock.stock > 0) {
                            sizeMenu.innerHTML += `
                                <div onclick="selectSize('${stock.size}')"
                                    class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider cursor-pointer hover:bg-black hover:text-white transition-colors border-b border-gray-100 flex justify-between items-center font-space-grotesk">
                                    <span>${fullName}</span><span class="text-xs opacity-50">${stock.size}</span>
                                </div>
                            `;
                        } else {
                            sizeMenu.innerHTML += `
                                <div class="size-option-item px-4 py-2.5 text-xs md:text-sm font-semibold uppercase tracking-wider border-b border-gray-100 flex justify-between items-center font-space-grotesk text-gray-300 cursor-not-allowed">
                                    <span>${fullName} (Out of Stock)</span><span class="text-xs opacity-50">${stock.size}</span>
                                </div>
                            `;
                        }
                    });
                } else {
                    sizeMenu.innerHTML = '<div class="px-4 py-2.5 text-xs font-space-grotesk text-gray-400">Out of Stock</div>';
                }
            }

            const sizeLabel = document.getElementById('selected-size-label');
            if (sizeLabel) sizeLabel.innerText = 'Select size: ';
            currentSelectedSize = null;

            // Reset all accordions and size dropdown to collapsed state
            document.querySelectorAll('#pdetail-left-col .accordion-content').forEach(c => c.classList.remove('expanded'));
            document.querySelectorAll('#pdetail-left-col .chevron-icon').forEach(i => i.classList.remove('rotated'));
            closeSizeDropdown();
            updateProductDetailScroll();

            showPage('view-product-detail', false, false);
            if (updateUrl && currentProduct && currentProduct.id) {
                history.pushState({ pageId: 'view-product-detail', productId: currentProduct.id }, '', '/product/' + currentProduct.id);
                try {
                    sessionStorage.setItem('notisse_active_view', 'view-product-detail');
                    sessionStorage.setItem('notisse_product_id', currentProduct.id);
                } catch (e) {}
            }
        }

        function openArtistProfile(artistIdOrName, location, updateUrl = true) {
            let artist = null;
            if (typeof artistsData !== 'undefined' && artistsData) {
                if (typeof artistIdOrName === 'number' || (!isNaN(Number(artistIdOrName)) && String(artistIdOrName).trim() !== '')) {
                    artist = artistsData.find(a => Number(a.id) === Number(artistIdOrName));
                }
                if (!artist) {
                    artist = artistsData.find(a => (a.name || '').toLowerCase() === String(artistIdOrName || '').toLowerCase());
                }
            }

            const name = artist ? artist.name : (typeof artistIdOrName === 'string' ? artistIdOrName : 'Mustafa Alatas');
            const loc = artist && artist.city ? artist.city : (location || 'Indonesia');

            const nameEl = document.getElementById('artist-profile-name');
            if (nameEl) nameEl.innerText = name;

            const locEl = document.getElementById('artist-profile-location');
            if (locEl) locEl.innerText = loc;

            const titleEl = document.getElementById('editorial-section-title');
            if (titleEl) titleEl.innerText = "Articles Featuring " + name;

            if (artist) {
                if (artist.bio) {
                    const bioEl = document.getElementById('artist-profile-bio') || document.querySelector('#view-artist-detail .md\\:col-span-8 p.text-gray-700') || document.querySelector('#view-artist-detail .md\\:col-span-8 p.text-gray-800');
                    if (bioEl) bioEl.innerText = artist.bio;
                }
                if (artist.photo) {
                    const photoEl = document.getElementById('artist-profile-photo') || document.querySelector('#view-artist-detail .md\\:col-span-4 img');
                    if (photoEl) {
                        photoEl.src = resolveImageUrl(artist.photo, '/masmus.jpeg');
                    }
                }
            }

            // Populate Editorial Articles dynamically per artist
            const edGrid = document.getElementById('artist-editorial-grid');
            const edEmpty = document.getElementById('artist-editorial-empty');
            if (edGrid) {
                edGrid.innerHTML = '';
                const editorials = (artist && artist.editorials) ? artist.editorials : [];
                if (editorials.length > 0) {
                    if (edEmpty) edEmpty.classList.add('hidden');
                    edGrid.classList.remove('hidden');
                    editorials.forEach(ed => {
                        const edImg = resolveImageUrl(ed.image, 'https://placehold.co/600x600/d9d9d9/555555?text=Editorial');
                        const card = document.createElement('div');
                        card.className = 'group cursor-pointer';
                        card.onclick = () => openArtistEditorialDetailById(ed.id);
                        card.innerHTML = `
                            <div class="w-full aspect-square bg-[#d9d9d9] mb-3 overflow-hidden">
                                <img src="${edImg}" alt="${ed.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <h4 class="font-bold text-xs md:text-sm text-black font-space-grotesk group-hover:underline leading-snug">
                                ${ed.title}
                            </h4>
                            <div class="mt-2.5 flex items-center justify-between text-[11px] font-space-grotesk">
                                <span class="text-black font-semibold hover:underline" onclick="event.stopPropagation(); openArtistEditorialDetailById(${ed.id})">Read Preview &rarr;</span>
                                <span onclick="event.stopPropagation(); navigateTo('view-editorials-all')" class="text-brandRed hover:underline font-bold inline-flex items-center gap-1 cursor-pointer">
                                    <span>Editorial Page</span>
                                    <span>&nearr;</span>
                                </span>
                            </div>
                        `;
                        edGrid.appendChild(card);
                    });
                } else {
                    edGrid.classList.add('hidden');
                    if (edEmpty) edEmpty.classList.remove('hidden');
                }
            }

            // Populate Artworks dynamically per artist
            const artGrid = document.getElementById('artist-artwork-grid');
            const artEmpty = document.getElementById('artist-artwork-empty');
            if (artGrid) {
                artGrid.innerHTML = '';
                const artworks = (artist && artist.artworks) ? artist.artworks : [];
                if (artworks.length > 0) {
                    if (artEmpty) artEmpty.classList.add('hidden');
                    artGrid.classList.remove('hidden');
                    artworks.forEach(artw => {
                        const artImg = resolveImageUrl(artw.image, 'https://placehold.co/600x800/d9d9d9/555555?text=Artwork');
                        const card = document.createElement('div');
                        card.className = 'group cursor-pointer';
                        card.onclick = () => openArtistArtworkDetail(artw.title, name, artw.medium, artw.dimensions, artImg, artw.price, artw.id);
                        card.innerHTML = `
                            <div class="w-full aspect-square bg-[#d9d9d9] mb-3.5 overflow-hidden relative">
                                <img src="${artImg}" alt="${artw.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <h4 class="font-bold text-sm sm:text-base text-black font-space-grotesk group-hover:underline">
                                ${artw.title}
                            </h4>
                        `;
                        artGrid.appendChild(card);
                    });
                } else {
                    artGrid.classList.add('hidden');
                    if (artEmpty) artEmpty.classList.remove('hidden');
                }
            }

            switchArtistTab('editorial');
            showPage('view-artist-detail', false, false);
            if (updateUrl && artist && artist.id) {
                history.pushState({ pageId: 'view-artist-detail', artistId: artist.id }, '', '/artist/' + artist.id);
                try {
                    sessionStorage.setItem('notisse_active_view', 'view-artist-detail');
                    sessionStorage.setItem('notisse_artist_id', artist.id);
                } catch (e) {}
            }
        }

        function filterArtists(query) {
            const q = (query || '').toLowerCase().trim();
            const cards = document.querySelectorAll('#artist-collab-grid .artist-card');
            let matchCount = 0;
            cards.forEach(card => {
                const name = (card.getAttribute('data-artist-name') || '').toLowerCase();
                const city = (card.getAttribute('data-artist-city') || '').toLowerCase();
                if (!q || name.includes(q) || city.includes(q)) {
                    card.style.display = '';
                    matchCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            const noResults = document.getElementById('artist-no-results');
            if (noResults) {
                noResults.style.display = matchCount === 0 ? 'block' : 'none';
            }
        }

        function switchArtistTab(tab) {
            const btnArtwork = document.getElementById('tab-btn-artwork');
            const btnEditorial = document.getElementById('tab-btn-editorial');
            const contentArtwork = document.getElementById('artist-tab-content-artwork');
            const contentEditorial = document.getElementById('artist-tab-content-editorial');

            if (tab === 'artwork') {
                btnArtwork.className = 'pb-3 font-medium text-black border-b-2 border-black transition-colors focus:outline-none cursor-pointer font-space-grotesk';
                btnEditorial.className = 'pb-3 font-medium text-gray-400 hover:text-black transition-colors focus:outline-none cursor-pointer font-space-grotesk';
                contentArtwork.classList.remove('hidden');
                contentEditorial.classList.add('hidden');
            } else {
                btnEditorial.className = 'pb-3 font-medium text-black border-b-2 border-black transition-colors focus:outline-none cursor-pointer font-space-grotesk';
                btnArtwork.className = 'pb-3 font-medium text-gray-400 hover:text-black transition-colors focus:outline-none cursor-pointer font-space-grotesk';
                contentEditorial.classList.remove('hidden');
                contentArtwork.classList.add('hidden');
            }
        }

        let currentActiveArtwork = {
            title: "Mereka Ulang Ungkapan Indah Leila",
            artist: "Mustafa Alatas",
            medium: "Oil on canvas",
            dimensions: "60x60cm",
            image: "https://placehold.co/1000x750/d9d9d9/555555?text=Full+Artwork+View"
        };

        function openArtistArtworkDetail(title, artist, medium, dimensions, image, price, id = null, updateUrl = true) {
            currentActiveArtwork = {
                id: id,
                title: title || 'Artwork',
                artist: artist || 'Mustafa Alatas',
                medium: medium || 'Oil on canvas',
                dimensions: dimensions || '60x60cm',
                image: image || 'https://placehold.co/1000x750/d9d9d9/555555?text=Full+Artwork+View',
                price: price || 0
            };

            const titleEl = document.getElementById('artwork-title');
            if (titleEl) titleEl.innerText = currentActiveArtwork.title;

            const artistEl = document.getElementById('artwork-artist-name');
            if (artistEl) artistEl.innerText = currentActiveArtwork.artist;

            const mediumEl = document.getElementById('artwork-medium');
            if (mediumEl) mediumEl.innerText = "Medium: " + currentActiveArtwork.medium;

            const dimEl = document.getElementById('artwork-dimensions');
            if (dimEl) dimEl.innerText = "Dimensions: " + currentActiveArtwork.dimensions;

            const imgEl = document.getElementById('artwork-detail-image');
            if (imgEl && currentActiveArtwork.image) imgEl.src = currentActiveArtwork.image;

            const priceEl = document.getElementById('artwork-price-display');
            if (priceEl) {
                const pVal = Number(currentActiveArtwork.price || 0);
                priceEl.innerText = pVal > 0 ? 'Rp ' + pVal.toLocaleString('id-ID') : 'Price on Inquiry';
            }

            showPage('view-artist-artwork-detail', false, false);
            if (updateUrl && id) {
                history.pushState({ pageId: 'view-artist-artwork-detail', artworkId: id }, '', '/artwork/' + id);
                try {
                    sessionStorage.setItem('notisse_active_view', 'view-artist-artwork-detail');
                    sessionStorage.setItem('notisse_artwork_id', id);
                } catch (e) {}
            }
        }

        function openArtistArtworkDetailById(id, updateUrl = true) {
            let foundArt = null;
            let foundArtist = null;
            if (typeof artistsData !== 'undefined' && artistsData) {
                for (const a of artistsData) {
                    if (a.artworks) {
                        const art = a.artworks.find(w => Number(w.id) === Number(id));
                        if (art) {
                            foundArt = art;
                            foundArtist = a;
                            break;
                        }
                    }
                }
            }
            if (foundArt) {
                const artImg = resolveImageUrl(foundArt.image, 'https://placehold.co/1000x750/d9d9d9/555555?text=Full+Artwork+View');
                openArtistArtworkDetail(
                    foundArt.title,
                    foundArtist ? foundArtist.name : 'Mustafa Alatas',
                    foundArt.medium || 'Oil on canvas',
                    foundArt.dimensions || '60x60cm',
                    artImg,
                    foundArt.price || 0,
                    foundArt.id,
                    updateUrl
                );
            }
        }

        /* ARTWORK OFFERING MODAL LOGIC */
        function triggerArtworkOffering(title, artist, medium, dimensions, image, price) {
            currentActiveArtwork = {
                title: title || 'Artwork',
                artist: artist || 'Mustafa Alatas',
                medium: medium || 'Oil on canvas',
                dimensions: dimensions || '60x60cm',
                image: image || 'https://placehold.co/400x500/222/fff?text=Artwork',
                price: price || 0
            };
            openArtworkOfferingModal();
        }

        function triggerArtworkOfferingCurrent() {
            openArtworkOfferingModal();
        }

        function openArtworkOfferingModal() {
            const modal = document.getElementById('modal-artwork-offering');
            const formBody = document.getElementById('artwork-offering-form-body');
            const successBody = document.getElementById('artwork-offering-success-body');

            // Populate Preview Info
            const prevImg = document.getElementById('offering-artwork-img');
            const prevTitle = document.getElementById('offering-artwork-title');
            const prevArtist = document.getElementById('offering-artwork-artist');
            const prevSpecs = document.getElementById('offering-artwork-specs');

            const curArt = currentActiveArtwork || {};
            if (prevImg) prevImg.src = resolveImageUrl(curArt.image, 'https://placehold.co/400x500/222/fff?text=Artwork');
            if (prevTitle) prevTitle.innerText = curArt.title || 'Artwork';
            if (prevArtist) prevArtist.innerText = curArt.artist || 'Mustafa Alatas';
            if (prevSpecs) prevSpecs.innerText = `${curArt.medium || 'Oil on canvas'} • ${curArt.dimensions || '60x60cm'}`;

            // Populate User Account Info from logged-in session / storage
            let activeUser = loggedInUser;
            try {
                const storedUser = localStorage.getItem('notisse_user');
                if (storedUser) activeUser = JSON.parse(storedUser);
            } catch (e) {}

            const userName = (activeUser && activeUser.name) ? activeUser.name : 'Collector / Guest';
            const userEmail = (activeUser && activeUser.email) ? activeUser.email : 'guest@notisse.com';

            const userNameEl = document.getElementById('offering-user-name');
            const userEmailEl = document.getElementById('offering-user-email');
            const userAvatarEl = document.getElementById('offering-user-avatar');

            if (userNameEl) userNameEl.innerText = userName;
            if (userEmailEl) userEmailEl.innerText = userEmail;
            if (userAvatarEl) {
                const initials = userName.split(' ').filter(Boolean).map(n => n[0]).join('').substring(0, 2).toUpperCase();
                userAvatarEl.innerText = initials || 'NT';
            }

            // Populate price
            const fixedPriceDisplay = document.getElementById('offering-fixed-price-display');
            const priceInput = document.getElementById('offering-buyer-price');
            const messageInput = document.getElementById('offering-buyer-message');
            const pVal = Number(curArt.price || 0);
            const formatted = pVal > 0 ? 'Rp ' + pVal.toLocaleString('id-ID') : 'Rp 0';
            if (fixedPriceDisplay) fixedPriceDisplay.innerText = formatted;
            if (priceInput) priceInput.value = pVal > 0 ? pVal : '0';
            if (messageInput) messageInput.value = '';

            // Reset view state
            if (formBody) formBody.classList.remove('hidden');
            if (successBody) successBody.classList.add('hidden');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                const inner = modal.querySelector('div');
                if (inner) inner.classList.remove('scale-95');
            }, 10);
            document.body.style.overflow = 'hidden';
            updateHeaderState();
        }

        function closeArtworkOfferingModal() {
            const modal = document.getElementById('modal-artwork-offering');
            if (!modal) return;
            const inner = modal.querySelector('div');
            if (inner) inner.classList.add('scale-95');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                updateHeaderState();
            }, 300);
        }

        function formatOfferingPriceInput(input) {
            let val = input.value.replace(/\D/g, '');
            if (!val) {
                input.value = '';
                return;
            }
            input.value = new Intl.NumberFormat('id-ID').format(val);
        }

        async function handleArtworkOfferingSubmit(e) {
            e.preventDefault();
            const priceStr = document.getElementById('offering-buyer-price').value.trim();
            const message = document.getElementById('offering-buyer-message').value.trim();

            if (!priceStr) {
                showToast("Please enter an offer amount.");
                return;
            }

            const rawPrice = priceStr.replace(/\D/g, '');

            let activeUser = loggedInUser;
            try {
                const storedUser = localStorage.getItem('notisse_user');
                if (storedUser) activeUser = JSON.parse(storedUser);
            } catch (err) {}

            if (!activeUser) {
                showToast("Please login or create an account first.");
                return;
            }

            const btn = document.querySelector('#artwork-offering-form-body button[type="submit"]');
            const origText = btn.innerText;
            btn.innerText = "SUBMITTING...";
            btn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            try {
                const response = await fetch('/api/artwork-offerings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        artwork_id: currentActiveArtwork.id,
                        offering_price: rawPrice
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    const succRef = document.getElementById('success-offering-ref');
                    const succArtist = document.getElementById('success-artist-name');
                    const succTitle = document.getElementById('success-artwork-title');
                    const succPrice = document.getElementById('success-artwork-price');
                    const succBuyer = document.getElementById('success-buyer-name');

                    if (succRef) succRef.innerText = data.data.reference_number;
                    if (succArtist) succArtist.innerText = data.data.artist_name;
                    if (succTitle) succTitle.innerText = data.data.artwork_title;
                    if (succPrice) succPrice.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(data.data.offering_price) + " IDR";
                    if (succBuyer) succBuyer.innerText = `${data.data.buyer_name} (${data.data.buyer_email})`;

                    document.getElementById('artwork-offering-form-body').classList.add('hidden');
                    document.getElementById('artwork-offering-success-body').classList.remove('hidden');

                    showToast("Acquisition inquiry submitted successfully!");
                } else {
                    showToast(data.message || "Failed to submit acquisition inquiry.");
                }
            } catch (err) {
                console.error(err);
                showToast("A system error occurred while submitting inquiry.");
            } finally {
                btn.innerText = origText;
                btn.disabled = false;
            }
        }

        /* LIGHTBOX IMAGE VIEWER LOGIC (SIZE CHART & ARTWORK) */
        function openLightbox(src, caption) {
            const lightbox = document.getElementById('modal-image-lightbox');
            const img = document.getElementById('lightbox-img');
            const cap = document.getElementById('lightbox-caption');

            if (!lightbox || !img) return;

            img.src = src;
            if (cap) cap.innerText = caption || '';

            lightbox.classList.remove('hidden');
            setTimeout(() => {
                lightbox.classList.remove('opacity-0');
            }, 10);
            document.body.style.overflow = 'hidden';
            updateHeaderState();
        }

        function closeLightbox() {
            const lightbox = document.getElementById('modal-image-lightbox');
            if (!lightbox) return;

            lightbox.classList.add('opacity-0');
            setTimeout(() => {
                lightbox.classList.add('hidden');
                document.body.style.overflow = 'auto';
                updateHeaderState();
            }, 300);
        }

        function openArtistEditorialDetail(title) {
            showPage('view-artist-editorial-detail');
        }

        function openArtistEditorialDetailById(id, updateUrl = true) {
            let foundEd = null;
            let foundArtist = null;

            if (typeof editorialsData !== 'undefined' && editorialsData && editorialsData.length > 0) {
                foundEd = editorialsData.find(e => e.id == id);
                if (foundEd && foundEd.artist) {
                    foundArtist = foundEd.artist;
                }
            }

            if (!foundEd && typeof artistsData !== 'undefined' && artistsData) {
                for (const a of artistsData) {
                    if (a.editorials) {
                        const ed = a.editorials.find(e => e.id == id);
                        if (ed) {
                            foundEd = ed;
                            foundArtist = a;
                            break;
                        }
                    }
                }
            }

            if (foundEd) {
                const titleEl = document.getElementById('editorial-detail-title');
                if (titleEl) titleEl.innerText = foundEd.title;

                const authorEl = document.getElementById('editorial-detail-author');
                if (authorEl) authorEl.innerText = (foundEd.author || 'Tim Editorial Notisse') + (foundArtist ? ' • Featuring ' + foundArtist.name : '');

                const topContent = document.getElementById('editorial-detail-content-top');
                const bottomContent = document.getElementById('editorial-detail-content-bottom');
                const rawText = foundEd.content || foundEd.excerpt || '';
                const paragraphs = rawText.split('\n').map(p => p.trim()).filter(Boolean);

                if (topContent) topContent.innerHTML = '';
                if (bottomContent) bottomContent.innerHTML = '';

                if (paragraphs.length <= 1) {
                    if (topContent && paragraphs[0]) {
                        const pEl = document.createElement('p');
                        pEl.className = 'leading-relaxed text-base sm:text-lg md:text-[1.05rem] text-gray-800 font-inter';
                        pEl.innerText = paragraphs[0];
                        topContent.appendChild(pEl);
                    }
                    if (bottomContent && foundEd.excerpt && foundEd.excerpt !== paragraphs[0]) {
                        const pEl = document.createElement('p');
                        pEl.className = 'leading-relaxed text-base sm:text-lg md:text-[1.05rem] text-gray-800 font-inter';
                        pEl.innerText = foundEd.excerpt;
                        bottomContent.appendChild(pEl);
                    }
                } else {
                    // Paragraf 1 di atas foto
                    if (topContent) {
                        const pEl = document.createElement('p');
                        pEl.className = 'leading-relaxed text-base sm:text-lg md:text-[1.05rem] text-gray-800 font-inter';
                        pEl.innerText = paragraphs[0];
                        topContent.appendChild(pEl);
                    }
                    // Paragraf 2 di bawah foto
                    if (bottomContent) {
                        for (let i = 1; i < paragraphs.length; i++) {
                            const pEl = document.createElement('p');
                            pEl.className = 'leading-relaxed text-base sm:text-lg md:text-[1.05rem] text-gray-800 font-inter';
                            pEl.innerText = paragraphs[i];
                            bottomContent.appendChild(pEl);
                        }
                    }
                }

                const edImg = resolveImageUrl(foundEd.image, '/home.jpg');
                const portraitEl = document.getElementById('editorial-detail-portrait');
                if (portraitEl) {
                    portraitEl.src = (foundArtist && foundArtist.photo) 
                        ? resolveImageUrl(foundArtist.photo, (edImg.includes('masmus') ? edImg : '/masmus.jpeg')) 
                        : (edImg.includes('masmus') ? edImg : '/masmus.jpeg');
                }

                let artImg = '/artwork.jpeg';
                if (foundArtist && foundArtist.artworks && foundArtist.artworks.length > 0) {
                    const firstArt = foundArtist.artworks[0].image;
                    artImg = resolveImageUrl(firstArt, '/artwork.jpeg');
                } else if (foundArtist && foundArtist.artwork_preview) {
                    artImg = resolveImageUrl(foundArtist.artwork_preview, '/artwork.jpeg');
                }
                const artworkEl = document.getElementById('editorial-detail-artwork');
                if (artworkEl) artworkEl.src = artImg;

                let featuredImg = '/home.jpg';
                if (foundEd.image && !foundEd.image.includes('masmus') && (!foundArtist || foundEd.image !== foundArtist.photo)) {
                    featuredImg = edImg;
                } else if (foundArtist && foundArtist.artworks && foundArtist.artworks.length > 2) {
                    const featArt = foundArtist.artworks[2].image;
                    featuredImg = resolveImageUrl(featArt, '/home.jpg');
                }
                const featuredEl = document.getElementById('editorial-detail-featured');
                if (featuredEl) {
                    featuredEl.src = featuredImg;
                }
            }

            showPage('view-artist-editorial-detail', false, false);
            if (updateUrl && id) {
                history.pushState({ pageId: 'view-artist-editorial-detail', editorialId: id }, '', '/editorial/' + id);
                try {
                    sessionStorage.setItem('notisse_active_view', 'view-artist-editorial-detail');
                    sessionStorage.setItem('notisse_editorial_id', id);
                } catch (e) {}
            }
        }

        /* LOOKBOOK DYNAMIC LOADER & DETAIL */
        function openLookbookDetail(id, updateUrl = true) {
            let lb = null;
            if (typeof lookbooksData !== 'undefined' && lookbooksData && lookbooksData.length > 0) {
                if (id) {
                    lb = lookbooksData.find(l => l.id == id) || lookbooksData[0];
                } else {
                    lb = lookbooksData[0];
                }
            }

            const titleEl = document.getElementById('lookbook-detail-title');
            if (titleEl) {
                titleEl.innerText = lb ? (lb.title || (lb.year + ' LOOKBOOK')) : '2025 LOOKBOOK';
            }

            const videoPlayer = document.getElementById('lookbook-video-player');
            const videoSource = document.getElementById('lookbook-video-source');
            if (videoPlayer && videoSource) {
                let vSrc = (lb && lb.video_url) ? resolveImageUrl(lb.video_url) : "{{ asset('video/editorial.mp4') }}";
                videoSource.src = vSrc;
                videoPlayer.load();
                videoPlayer.play().catch(() => {});
            }

            const grid = document.getElementById('lookbook-photos-grid');
            if (grid && lb && lb.fotoshoots) {
                grid.innerHTML = '';
                if (lb.fotoshoots.length > 0) {
                    lb.fotoshoots.forEach(f => {
                        const imgUrl = resolveImageUrl(f.image);
                        const escapedTitle = (f.title || '').replace(/'/g, "\\'");
                        grid.innerHTML += `
                            <div class="w-full group cursor-pointer" onclick="openLightbox('${imgUrl}', '${escapedTitle}')">
                                <div class="overflow-hidden bg-[#d9d9d9] relative">
                                    <img src="${imgUrl}" alt="${f.title || ''}"
                                        class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                    ${f.title ? `
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <span class="text-white font-space-grotesk tracking-[0.2em] uppercase text-sm md:text-base">${f.title}</span>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    });
                } else {
                    grid.innerHTML = '<div class="col-span-2 py-16 text-center text-gray-400 font-inter">No photos uploaded for this lookbook album yet.</div>';
                }
            }

            showPage('view-lookbook-detail', false, false);
            if (updateUrl && lb && lb.id) {
                history.pushState({ pageId: 'view-lookbook-detail', lookbookId: lb.id }, '', '/lookbook/' + lb.id);
                try {
                    sessionStorage.setItem('notisse_active_view', 'view-lookbook-detail');
                    sessionStorage.setItem('notisse_lookbook_id', lb.id);
                } catch (e) {}
            }
        }

        function showToast(message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'bg-black text-white px-5 py-3 rounded text-xs font-space-grotesk uppercase tracking-wider shadow-xl transition-all duration-300 opacity-0 transform translate-y-2';
            toast.innerText = message;
            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-y-2');
            }, 10);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function updateCarouselUI() {
            const slides = document.querySelectorAll('.hero-carousel-slide');
            slides.forEach((slide, index) => {
                if (index === currentSlideIndex) {
                    slide.classList.remove('opacity-0', 'pointer-events-none');
                    slide.classList.add('opacity-100');
                } else {
                    slide.classList.remove('opacity-100');
                    slide.classList.add('opacity-0', 'pointer-events-none');
                }
            });
        }

        function nextSlide() {
            const slides = document.querySelectorAll('.hero-carousel-slide');
            if (slides.length <= 1) return;
            currentSlideIndex = (currentSlideIndex + 1) % slides.length;
            updateCarouselUI();
        }

        function prevSlide() {
            const slides = document.querySelectorAll('.hero-carousel-slide');
            if (slides.length <= 1) return;
            currentSlideIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
            updateCarouselUI();
        }

        function goToSlide(index) {
            currentSlideIndex = index;
            updateCarouselUI();
        }

        function startAutoSlide() {
            stopAutoSlide();
            slideInterval = setInterval(nextSlide, 3500);
        }

        function stopAutoSlide() {
            if (slideInterval) clearInterval(slideInterval);
        }

        function updateProductDetailScroll() {
            const leftCol = document.getElementById('pdetail-left-col');
            if (!leftCol) return;
            const hasExpandedAccordion = document.querySelector('#pdetail-left-col .accordion-content.expanded') !== null;
            const isSizeDropdownActive = document.querySelector('#size-dropdown-menu.active') !== null;
            if (hasExpandedAccordion || isSizeDropdownActive) {
                leftCol.classList.remove('lg:overflow-y-hidden');
                leftCol.classList.add('lg:overflow-y-auto');
            } else {
                leftCol.classList.remove('lg:overflow-y-auto');
                leftCol.classList.add('lg:overflow-y-hidden');
                leftCol.scrollTop = 0;
            }
        }

        function toggleSizeDropdown(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('size-dropdown-menu');
            if (menu) {
                menu.classList.toggle('active');
                updateProductDetailScroll();

                if (menu.classList.contains('active')) {
                    const leftCol = document.getElementById('pdetail-left-col');
                    if (leftCol) {
                        setTimeout(() => {
                            leftCol.scrollBy({ top: 160, behavior: 'smooth' });
                        }, 50);
                    }
                }
            }
        }

        function closeSizeDropdown() {
            const menu = document.getElementById('size-dropdown-menu');
            if (menu && menu.classList.contains('active')) {
                menu.classList.remove('active');
                updateProductDetailScroll();
            }
        }

        function selectSize(code) {
            currentSelectedSize = code;
            document.getElementById('selected-size-label').innerText = "Select size: " + code;
            closeSizeDropdown();
        }

        function toggleAccordion(key) {
            const content = document.getElementById('acc-content-' + key);
            const icon = document.getElementById('acc-icon-' + key);
            if (content) content.classList.toggle('expanded');
            if (icon) icon.classList.toggle('rotated');
            updateProductDetailScroll();
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('custom-size-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                closeSizeDropdown();
            }
        });

        function formatRupiah(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID') + ',00';
        }

        let selectedShippingCost = 0;
        let selectedCourierName = "";
        let searchTimeout;

        function renderCartItems() {
            const container = document.getElementById('cart-items-container');
            const totalElement = document.getElementById('cart-total-price');
            const badgeElement = document.getElementById('header-cart-badge');
            const counterElement = document.getElementById('header-cart-counter');
            let totalAmount = 0;
            let totalItemCount = 0;

            if (!container) return;

            if (!cartState || cartState.length === 0) {
                container.innerHTML = `<div class="text-center py-16 text-gray-400 text-sm font-inter">Your cart is empty.</div>`;
            } else {
                let html = '';
                cartState.forEach((item, index) => {
                    const itemPrice = item.unitPrice || item.price || 0;
                    const itemTotal = itemPrice * item.qty;
                    totalAmount += itemTotal;
                    totalItemCount += item.qty;

                    let itemImg = resolveImageUrl(item.image, "{{ asset('footage-baju.jpg') }}");

                    html += `
                        <div class="grid grid-cols-12 items-center text-xs md:text-sm py-2 border-b border-gray-100 cart-animated-item" style="animation-delay: ${index * 70}ms">
                            <div class="col-span-6 md:col-span-5 flex items-center space-x-3">
                                <div class="w-12 h-16 bg-gray-100 flex-shrink-0 border border-gray-200 overflow-hidden">
                                    <img src="${itemImg}" alt="${item.name}" class="w-full h-full object-cover" onerror="this.src='{{ asset('footage-baju.jpg') }}'">
                                </div>
                                <div>
                                    <p class="font-semibold uppercase text-black text-xs md:text-sm font-space-grotesk">${item.name}</p>
                                    <p class="text-xs text-gray-500 font-inter">Size: ${item.size}</p>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-3 flex justify-center">
                                <div class="flex items-center border border-gray-400">
                                    <button onclick="updateCartQty(${index}, -1)" class="px-2 py-1 hover:bg-gray-100 font-bold font-inter cursor-pointer">−</button>
                                    <span class="px-3 py-1 font-semibold font-inter">${item.qty}</span>
                                    <button onclick="updateCartQty(${index}, 1)" class="px-2 py-1 hover:bg-gray-100 font-bold font-inter cursor-pointer">+</button>
                                </div>
                            </div>
                            <div class="hidden md:block md:col-span-2 text-right font-space-grotesk">${formatRupiah(itemPrice)}</div>
                            <div class="col-span-3 md:col-span-2 text-right font-medium font-space-grotesk">${formatRupiah(itemTotal)}</div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }

            const grandTotal = totalAmount + selectedShippingCost;
            if (totalElement) totalElement.innerText = formatRupiah(grandTotal) + ' IDR';
            if (badgeElement) badgeElement.innerText = totalItemCount;
            if (counterElement) counterElement.innerText = totalItemCount;

            saveCartState();
        }

        function triggerBadgePop() {
            const badge = document.getElementById('header-cart-badge');
            if (badge) {
                badge.classList.remove('cart-badge-pop');
                void badge.offsetWidth;
                badge.classList.add('cart-badge-pop');
            }
        }

        async function updateCartQty(index, change) {
            if (cartState[index]) {
                const item = cartState[index];
                const newQty = item.qty + change;
                if (newQty <= 0) {
                    await removeCartItem(index);
                } else {
                    try {
                        const response = await fetch(`/api/cart/${item.cart_item_id}`, {
                            method: 'PUT',
                            headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                            body: JSON.stringify({ quantity: newQty })
                        });
                        const data = await response.json();
                        if (data.success) {
                            cartState = data.items;
                            renderCartItems();
                            triggerBadgePop();
                        }
                    } catch (e) { console.error(e); }
                }
            }
        }

        async function removeCartItem(index) {
            if (cartState[index]) {
                const item = cartState[index];
                try {
                    const response = await fetch(`/api/cart/${item.cart_item_id}`, {
                        method: 'DELETE',
                        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}
                    });
                    const data = await response.json();
                    if (data.success) {
                        cartState = data.items;
                        renderCartItems();
                        triggerBadgePop();
                    }
                } catch (e) { console.error(e); }
            }
        }

        function addCrossSellItem() {
            showToast("Cross-sell item feature is temporarily unavailable");
        }

        async function addSelectedToCart() {
            if (!currentProduct) {
                currentProduct = productsData && productsData.length > 0 ? productsData[0] : null;
            }
            if (!currentProduct) return;
            if (!currentSelectedSize) {
                showToast('Please select a size!');
                return;
            }

            try {
                const response = await fetch('/api/cart', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                    body: JSON.stringify({
                        product_id: currentProduct.id,
                        size: currentSelectedSize,
                        quantity: 1
                    })
                });
                const data = await response.json();
                if (data.success) {
                    cartState = data.items;
                    renderCartItems();
                    triggerBadgePop();
                    openCart();
                    showToast('Added to cart!');
                } else {
                    showToast(data.message || 'Failed to add to cart');
                }
            } catch(e) {
                console.error(e);
                showToast('A system error occurred.');
            }
        }

        async function searchAreaBiteship(keyword) {
            const areaResults = document.getElementById('area-results');
            const areaIdHidden = document.getElementById('selected-area-id');
            const areaInput = document.getElementById('area-search');

            clearTimeout(searchTimeout);
            if(!keyword || keyword.length < 3) {
                if (areaResults) areaResults.classList.add('hidden');
                return;
            }
            searchTimeout = setTimeout(async () => {
                try {
                    const res = await fetch(`/shipping-areas?keyword=${encodeURIComponent(keyword)}`);
                    const data = await res.json();
                    
                    if (!areaResults) return;
                    areaResults.innerHTML = '';
                    if (data.areas && data.areas.length > 0) {
                        data.areas.forEach(area => {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100';
                            div.innerText = `${area.name} - ${area.administrative_division_level_2_name}`;
                            div.onclick = () => {
                                areaInput.value = `${area.name}, ${area.administrative_division_level_2_name}`;
                                areaIdHidden.value = area.id;
                                areaResults.classList.add('hidden');
                                fetchShippingRates(area.id);
                            };
                            areaResults.appendChild(div);
                        });
                        areaResults.classList.remove('hidden');
                    }
                } catch (e) {
                    console.error("Failed to fetch areas", e);
                }
            }, 500);
        }

        async function fetchShippingRates(areaId) {
            const shippingOptions = document.getElementById('shipping-options');
            const shippingContainer = document.getElementById('shipping-options-container');

            if (shippingOptions) shippingOptions.innerHTML = '<span class="text-gray-400">Calculating shipping rate...</span>';
            if (shippingContainer) shippingContainer.classList.remove('hidden');

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const items = cartState.map(item => ({
                id: item.id,
                name: item.name,
                price: item.unitPrice,
                quantity: item.qty
            }));

            try {
                const res = await fetch('/shipping-rates', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        destination_area_id: areaId,
                        items: items
                    })
                });

                const data = await res.json();
                if (shippingOptions) shippingOptions.innerHTML = '';
                
                if (data.pricing && data.pricing.length > 0) {
                    data.pricing.forEach(rate => {
                        const label = document.createElement('label');
                        label.className = 'flex items-center space-x-2 cursor-pointer';
                        label.innerHTML = `
                            <input type="radio" name="courier" value="${rate.price}" class="form-radio text-black" onchange="selectCourier('${rate.courier_name} ${rate.courier_service_name}', ${rate.price})">
                            <span>${rate.courier_name} ${rate.courier_service_name} - ${formatRupiah(rate.price)}</span>
                        `;
                        shippingOptions.appendChild(label);
                    });
                } else {
                    if (shippingOptions) shippingOptions.innerHTML = '<span class="text-red-500">Courier unavailable for this area.</span>';
                }
            } catch (e) {
                console.error("Failed to fetch shipping rate", e);
                if (shippingOptions) shippingOptions.innerHTML = '<span class="text-red-500">Connection error.</span>';
            }
        }

        window.selectCourier = function(name, cost) {
            selectedCourierName = name;
            selectedShippingCost = cost;
            renderCartItems(); // trigger recalc total in cart
        };

        function processCheckout() {
            if (cartState.length === 0) {
                showToast("Cart is empty!");
                return;
            }
            window.location.href = '/checkout';
        }

        // ====== BESPOKE NOTISSE DATE PICKER LOGIC ======
        let calCurrentYear = 2000;
        let calCurrentMonth = 0; // 0-11
        let calSelectedDate = null; // YYYY-MM-DD
        const calMonthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        function initNotisseDatePicker() {
            const yearSelect = document.getElementById('cal-year-select');
            if (!yearSelect) return;

            const currentYear = new Date().getFullYear();
            yearSelect.innerHTML = '';
            for (let y = currentYear; y >= 1930; y--) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                yearSelect.appendChild(opt);
            }

            // Sync with existing value if set
            const hiddenInput = document.getElementById('edit-birthdate');
            if (hiddenInput && hiddenInput.value) {
                setNotisseDatePickerValue(hiddenInput.value);
            } else {
                calCurrentYear = 2000;
                calCurrentMonth = 0;
                renderNotisseCalendarGrid();
            }

            // Close on click outside
            document.addEventListener('click', function(e) {
                const container = document.getElementById('notisse-datepicker-container');
                if (container && !container.contains(e.target)) {
                    closeNotisseDatePicker();
                }
            });
        }

        function toggleNotisseDatePicker(e) {
            if (e) e.stopPropagation();
            const dropdown = document.getElementById('notisse-calendar-dropdown');
            const chevron = document.getElementById('datepicker-chevron');
            if (!dropdown) return;
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
                renderNotisseCalendarGrid();
            } else {
                closeNotisseDatePicker();
            }
        }

        function closeNotisseDatePicker() {
            const dropdown = document.getElementById('notisse-calendar-dropdown');
            const chevron = document.getElementById('datepicker-chevron');
            if (dropdown) dropdown.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }

        function setNotisseDatePickerValue(dateStr) {
            const hiddenInput = document.getElementById('edit-birthdate');
            const displayText = document.getElementById('datepicker-display-text');
            const clearBtn = document.getElementById('datepicker-clear-btn');

            if (!dateStr) {
                if (hiddenInput) hiddenInput.value = '';
                if (displayText) {
                    displayText.innerText = 'Select birth date (DD / MM / YYYY)';
                    displayText.classList.remove('text-black', 'font-medium');
                    displayText.classList.add('text-gray-400');
                }
                if (clearBtn) clearBtn.classList.add('hidden');
                calSelectedDate = null;
                calCurrentYear = 2000;
                calCurrentMonth = 0;
            } else {
                if (hiddenInput) hiddenInput.value = dateStr;
                calSelectedDate = dateStr;
                const parts = dateStr.split('-');
                if (parts.length === 3) {
                    const y = parseInt(parts[0]);
                    const m = parseInt(parts[1]) - 1;
                    const d = parseInt(parts[2]);
                    calCurrentYear = y;
                    calCurrentMonth = m;

                    if (displayText) {
                        displayText.innerText = `${d} ${calMonthNames[m]} ${y}`;
                        displayText.classList.remove('text-gray-400');
                        displayText.classList.add('text-black', 'font-medium');
                    }
                    if (clearBtn) clearBtn.classList.remove('hidden');
                }
            }
            renderNotisseCalendarGrid();
        }

        function clearNotisseDatePicker(e) {
            if (e) e.stopPropagation();
            setNotisseDatePickerValue('');
        }

        function changeNotisseMonth(delta, e) {
            if (e) e.stopPropagation();
            calCurrentMonth += delta;
            if (calCurrentMonth < 0) {
                calCurrentMonth = 11;
                calCurrentYear--;
            } else if (calCurrentMonth > 11) {
                calCurrentMonth = 0;
                calCurrentYear++;
            }
            renderNotisseCalendarGrid();
        }

        function onMonthSelectChange(val) {
            calCurrentMonth = parseInt(val);
            renderNotisseCalendarGrid();
        }

        function onYearSelectChange(val) {
            calCurrentYear = parseInt(val);
            renderNotisseCalendarGrid();
        }

        function renderNotisseCalendarGrid() {
            const monthSelect = document.getElementById('cal-month-select');
            const yearSelect = document.getElementById('cal-year-select');
            const daysGrid = document.getElementById('cal-days-grid');
            if (!daysGrid) return;

            if (monthSelect) monthSelect.value = calCurrentMonth;
            if (yearSelect) yearSelect.value = calCurrentYear;

            daysGrid.innerHTML = '';

            const firstDayIndex = new Date(calCurrentYear, calCurrentMonth, 1).getDay(); // 0 is Sunday
            const totalDaysInMonth = new Date(calCurrentYear, calCurrentMonth + 1, 0).getDate();
            const prevMonthDays = new Date(calCurrentYear, calCurrentMonth, 0).getDate();

            // Previous month overflow days
            for (let i = firstDayIndex - 1; i >= 0; i--) {
                const dayNum = prevMonthDays - i;
                const cell = document.createElement('div');
                cell.className = 'w-8 h-8 mx-auto flex items-center justify-center text-gray-300 text-[11px] pointer-events-none select-none';
                cell.textContent = dayNum;
                daysGrid.appendChild(cell);
            }

            // Current month days
            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

            for (let day = 1; day <= totalDaysInMonth; day++) {
                const dateString = `${calCurrentYear}-${String(calCurrentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isSelected = (calSelectedDate === dateString);
                const isToday = (todayStr === dateString);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = day;

                let classes = 'w-8 h-8 mx-auto flex items-center justify-center text-xs transition-colors cursor-pointer ';
                if (isSelected) {
                    classes += 'bg-black text-white font-bold ring-2 ring-black';
                } else if (isToday) {
                    classes += 'border border-black font-bold text-black hover:bg-black hover:text-white';
                } else {
                    classes += 'text-black hover:bg-neutral-100 font-medium';
                }

                btn.className = classes;
                btn.onclick = (e) => {
                    e.stopPropagation();
                    selectNotisseDate(dateString);
                };
                daysGrid.appendChild(btn);
            }

            // Next month overflow padding (to complete 35 or 42 grid cells)
            const filledCells = firstDayIndex + totalDaysInMonth;
            const remaining = (filledCells % 7 === 0) ? 0 : 7 - (filledCells % 7);
            for (let j = 1; j <= remaining; j++) {
                const cell = document.createElement('div');
                cell.className = 'w-8 h-8 mx-auto flex items-center justify-center text-gray-300 text-[11px] pointer-events-none select-none';
                cell.textContent = j;
                daysGrid.appendChild(cell);
            }
        }

        function selectNotisseDate(dateString) {
            setNotisseDatePickerValue(dateString);
            closeNotisseDatePicker();
        }

        document.addEventListener('DOMContentLoaded', initNotisseDatePicker);

        // ====== EDIT PROFILE LOGIC ======
        let editAreaSearchTimeout = null;
        const editAreaSearchInput = document.getElementById('edit-area-search');
        const editAreaResultsBox = document.getElementById('edit-area-results');

        function showEditProfile() {
            if (!loggedInUser) return;
            document.getElementById('edit-name').value = loggedInUser.name || '';
            document.getElementById('edit-phone').value = loggedInUser.phone || '';
            setNotisseDatePickerValue(loggedInUser.birthdate || '');
            document.getElementById('edit-address').value = loggedInUser.address || '';
            document.getElementById('edit-area-id').value = loggedInUser.area_id || '';
            
            document.getElementById('edit-profile-error').classList.add('hidden');
            document.getElementById('edit-profile-success').classList.add('hidden');
            
            showPage('view-edit-profile');
        }

        if (editAreaSearchInput) {
            editAreaSearchInput.addEventListener('input', function(e) {
                clearTimeout(editAreaSearchTimeout);
                const keyword = e.target.value;
                
                if (keyword.length < 3) {
                    editAreaResultsBox.classList.add('hidden');
                    return;
                }

                editAreaSearchTimeout = setTimeout(() => {
                    fetch(`/shipping-areas?keyword=${keyword}`)
                        .then(res => res.json())
                        .then(data => {
                            editAreaResultsBox.innerHTML = '';
                            if (data.areas && data.areas.length > 0) {
                                data.areas.forEach(area => {
                                    const li = document.createElement('li');
                                    li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm font-inter border-b border-gray-100';
                                    li.innerText = area.name;
                                    li.onclick = () => {
                                        editAreaSearchInput.value = area.name;
                                        document.getElementById('edit-area-id').value = area.id;
                                        editAreaResultsBox.classList.add('hidden');
                                    };
                                    editAreaResultsBox.appendChild(li);
                                });
                                editAreaResultsBox.classList.remove('hidden');
                            } else {
                                editAreaResultsBox.classList.add('hidden');
                            }
                        });
                }, 500);
            });

            document.addEventListener('click', function(e) {
                if (!editAreaSearchInput.contains(e.target) && !editAreaResultsBox.contains(e.target)) {
                    editAreaResultsBox.classList.add('hidden');
                }
            });
        }

        async function handleEditProfileSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('edit-profile-btn');
            const errorBox = document.getElementById('edit-profile-error');
            const successBox = document.getElementById('edit-profile-success');
            const originalText = btn.innerText;

            errorBox.classList.add('hidden');
            successBox.classList.add('hidden');

            const formData = new FormData();
            formData.append('name', document.getElementById('edit-name').value);
            formData.append('phone', document.getElementById('edit-phone').value);
            formData.append('birthdate', document.getElementById('edit-birthdate').value);
            formData.append('address', document.getElementById('edit-address').value);
            formData.append('area_id', document.getElementById('edit-area-id').value);

            const avatarFile = document.getElementById('edit-avatar').files[0];
            if (avatarFile) {
                formData.append('avatar', avatarFile);
            }

            if (!formData.get('area_id')) {
                errorBox.innerText = 'Please select a district/city from the search list.';
                errorBox.classList.remove('hidden');
                return;
            }

            btn.innerText = 'SAVING...';
            btn.disabled = true;

            try {
                const response = await fetch('/update-profile', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                if (response.ok && data.success) {
                    successBox.innerText = 'Profile updated successfully.';
                    successBox.classList.remove('hidden');
                    
                    // Update frontend state
                    loggedInUser.name = formData.get('name');
                    loggedInUser.phone = formData.get('phone');
                    loggedInUser.birthdate = data.user?.birthdate || formData.get('birthdate');
                    loggedInUser.address = formData.get('address');
                    loggedInUser.area_id = formData.get('area_id');
                    
                    localStorage.setItem('notisse_user', JSON.stringify(loggedInUser));
                    document.getElementById('user-display-name').innerText = loggedInUser.name;
                    document.getElementById('user-display-address').innerText = loggedInUser.address;
                    const birthEl = document.getElementById('user-display-birthdate');
                    if (birthEl && loggedInUser.birthdate) {
                        birthEl.innerText = 'Date of Birth: ' + loggedInUser.birthdate;
                    }
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);
                } else {
                    errorBox.innerText = data.message || 'Failed to save changes.';
                    errorBox.classList.remove('hidden');
                }
            } catch (err) {
                errorBox.innerText = 'A network error occurred.';
                errorBox.classList.remove('hidden');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        // ─── USER ADDRESS BOOK SYSTEM (MAX 3 ADDRESSES) ───
        let userSavedAddresses = @json($addresses ?? []);

        function openAddressModal(addressData = null) {
            const modal = document.getElementById('address-modal');
            const titleEl = document.getElementById('address-modal-title');
            const errorBox = document.getElementById('address-modal-error');
            const submitBtnText = document.getElementById('modal-submit-btn-text');

            if (!modal) return;
            errorBox.classList.add('hidden');
            errorBox.innerText = '';

            if (addressData) {
                // Edit mode
                titleEl.innerText = 'Edit Address';
                submitBtnText.innerText = 'SIMPAN PERUBAHAN';
                document.getElementById('modal-address-id').value = addressData.id;
                document.getElementById('modal-address-label').value = addressData.label || '';
                document.getElementById('modal-recipient-name').value = addressData.recipient_name || '';
                document.getElementById('modal-phone').value = addressData.phone || '';
                document.getElementById('modal-address-detail').value = addressData.address || '';
                document.getElementById('modal-area-id').value = addressData.area_id || '';
                document.getElementById('modal-area-name').value = addressData.area_name || '';
                document.getElementById('modal-area-search').value = addressData.area_name || '';
                document.getElementById('modal-is-primary').checked = !!addressData.is_primary;

                if (addressData.area_id) {
                    const conf = document.getElementById('modal-confirmed-area');
                    const confText = document.getElementById('modal-confirmed-area-text');
                    if (conf && confText) {
                        confText.innerText = addressData.area_name || addressData.area_id;
                        conf.classList.remove('hidden');
                    }
                }
            } else {
                // Add mode
                titleEl.innerText = 'Add New Address';
                submitBtnText.innerText = 'SIMPAN ALAMAT';
                document.getElementById('modal-address-id').value = '';
                document.getElementById('modal-address-label').value = 'Rumah';
                document.getElementById('modal-recipient-name').value = (typeof loggedInUser !== 'undefined' && loggedInUser?.name) ? loggedInUser.name : '';
                document.getElementById('modal-phone').value = (typeof loggedInUser !== 'undefined' && loggedInUser?.phone) ? loggedInUser.phone : '';
                document.getElementById('modal-address-detail').value = '';
                document.getElementById('modal-area-id').value = '';
                document.getElementById('modal-area-name').value = '';
                document.getElementById('modal-area-search').value = '';
                document.getElementById('modal-is-primary').checked = (userSavedAddresses.length === 0);

                const conf = document.getElementById('modal-confirmed-area');
                if (conf) conf.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setupModalAreaSearch();
        }

        function closeAddressModal() {
            const modal = document.getElementById('address-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function setAddressLabelPreset(label) {
            const input = document.getElementById('modal-address-label');
            if (input) input.value = label;
        }

        let modalAreaTimeout = null;
        function setupModalAreaSearch() {
            const searchInput = document.getElementById('modal-area-search');
            const resultsBox = document.getElementById('modal-area-results');
            const spinner = document.getElementById('modal-area-spinner');
            const areaIdInput = document.getElementById('modal-area-id');
            const areaNameInput = document.getElementById('modal-area-name');
            const confBox = document.getElementById('modal-confirmed-area');
            const confText = document.getElementById('modal-confirmed-area-text');

            if (!searchInput || searchInput.dataset.listenerAttached) return;
            searchInput.dataset.listenerAttached = 'true';

            searchInput.addEventListener('input', function() {
                const keyword = this.value.trim();
                clearTimeout(modalAreaTimeout);

                if (keyword.length < 3) {
                    resultsBox.classList.add('hidden');
                    return;
                }

                if (spinner) spinner.classList.remove('hidden');

                modalAreaTimeout = setTimeout(async () => {
                    try {
                        const res = await fetch(`/shipping-areas?keyword=${encodeURIComponent(keyword)}`);
                        const data = await res.json();
                        if (spinner) spinner.classList.add('hidden');

                        resultsBox.innerHTML = '';
                        if (data.areas && data.areas.length > 0) {
                            data.areas.forEach(area => {
                                const li = document.createElement('li');
                                li.className = 'px-3 py-2 hover:bg-neutral-100 cursor-pointer text-xs font-inter border-b border-gray-100 flex items-center justify-between';
                                li.innerHTML = `<span class="truncate">${area.name}</span><span class="text-[9px] font-space-grotesk font-bold uppercase text-gray-400 shrink-0 ml-2">Pilih</span>`;
                                li.onclick = () => {
                                    searchInput.value = area.name;
                                    areaIdInput.value = area.id;
                                    areaNameInput.value = area.name;
                                    if (confBox && confText) {
                                        confText.innerText = area.name;
                                        confBox.classList.remove('hidden');
                                    }
                                    resultsBox.classList.add('hidden');
                                };
                                resultsBox.appendChild(li);
                            });
                            resultsBox.classList.remove('hidden');
                        } else {
                            resultsBox.innerHTML = '<li class="px-3 py-2 text-gray-400 text-xs font-inter italic">Kecamatan tidak ditemukan.</li>';
                            resultsBox.classList.remove('hidden');
                        }
                    } catch (err) {
                        if (spinner) spinner.classList.add('hidden');
                        console.error('Error fetching areas:', err);
                    }
                }, 400);
            });

            document.addEventListener('click', function(e) {
                if (resultsBox && !searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                    resultsBox.classList.add('hidden');
                }
            });
        }

        async function handleAddressFormSubmit(e) {
            e.preventDefault();
            const errorBox = document.getElementById('address-modal-error');
            const submitBtn = document.getElementById('modal-submit-btn');
            const originalBtnText = document.getElementById('modal-submit-btn-text').innerText;

            errorBox.classList.add('hidden');
            errorBox.innerText = '';

            const id = document.getElementById('modal-address-id').value;
            const areaId = document.getElementById('modal-area-id').value;

            if (!areaId) {
                errorBox.innerText = 'Silakan pilih kecamatan/kota dari daftar pencarian.';
                errorBox.classList.remove('hidden');
                return;
            }

            const payload = {
                label: document.getElementById('modal-address-label').value.trim(),
                recipient_name: document.getElementById('modal-recipient-name').value.trim(),
                phone: document.getElementById('modal-phone').value.trim(),
                address: document.getElementById('modal-address-detail').value.trim(),
                area_id: areaId,
                area_name: document.getElementById('modal-area-name').value.trim() || document.getElementById('modal-area-search').value.trim(),
                is_primary: document.getElementById('modal-is-primary').checked
            };

            submitBtn.disabled = true;
            document.getElementById('modal-submit-btn-text').innerText = 'MENYIMPAN...';

            try {
                const url = id ? `/api/user/addresses/${id}` : '/api/user/addresses';
                const method = id ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    userSavedAddresses = data.addresses || [];
                    renderSavedAddresses(userSavedAddresses);
                    closeAddressModal();
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Alamat berhasil disimpan.');
                    }
                } else {
                    errorBox.innerText = data.message || 'Gagal menyimpan alamat.';
                    errorBox.classList.remove('hidden');
                }
            } catch (err) {
                errorBox.innerText = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                errorBox.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                document.getElementById('modal-submit-btn-text').innerText = originalBtnText;
            }
        }

        async function editAddressModal(id) {
            const addr = userSavedAddresses.find(a => a.id === id);
            if (addr) {
                openAddressModal(addr);
            } else {
                try {
                    const res = await fetch('/api/user/addresses');
                    const data = await res.json();
                    if (data.addresses) {
                        userSavedAddresses = data.addresses;
                        const found = userSavedAddresses.find(a => a.id === id);
                        if (found) openAddressModal(found);
                    }
                } catch(e) {
                    console.error(e);
                }
            }
        }

        async function setPrimaryAddress(id) {
            try {
                const response = await fetch(`/api/user/addresses/${id}/primary`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    userSavedAddresses = data.addresses || [];
                    renderSavedAddresses(userSavedAddresses);
                    if (typeof showToast === 'function') {
                        showToast('Alamat utama berhasil diperbarui.');
                    }
                }
            } catch(e) {
                console.error(e);
            }
        }

        async function deleteAddressConfirm(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus alamat ini?')) return;

            try {
                const response = await fetch(`/api/user/addresses/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    userSavedAddresses = data.addresses || [];
                    renderSavedAddresses(userSavedAddresses);
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Alamat berhasil dihapus.');
                    }
                } else {
                    alert(data.message || 'Gagal menghapus alamat.');
                }
            } catch(e) {
                console.error(e);
            }
        }

        function renderSavedAddresses(addresses) {
            const container = document.getElementById('saved-addresses-grid');
            const badge = document.getElementById('address-counter-badge');
            const addBtnTrigger = document.getElementById('btn-add-address-trigger');

            if (!container) return;

            const count = addresses ? addresses.length : 0;
            if (badge) badge.innerText = `${count} / 3 ALAMAT`;

            if (addBtnTrigger) {
                if (count >= 3) {
                    addBtnTrigger.classList.add('hidden');
                } else {
                    addBtnTrigger.classList.remove('hidden');
                }
            }

            if (!addresses || addresses.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full border border-gray-200 p-8 text-center bg-gray-50 space-y-3">
                        <p class="text-gray-400 font-inter text-sm">Belum ada alamat pengiriman yang tersimpan.</p>
                        <button type="button" onclick="openAddressModal()" class="px-4 py-2 bg-black text-white text-xs font-bold font-space-grotesk uppercase tracking-wider cursor-pointer">
                            + Tambah Alamat Sekarang
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = addresses.map(addr => `
                <div class="border ${addr.is_primary ? 'border-black ring-1 ring-black' : 'border-gray-200'} bg-white p-5 flex flex-col justify-between space-y-4 hover:shadow-sm transition-all relative">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] font-space-grotesk font-bold uppercase tracking-widest px-2.5 py-0.5 bg-neutral-100 text-neutral-800 border border-gray-200">
                                ${addr.label}
                            </span>
                            ${addr.is_primary ? `
                                <span class="text-[9px] font-space-grotesk font-bold uppercase tracking-widest px-2 py-0.5 bg-black text-white">
                                    PRIMARY
                                </span>
                            ` : ''}
                        </div>
                        <div>
                            <h3 class="font-bold text-black text-sm font-space-grotesk uppercase">${addr.recipient_name}</h3>
                            <p class="text-xs text-gray-500 font-inter mt-0.5">${addr.phone}</p>
                        </div>
                        <div class="text-xs font-inter text-gray-700 leading-relaxed border-t border-gray-100 pt-2.5">
                            <p class="line-clamp-3">${addr.address}</p>
                            <div class="mt-2 text-[11px] text-gray-400 font-inter">
                                <span class="font-semibold text-gray-600">Wilayah:</span> ${addr.area_name || addr.area_id}
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-3 flex items-center justify-between text-xs font-space-grotesk">
                        ${!addr.is_primary ? `
                            <button type="button" onclick="setPrimaryAddress(${addr.id})" class="text-[10px] font-bold uppercase tracking-wider text-neutral-600 hover:text-black underline cursor-pointer">
                                Set as Primary
                            </button>
                        ` : `
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Default</span>
                        `}
                        <div class="flex items-center space-x-3">
                            <button type="button" onclick="editAddressModal(${addr.id})" class="text-[10px] font-bold uppercase tracking-wider text-black hover:underline cursor-pointer">
                                Edit
                            </button>
                            <button type="button" onclick="deleteAddressConfirm(${addr.id})" class="text-[10px] font-bold uppercase tracking-wider text-red-500 hover:text-red-700 underline cursor-pointer">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Initial Cart & URL Trigger Setup
        document.addEventListener('DOMContentLoaded', () => {
            renderCartItems();

            const initialView = @json($activeView ?? null);
            const initialProductId = @json($initialProductId ?? null);
            const initialArtistId = @json($initialArtistId ?? null);
            const initialArtworkId = @json($initialArtworkId ?? null);
            const initialEditorialId = @json($initialEditorialId ?? null);
            const initialLookbookId = @json($initialLookbookId ?? null);

            if (initialProductId) {
                openProductDetail(initialProductId, null, null, false);
            } else if (initialArtistId) {
                openArtistProfile(initialArtistId, null, false);
            } else if (initialArtworkId) {
                openArtistArtworkDetailById(initialArtworkId, false);
            } else if (initialEditorialId) {
                openArtistEditorialDetailById(initialEditorialId, false);
            } else if (initialLookbookId) {
                openLookbookDetail(initialLookbookId, false);
            } else if (initialView) {
                showPage(initialView, false, false);
                if (initialView === 'view-account-profile') {
                    const hash = window.location.hash.replace('#', '');
                    const savedTab = sessionStorage.getItem('notisse_active_acc_tab');
                    const tabToOpen = hash || savedTab || 'orders';
                    switchAccountTab(tabToOpen);
                }
            } else {
                // Fallback to sessionStorage if loaded on root / but user previously viewed another page
                const savedView = sessionStorage.getItem('notisse_active_view');
                if (savedView && document.getElementById(savedView)) {
                    showPage(savedView, false, false);
                    if (savedView === 'view-account-profile') {
                        const savedTab = sessionStorage.getItem('notisse_active_acc_tab');
                        if (savedTab) switchAccountTab(savedTab);
                    }
                }
            }

            if (typeof startAutoSlide === 'function') {
                startAutoSlide();
            }

            const urlParams = new URLSearchParams(window.location.search);
            const shouldOpenCart = urlParams.get('open_checkout') === 'true' || urlParams.get('cart') === 'true' || window.location.hash === '#cart' || sessionStorage.getItem('notisse_cart_open') === 'true';

            if (shouldOpenCart) {
                setTimeout(() => {
                    openCart(false);
                    if (loggedInUser && loggedInUser.area_id && cartState.length > 0) {
                        fetchShippingRates(loggedInUser.area_id);
                    }
                }, 200);
            }
        });
    </script>
