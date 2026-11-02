{{-- ══════════════════════════════════════════════════════════════
     NOTISSE LUXURY CONFIRMATION MODAL (STOREFRONT)
     Consistent Streetwear Aesthetic — No Native OS Dialogs
══════════════════════════════════════════════════════════════ --}}
<div id="notisse-confirm-modal" class="fixed inset-0 z-[99998] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity duration-300 opacity-0 pointer-events-none" onclick="window.closeStorefrontConfirmModal(false)">
    <div class="modal-box bg-white text-black border border-neutral-300 shadow-2xl max-w-sm w-full p-6 text-center transform scale-95 transition-all duration-300 relative select-none" onclick="event.stopPropagation()">
        <!-- Close button top-right -->
        <button type="button" onclick="window.closeStorefrontConfirmModal(false)" class="absolute top-4 right-4 text-neutral-400 hover:text-black transition-colors cursor-pointer p-1" title="Tutup">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Status Icon: Danger -->
        <div id="notisse-confirm-icon-danger" class="mx-auto w-12 h-12 rounded-full bg-red-50 border border-red-100 flex items-center justify-center mb-4 text-red-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <!-- Status Icon: Normal / Info -->
        <div id="notisse-confirm-icon-normal" class="hidden mx-auto w-12 h-12 rounded-full bg-neutral-100 border border-neutral-200 flex items-center justify-center mb-4 text-black">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <!-- Title & Message -->
        <h3 id="notisse-confirm-title" class="font-space-grotesk font-extrabold text-base uppercase tracking-wider text-black mb-2">
            Konfirmasi
        </h3>
        <p id="notisse-confirm-message" class="text-xs text-neutral-600 font-inter leading-relaxed mb-6">
            Apakah Anda yakin ingin melanjutkan tindakan ini?
        </p>

        <!-- Actions -->
        <div class="flex items-center gap-3">
            <button type="button" id="notisse-cancel-btn" onclick="window.closeStorefrontConfirmModal(false)" class="flex-1 py-2.5 px-4 border border-neutral-300 hover:border-black text-black font-space-grotesk font-bold text-xs uppercase tracking-wider transition-colors cursor-pointer bg-white">
                Batal
            </button>
            <button type="button" id="notisse-confirm-btn" onclick="window.closeStorefrontConfirmModal(true)" class="flex-1 py-2.5 px-4 bg-black hover:bg-neutral-800 text-white font-space-grotesk font-bold text-xs uppercase tracking-wider transition-colors cursor-pointer shadow-sm">
                Lanjutkan
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    let confirmResolver = null;

    /**
     * Ask user confirmation via custom storefront modal.
     * Returns Promise<boolean>.
     */
    window.askStorefrontConfirm = function(options = {}) {
        return new Promise((resolve) => {
            confirmResolver = resolve;

            const modal = document.getElementById('notisse-confirm-modal');
            if (!modal) {
                // Safe fallback in case modal markup wasn't rendered
                resolve(true);
                return;
            }

            const title = options.title || 'Konfirmasi';
            const message = options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            const confirmText = options.confirmText || 'Lanjutkan';
            const cancelText = options.cancelText || 'Batal';
            const isDanger = options.isDanger !== false; // default true if not specified

            const titleEl = document.getElementById('notisse-confirm-title');
            const msgEl = document.getElementById('notisse-confirm-message');
            const btnConfirm = document.getElementById('notisse-confirm-btn');
            const btnCancel = document.getElementById('notisse-cancel-btn');
            const iconDanger = document.getElementById('notisse-confirm-icon-danger');
            const iconNormal = document.getElementById('notisse-confirm-icon-normal');

            if (titleEl) titleEl.textContent = title;
            if (msgEl) msgEl.textContent = message;
            if (btnCancel) btnCancel.textContent = cancelText;

            if (btnConfirm) {
                btnConfirm.textContent = confirmText;
                if (isDanger) {
                    btnConfirm.className = 'flex-1 py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-space-grotesk font-bold text-xs uppercase tracking-wider transition-colors cursor-pointer shadow-sm';
                } else {
                    btnConfirm.className = 'flex-1 py-2.5 px-4 bg-black hover:bg-neutral-800 text-white font-space-grotesk font-bold text-xs uppercase tracking-wider transition-colors cursor-pointer shadow-sm';
                }
            }

            if (iconDanger && iconNormal) {
                if (isDanger) {
                    iconDanger.classList.remove('hidden');
                    iconNormal.classList.add('hidden');
                } else {
                    iconDanger.classList.add('hidden');
                    iconNormal.classList.remove('hidden');
                }
            }

            // Show modal
            modal.classList.remove('pointer-events-none', 'opacity-0');
            modal.classList.add('opacity-100');
            const box = modal.querySelector('.modal-box');
            if (box) {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }
        });
    };

    window.closeStorefrontConfirmModal = function(result) {
        const modal = document.getElementById('notisse-confirm-modal');
        if (modal) {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');
            const box = modal.querySelector('.modal-box');
            if (box) {
                box.classList.remove('scale-100');
                box.classList.add('scale-95');
            }
        }
        if (confirmResolver) {
            const r = confirmResolver;
            confirmResolver = null;
            r(!!result);
        }
    };

    // ESC key listener to dismiss
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('notisse-confirm-modal');
            if (modal && modal.classList.contains('opacity-100')) {
                window.closeStorefrontConfirmModal(false);
            }
        }
    });
})();
</script>
