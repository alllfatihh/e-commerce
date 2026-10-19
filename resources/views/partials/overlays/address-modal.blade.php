<!-- LUXURY NOTISSE ADDRESS MODAL (MAX 3 ADDRESSES) -->
<div id="address-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-sm transition-all duration-300">
    <div class="relative w-full max-w-lg bg-white border border-black shadow-2xl p-6 sm:p-8 space-y-6 max-h-[92vh] overflow-y-auto">
        <!-- Close Button -->
        <button type="button" onclick="closeAddressModal()" class="absolute top-5 right-5 text-gray-400 hover:text-black transition-colors focus:outline-none cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Header -->
        <div class="border-b border-gray-100 pb-4">
            <span class="text-[10px] font-space-grotesk font-bold tracking-widest uppercase bg-neutral-100 px-2 py-0.5 text-neutral-600 inline-block mb-1.5">Shipping Profile</span>
            <h2 id="address-modal-title" class="text-lg sm:text-xl font-bold font-space-grotesk uppercase tracking-tight text-black">Add New Address</h2>
            <p class="text-xs text-gray-500 font-inter mt-1">Simpan hingga maksimal 3 alamat pengiriman untuk kemudahan checkout.</p>
        </div>

        <!-- Error / Info Notice -->
        <div id="address-modal-error" class="hidden bg-red-50 border border-red-200 text-red-700 text-xs font-inter p-3"></div>

        <!-- Form -->
        <form id="address-form" onsubmit="handleAddressFormSubmit(event)" class="space-y-4">
            <input type="hidden" id="modal-address-id" value="">

            <!-- Label & Preset Selector -->
            <div>
                <label class="block font-space-grotesk text-[10px] font-bold uppercase tracking-wider text-gray-700 mb-1.5">Label Alamat *</label>
                <div class="flex items-center gap-2 mb-2">
                    <button type="button" onclick="setAddressLabelPreset('Rumah')" class="px-2.5 py-1 text-[11px] font-space-grotesk font-semibold uppercase border border-gray-300 hover:border-black transition-colors cursor-pointer bg-neutral-50 hover:bg-neutral-100">Rumah</button>
                    <button type="button" onclick="setAddressLabelPreset('Kantor')" class="px-2.5 py-1 text-[11px] font-space-grotesk font-semibold uppercase border border-gray-300 hover:border-black transition-colors cursor-pointer bg-neutral-50 hover:bg-neutral-100">Kantor</button>
                    <button type="button" onclick="setAddressLabelPreset('Apartemen')" class="px-2.5 py-1 text-[11px] font-space-grotesk font-semibold uppercase border border-gray-300 hover:border-black transition-colors cursor-pointer bg-neutral-50 hover:bg-neutral-100">Apartemen</button>
                </div>
                <input type="text" id="modal-address-label" required placeholder="Contoh: Rumah, Kantor, Kosan" class="w-full border border-gray-300 p-2.5 text-xs font-inter focus:outline-none focus:border-black bg-white transition-colors">
            </div>

            <!-- Recipient Name & Phone -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-space-grotesk text-[10px] font-bold uppercase tracking-wider text-gray-700 mb-1">Nama Penerima *</label>
                    <input type="text" id="modal-recipient-name" required placeholder="Nama lengkap penerima" class="w-full border border-gray-300 p-2.5 text-xs font-inter focus:outline-none focus:border-black bg-white transition-colors">
                </div>
                <div>
                    <label class="block font-space-grotesk text-[10px] font-bold uppercase tracking-wider text-gray-700 mb-1">Nomor Telepon / WA *</label>
                    <input type="text" id="modal-phone" required placeholder="08xxxxxxxxxx" class="w-full border border-gray-300 p-2.5 text-xs font-inter focus:outline-none focus:border-black bg-white transition-colors">
                </div>
            </div>

            <!-- Biteship Destination District Search -->
            <div class="relative">
                <label class="block font-space-grotesk text-[10px] font-bold uppercase tracking-wider text-gray-700 mb-1">Kecamatan / Kota (Biteship Live Rates) *</label>
                <div class="relative">
                    <input type="text" id="modal-area-search" autocomplete="off" placeholder="Ketik minimal 3 huruf nama kecamatan (contoh: Tebet, Sukajadi)..." class="w-full border border-gray-300 p-2.5 pl-8 text-xs font-inter focus:outline-none focus:border-black bg-white transition-colors">
                    <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span id="modal-area-spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-space-grotesk">Mencari...</span>
                </div>
                <ul id="modal-area-results" class="absolute z-30 w-full bg-white border border-gray-300 shadow-xl mt-1 hidden max-h-48 overflow-y-auto text-xs font-inter divide-y divide-gray-100"></ul>

                <!-- Confirmed Area Display -->
                <div id="modal-confirmed-area" class="hidden mt-2 p-2 bg-neutral-50 border border-gray-200 flex items-center justify-between text-xs font-inter">
                    <div class="flex items-center space-x-2 truncate">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                        <span id="modal-confirmed-area-text" class="text-neutral-800 font-medium truncate">Pilihan Wilayah</span>
                    </div>
                    <span class="text-[9px] font-bold font-space-grotesk uppercase bg-neutral-200 px-1.5 py-0.5 text-neutral-700 shrink-0">Terkonfirmasi</span>
                </div>

                <input type="hidden" id="modal-area-id" required>
                <input type="hidden" id="modal-area-name">
            </div>

            <!-- Full Street Address Details -->
            <div>
                <label class="block font-space-grotesk text-[10px] font-bold uppercase tracking-wider text-gray-700 mb-1">Alamat Lengkap & Patokan Rumah *</label>
                <textarea id="modal-address-detail" required rows="3" placeholder="Nama jalan, nomor rumah/bangunan, RT/RW, kelurahan, dan patokan..." class="w-full border border-gray-300 p-2.5 text-xs font-inter focus:outline-none focus:border-black bg-white resize-none leading-relaxed transition-colors"></textarea>
            </div>

            <!-- Set as Primary Checkbox -->
            <div class="flex items-center space-x-2 pt-1">
                <input type="checkbox" id="modal-is-primary" class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black cursor-pointer">
                <label for="modal-is-primary" class="text-xs font-inter text-gray-700 cursor-pointer select-none">
                    Jadikan sebagai alamat pengiriman utama (Primary Address)
                </label>
            </div>

            <!-- Actions -->
            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                <button type="button" onclick="closeAddressModal()" class="px-4 py-2 border border-gray-300 text-xs font-bold font-space-grotesk uppercase tracking-wider hover:bg-neutral-100 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="modal-submit-btn" class="px-5 py-2 bg-black hover:bg-neutral-800 text-white text-xs font-bold font-space-grotesk uppercase tracking-wider transition-colors cursor-pointer flex items-center space-x-2">
                    <span id="modal-submit-btn-text">SIMPAN ALAMAT</span>
                </button>
            </div>
        </form>
    </div>
</div>
