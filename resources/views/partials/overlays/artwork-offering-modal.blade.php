    <!-- OVERLAY 4: ARTWORK OFFERING MODAL (ACQUISITION INQUIRY) -->
    <div id="modal-artwork-offering"
        class="fixed inset-0 bg-black/40 backdrop-blur-[1px] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4 sm:p-6"
        onclick="closeArtworkOfferingModal()">
        <div class="bg-white w-full max-w-lg shadow-2xl border border-gray-200 transform scale-95 transition-transform duration-300 flex flex-col max-h-[92vh] overflow-hidden"
            onclick="event.stopPropagation()">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-white sticky top-0 z-10">
                <div>
                    <span class="text-[10px] font-bold tracking-[0.25em] uppercase text-gray-400 font-space-grotesk block">NOTISSE</span>
                    <h3 class="text-base sm:text-lg font-bold font-space-grotesk text-black tracking-tight">Artwork Offering / Buy Inquiry</h3>
                </div>
                <button onclick="closeArtworkOfferingModal()" aria-label="Close Modal"
                    class="text-2xl font-light hover:rotate-90 text-gray-500 hover:text-black transition-transform duration-300 focus:outline-none cursor-pointer">
                    &#10005;
                </button>
            </div>

            <!-- Modal Form Body (Scrollable) -->
            <div id="artwork-offering-form-body" class="p-6 overflow-y-auto space-y-5 flex-grow">
                <!-- Selected Artwork Card Preview -->
                <div class="flex items-center space-x-4 p-3.5 bg-neutral-50 border border-gray-200 rounded-sm">
                    <img id="offering-artwork-img" src="https://placehold.co/400x500/222/fff?text=Artwork" alt="Artwork"
                        class="w-16 h-20 object-cover bg-gray-200 border border-gray-300 flex-shrink-0">
                    <div class="space-y-0.5 min-w-0">
                        <span class="text-[9px] font-semibold tracking-widest uppercase bg-black text-white px-1.5 py-0.5 inline-block font-space-grotesk mb-1">Original Piece</span>
                        <p id="offering-artwork-title" class="font-bold text-xs sm:text-sm text-black font-space-grotesk truncate">Mereka Ulang Ungkapan Indah Leila</p>
                        <p class="text-xs text-gray-600 font-inter">Artist: <span id="offering-artwork-artist" class="font-semibold text-black">Mustafa Alatas</span></p>
                        <p id="offering-artwork-specs" class="text-[11px] text-gray-500 font-inter">Oil on canvas &bull; 60x60cm</p>
                    </div>
                </div>

                <!-- Offering Form -->
                <form id="artwork-offering-form" onsubmit="handleArtworkOfferingSubmit(event)" class="space-y-4">
                    <!-- Account Identity Card (Logged in User) -->
                    <div class="p-3.5 bg-neutral-50 border border-gray-200 rounded-sm">
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-400 font-space-grotesk block mb-1.5">Collector Account (Registered in System)</span>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center font-bold text-xs font-space-grotesk flex-shrink-0" id="offering-user-avatar">
                                    JD
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-xs sm:text-sm text-black font-space-grotesk truncate" id="offering-user-name">John Doe</p>
                                    <p class="text-[11px] text-gray-500 font-inter truncate" id="offering-user-email">john.doe@notisse.com</p>
                                </div>
                            </div>
                            <span class="text-[9px] font-semibold tracking-wider uppercase text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded font-space-grotesk flex-shrink-0">
                                Verified Account
                            </span>
                        </div>
                    </div>

                    <!-- Acquisition Price Display -->
                    <div class="p-3.5 bg-neutral-50 border border-gray-200 rounded-sm">
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-400 font-space-grotesk block mb-1">Artwork Price</span>
                        <p id="offering-fixed-price-display" class="font-space-grotesk font-bold text-lg text-black">Rp 7.500.000</p>
                        <input type="hidden" id="offering-buyer-price" value="">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold tracking-wider uppercase font-space-grotesk text-black mb-1">
                            Acquisition Notes (Optional)
                        </label>
                        <textarea id="offering-buyer-message" rows="3" placeholder="Write additional notes or placement intent for our curatorial team and artist..."
                            class="w-full border border-gray-300 px-3.5 py-2 text-sm font-inter focus:outline-none focus:border-black transition-colors resize-none"></textarea>
                    </div>

                    <div class="pt-2 flex justify-center">
                        <button type="submit" id="offering-submit-btn"
                            class="btn-brush w-full text-xs sm:text-sm font-bold tracking-[0.15em] uppercase cursor-pointer font-space-grotesk flex items-center justify-center space-x-2">
                            <span>CONFIRM ARTWORK ACQUISITION</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </form>

                <p class="text-[11px] text-gray-400 text-center font-inter">
                    Inquiry is recorded in your account registry and forwarded to Notisse curators and the artist.
                </p>
            </div>

            <!-- Success State Body (Official In-App Confirmation) -->
            <div id="artwork-offering-success-body" class="p-6 sm:p-8 hidden text-center space-y-5">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
                    &#10003;
                </div>
                <div>
                    <h4 class="text-lg sm:text-xl font-bold font-space-grotesk text-black">Inquiry Successfully Submitted!</h4>
                    <p class="text-xs text-gray-600 font-inter mt-2 max-w-sm mx-auto leading-relaxed">
                        Your inquiry is officially registered in Notisse system. The curatorial team and artist (<strong class="text-black" id="success-artist-name">Mustafa Alatas</strong>) will review your acquisition request.
                    </p>
                </div>

                <div class="p-4 bg-neutral-50 border border-gray-200 text-left space-y-2 text-xs font-inter">
                    <div class="flex justify-between border-b border-gray-200 pb-1.5">
                        <span class="text-gray-500 font-space-grotesk text-[11px] uppercase">Inquiry Ref:</span>
                        <span class="font-mono font-bold text-black" id="success-offering-ref">#NTS-ART-9281</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Collector:</span>
                        <span class="font-medium text-black" id="success-buyer-name">John Doe (john.doe@notisse.com)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Artwork:</span>
                        <span class="font-medium text-black truncate ml-2" id="success-artwork-title">Mereka Ulang Ungkapan Indah Leila</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Acquisition Value:</span>
                        <span class="font-bold text-emerald-700 font-space-grotesk" id="success-artwork-price">Rp 12.500.000 IDR</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-gray-500">Status:</span>
                        <span class="font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded text-[10px] uppercase font-space-grotesk">Under Curatorial Review</span>
                    </div>
                </div>

                <p class="text-[11px] text-gray-400 font-inter leading-relaxed">
                    Curatorial review outcome and instructions will be notified directly to your account and email.
                </p>

                <div class="pt-2 flex justify-center">
                    <button type="button" onclick="closeArtworkOfferingModal()"
                        class="btn-brush w-full text-xs font-bold tracking-wider uppercase cursor-pointer font-space-grotesk">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>

