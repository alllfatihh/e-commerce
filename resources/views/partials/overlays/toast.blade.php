    <!-- TOAST NOTIFICATION CONTAINER -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 pointer-events-none flex flex-col space-y-2"></div>

    <!-- TOAST NOTIFICATION -->
    <div id="toast-notification"
        class="fixed top-24 left-1/2 -translate-x-1/2 bg-black text-white px-6 py-3 rounded-full shadow-lg z-[60] flex items-center space-x-3 transition-all duration-300 opacity-0 translate-y-4 hidden font-space-grotesk text-sm tracking-wide">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span id="toast-message" class="font-medium">Notification message</span>
    </div>

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                if (typeof showToast === 'function') {
                    showToast(@json(session('error')));
                }
            }, 300);
        });
    </script>
    @endif

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                if (typeof showToast === 'function') {
                    showToast(@json(session('success')));
                }
            }, 300);
        });
    </script>
    @endif


