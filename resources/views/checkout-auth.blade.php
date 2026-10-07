<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notisse - Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        roboto: ['Roboto', 'sans-serif'],
                        helvetica: ['"Helvetica Neue"', 'Helvetica', 'Arial', 'sans-serif'],
                    },
                    colors: {
                        brandDark: '#111111',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #fff; color: #111; overflow-x: hidden; }
        .input-a24 {
            border: 1px solid #ccc; border-radius: 4px; padding: 12px 16px; width: 100%;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; transition: border-color 0.2s;
            outline: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .input-a24:focus { border-color: #3b82f6; box-shadow: 0 0 0 1px #3b82f6; }
        .btn-a24 {
            background-color: #1a66ff; color: #fff; padding: 16px 24px; font-weight: 700; border-radius: 6px; 
            border: none; cursor: pointer; transition: background-color 0.2s; width: 100%; font-size: 16px;
        }
        .btn-a24:hover { background-color: #0f52db; }
        .btn-google {
            background-color: #fff; color: #111; border: 1px solid #d1d5db; border-radius: 6px; padding: 14px 24px;
            font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 10px;
            text-transform: none; text-decoration: none; transition: background 0.2s; width: 100%;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .btn-google:hover { background-color: #f9fafb; border-color: #9ca3af; }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex flex-col md:flex-row min-h-screen">
        
        <!-- LEFT COLUMN: Form -->
        <div class="w-full md:w-1/2 lg:w-3/5 bg-white p-6 md:p-12 lg:p-16 flex justify-end order-2 md:order-1 border-r border-gray-200">
            <div class="max-w-lg w-full">
                <a href="/?open_checkout=true" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-black mb-6 tracking-wider uppercase font-roboto transition-colors">
                    &larr; Kembali ke Keranjang
                </a>
                
                <div class="mb-8">
                    <h2 class="text-xl font-bold font-helvetica mb-2">Contact</h2>
                    @if(!auth()->check())
                        <p class="text-sm text-gray-500 mb-4">Sudah punya akun? <a href="/auth/google" class="text-blue-600 underline">Login dengan Google</a></p>
                    @else
                        <p class="text-sm text-gray-500 mb-4">Login sebagai: <span class="font-bold text-gray-800">{{ auth()->user()->email }}</span></p>
                    @endif
                </div>

                <div id="error-message" class="hidden bg-red-100 text-red-700 p-3 rounded mb-6 text-sm border border-red-200"></div>

                <form id="seamless-form" class="space-y-6">
                    <!-- Contact Section -->
                    <div class="space-y-4">
                        <div>
                            <input type="email" id="email" class="input-a24" placeholder="Email" value="{{ auth()->user()->email ?? '' }}" required {{ auth()->check() ? 'readonly' : '' }}>
                        </div>
                        <div>
                            <input type="text" id="phone" class="input-a24" placeholder="Nomor Handphone (Whatsapp)" value="{{ auth()->user()->phone ?? '' }}" required>
                        </div>
                        @if(!auth()->check())
                        <div>
                            <input type="password" id="password" class="input-a24" placeholder="Buat Password Baru (min. 6 karakter)" required>
                        </div>
                        @else
                        <input type="hidden" id="password" value="google-login">
                        @endif
                    </div>

                    <!-- Delivery Section -->
                    <div class="mt-10">
                        <h2 class="text-xl font-bold font-helvetica mb-4">Delivery</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <input type="text" id="name" class="input-a24" placeholder="Nama Lengkap Penerima" value="{{ auth()->user()->name ?? '' }}" required {{ auth()->check() ? 'readonly' : '' }}>
                            </div>

                            <div class="relative">
                                <input type="text" id="area-search" class="input-a24" placeholder="Kecamatan / Kode Pos (Cari disini...)" autocomplete="off">
                                <ul id="area-results" class="absolute z-10 w-full bg-white border border-gray-300 shadow-lg rounded mt-1 hidden max-h-48 overflow-y-auto"></ul>
                            </div>
                            
                            <div>
                                <textarea id="address" class="input-a24 h-24 resize-none" placeholder="Alamat Lengkap (Nama Jalan, RT/RW, Patokan)" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" id="area_id" required>
                    <input type="hidden" id="area_name">

                    <button type="submit" id="submit-btn" class="btn-a24 mt-8">
                        Lanjut ke Pengiriman
                    </button>
                    <p class="text-xs text-center text-gray-500 mt-4">All transactions are secure and encrypted.</p>
                </form>
            </div>
        </div>

        <!-- RIGHT COLUMN: Order Summary -->
        <div class="w-full md:w-1/2 lg:w-2/5 bg-[#f5f5f5] p-6 md:p-12 lg:p-16 flex justify-start order-1 md:order-2">
            <div class="max-w-md w-full">
                <div id="checkout-summary-items" class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2">
                    <!-- Items injected by JS -->
                </div>

                <div class="border-t border-gray-300 pt-4 space-y-3 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="checkout-subtotal" class="font-medium text-gray-900"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span class="text-xs text-gray-500">Kalkulasi di tahap selanjutnya</span>
                    </div>
                </div>

                <div class="border-t border-gray-300 mt-4 pt-4 flex justify-between items-center">
                    <span class="text-lg text-gray-900">Total</span>
                    <span class="text-2xl font-bold text-gray-900 flex items-center gap-1">
                        <span class="text-xs text-gray-500 font-normal mr-1">IDR</span> 
                        <span id="checkout-total"></span>
                    </span>
                </div>
            </div>
        </div>
        
    </div>

    <script>
        // Render Cart Summary
        const cart = JSON.parse(localStorage.getItem('notisse_cart')) || [];
        const summaryContainer = document.getElementById('checkout-summary-items');
        let subtotal = 0;

        if (cart.length === 0) {
            summaryContainer.innerHTML = '<p class="text-sm text-gray-500">Keranjang Anda kosong.</p>';
        } else {
            cart.forEach(item => {
                const itemPrice = item.price || item.unitPrice || 0;
                const itemQty = item.qty || 1;
                subtotal += itemPrice * itemQty;
                
                let imgUrl = item.image || '';
                if (imgUrl && !imgUrl.startsWith('http') && !imgUrl.startsWith('/')) {
                    imgUrl = '/' + imgUrl;
                }
                
                const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(itemPrice * itemQty);

                summaryContainer.innerHTML += `
                    <div class="flex items-center gap-4 relative">
                        <div class="relative w-16 h-16 border border-gray-300 bg-white rounded-lg flex-shrink-0 overflow-hidden">
                            <img src="${imgUrl || '/footage-baju.jpg'}" class="w-full h-full object-cover rounded-lg" onerror="this.src='/footage-baju.jpg'">
                            <span class="absolute -top-2 -right-2 bg-gray-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">${itemQty}</span>
                        </div>
                        <div class="flex-grow">
                            <h4 class="font-medium text-sm text-gray-900">${item.name}</h4>
                            <p class="text-xs text-gray-500">${item.size}</p>
                        </div>
                        <div class="font-medium text-sm text-gray-900">${formattedPrice}</div>
                    </div>
                `;
            });
        }

        const formattedTotal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(subtotal);
        document.getElementById('checkout-subtotal').innerText = formattedTotal;
        document.getElementById('checkout-total').innerText = formattedTotal;


        // Form Logic
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let searchTimeout = null;

        document.getElementById('area-search').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const keyword = e.target.value;
            const resultsBox = document.getElementById('area-results');
            
            if (keyword.length < 3) {
                resultsBox.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/shipping-areas?keyword=${keyword}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsBox.innerHTML = '';
                        if (data.areas && data.areas.length > 0) {
                            data.areas.forEach(area => {
                                const li = document.createElement('li');
                                li.className = 'px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm font-helvetica border-b border-gray-100 last:border-0 text-gray-700';
                                li.innerText = area.name;
                                li.onclick = () => {
                                    document.getElementById('area-search').value = area.name;
                                    document.getElementById('area_id').value = area.id;
                                    document.getElementById('area_name').value = area.name;
                                    resultsBox.classList.add('hidden');
                                };
                                resultsBox.appendChild(li);
                            });
                            resultsBox.classList.remove('hidden');
                        } else {
                            resultsBox.classList.add('hidden');
                        }
                    });
            }, 500);
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!document.getElementById('area-search').contains(e.target) && !document.getElementById('area-results').contains(e.target)) {
                document.getElementById('area-results').classList.add('hidden');
            }
        });

        document.getElementById('seamless-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const areaId = document.getElementById('area_id').value;
            const errorBox = document.getElementById('error-message');
            errorBox.classList.add('hidden');

            if (!areaId) {
                errorBox.innerText = 'Silakan cari dan pilih kecamatan tujuan terlebih dahulu dari daftar drop-down yang muncul saat mengetik.';
                errorBox.classList.remove('hidden');
                return;
            }

            const btn = document.getElementById('submit-btn');
            const originalText = btn.innerText;
            btn.innerText = 'Memproses...';
            btn.disabled = true;
            btn.classList.add('opacity-75');

            const payload = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                area_id: areaId,
                _token: csrfToken
            };

            try {
                const response = await fetch('/auth/seamless', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Berhasil login/register, langsung kembalikan ke welcome blade
                    // dengan trigger buka checkout
                    window.location.href = '/?open_checkout=true';
                } else {
                    errorBox.innerText = data.message || 'Terjadi kesalahan. Periksa kembali email & password Anda.';
                    errorBox.classList.remove('hidden');
                    btn.innerText = originalText;
                    btn.disabled = false;
                    btn.classList.remove('opacity-75');
                }
            } catch (err) {
                errorBox.innerText = 'Koneksi ke server gagal.';
                errorBox.classList.remove('hidden');
                btn.innerText = originalText;
                btn.disabled = false;
                btn.classList.remove('opacity-75');
            }
        });
    </script>
</body>
</html>
