    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notisse - Fashion & Art Brand</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logowebkecil.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts Preconnect & Imports (Space Grotesk, Inter, Caveat) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['"Inter"', 'sans-serif'],
                        sans: ['"Inter"', 'sans-serif'],
                        'space-grotesk': ['"Space Grotesk"', 'sans-serif'],
                        spaceGrotesk: ['"Space Grotesk"', 'sans-serif'],
                        'inter': ['"Inter"', 'sans-serif'],
                        inter: ['"Inter"', 'sans-serif'],
                        'montserrat': ['"Space Grotesk"', 'sans-serif'],
                        montserrat: ['"Space Grotesk"', 'sans-serif'],
                        'roboto': ['"Inter"', 'sans-serif'],
                        roboto: ['"Inter"', 'sans-serif'],
                        'helvetica': ['"Inter"', 'sans-serif'],
                        helvetica: ['"Inter"', 'sans-serif'],
                        'neue-montreal': ['"Space Grotesk"', 'sans-serif'],
                        neueMontreal: ['"Space Grotesk"', 'sans-serif'],
                        cursive: ['Caveat', 'cursive'],
                    },
                    colors: {
                        brandDark: '#1c1c1c',
                        taupeBanner: '#A29892',
                        accentYellow: '#E2FF00',
                        placeholderBg: '#d9d9d9'
                    }
                }
            }
        }
    </script>
