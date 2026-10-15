    <!-- OVERLAY 5: FULL-SCREEN IMAGE LIGHTBOX MODAL (ZOOM SIZE CHART & ARTWORKS) -->
    <div id="modal-image-lightbox"
        class="fixed inset-0 bg-white/95 backdrop-blur-[1px] hidden opacity-0 transition-opacity duration-300 flex flex-col items-center justify-center p-4 sm:p-8"
        onclick="closeLightbox()">
        <div class="absolute top-4 right-6 flex items-center space-x-4 z-20">
            <span id="lightbox-caption" class="text-xs text-gray-500 uppercase font-space-grotesk tracking-widest hidden sm:inline"></span>
            <button onclick="closeLightbox()" aria-label="Close Lightbox"
                class="text-3xl text-black hover:text-gray-600 hover:rotate-90 transition-transform duration-300 focus:outline-none cursor-pointer">
                &#10005;
            </button>
        </div>
        <div class="max-w-4xl max-h-[85vh] flex items-center justify-center p-2" onclick="event.stopPropagation()">
            <img id="lightbox-img" src="" alt="Enlarged View" class="max-w-full max-h-[82vh] object-contain shadow-2xl rounded-sm">
        </div>
    </div>

