<!-- TOAST NOTIFICATION CONTAINER -->
<div id="toast-container" class="fixed bottom-12 right-6 z-[99999] pointer-events-none flex flex-col space-y-2 max-w-sm w-auto"></div>

<script>
(function() {
    // Universal Notisse Toast: Cuma background hitam dan teks putih saja
    window.showToast = function(message) {
        if (!message) return;
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed bottom-12 right-6 z-[99999] pointer-events-none flex flex-col space-y-2 max-w-sm w-auto';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = 'bg-black text-white px-5 py-3 rounded text-xs font-space-grotesk uppercase tracking-wider shadow-xl transition-all duration-300 opacity-0 transform translate-y-2 pointer-events-auto cursor-pointer select-none';
        toast.innerText = message;

        let isDismissed = false;
        let timer = null;

        const dismiss = () => {
            if (isDismissed) return;
            isDismissed = true;
            if (timer) clearTimeout(timer);
            toast.classList.remove('opacity-100', 'translate-y-0');
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        };

        toast.addEventListener('click', dismiss);

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('opacity-0', 'translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');
        }, 20);

        timer = setTimeout(dismiss, 3000);
    };

    // Gantikan alert browser default agar tetap konsisten menggunakan toast hitam Notisse
    window.alert = function(message) {
        if (typeof window.showToast === 'function') {
            window.showToast(message);
        }
    };
})();
</script>

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

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            if (typeof showToast === 'function') {
                showToast(@json(session('warning')));
            }
        }, 300);
    });
</script>
@endif

@if(session('info') || session('status'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            if (typeof showToast === 'function') {
                showToast(@json(session('info') ?? session('status')));
            }
        }, 300);
    });
</script>
@endif

@if(isset($errors) && $errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            if (typeof showToast === 'function') {
                showToast(@json($errors->first()));
            }
        }, 300);
    });
</script>
@endif
