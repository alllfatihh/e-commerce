    <!-- OVERLAY 3: MY CART SLIDE-OVER DRAWER -->
    <div id="overlay-cart"
        class="fixed inset-0 bg-black/40 z-[60] hidden opacity-0 overlay-backdrop transition-opacity duration-400"
        onclick="closeCart()">
        <div id="cart-drawer-content"
            class="absolute right-0 top-0 bottom-0 w-full max-w-4xl bg-white p-6 md:p-10 transform translate-x-full overlay-slide flex flex-col overflow-y-auto shadow-2xl"
            onclick="event.stopPropagation()">
            <div class="flex justify-between items-center border-b border-gray-200 pb-3 mb-6">
                <h2 class="text-2xl md:text-3xl font-semibold tracking-tight font-space-grotesk">My cart</h2>
                <button id="cart-close" aria-label="Close Cart"
                    class="text-3xl font-light hover:rotate-90 transition-transform duration-300 focus:outline-none cursor-pointer">
                    &#10005;
                </button>
            </div>

            <div
                class="grid grid-cols-12 text-xs text-gray-500 font-regular pb-2 border-b border-gray-200 mb-6 font-space-grotesk">
                <div class="col-span-6 md:col-span-5">Product</div>
                <div class="col-span-3 md:col-span-3 text-center">Quantity</div>
                <div class="hidden md:block md:col-span-2 text-right">Price</div>
                <div class="col-span-3 md:col-span-2 text-right">Total</div>
            </div>

            <div id="cart-items-container" class="space-y-6 flex-grow"></div>

            <div class="mt-6 pt-6 border-t border-gray-200 flex flex-col items-end space-y-1">
                <div class="flex items-baseline space-x-6">
                    <span class="text-base md:text-lg font-medium text-gray-900 font-space-grotesk">Subtotal</span>
                    <span id="cart-total-price" class="text-xl md:text-xl font-regular font-space-grotesk">Rp 0,00 IDR</span>
                </div>
                <p class="text-xs text-gray-400 font-space-grotesk">Shipping will be calculated at checkout.</p>
                <div class="pt-4 w-full text-right">
                    <button onclick="window.location.href='/auth/checkout'"
                        class="btn-brush text-sm md:text-base cursor-pointer font-space-grotesk" id="checkout-btn">
                        CHECKOUT
                    </button>
                </div>
            </div>
        </div>
    </div>

