<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kisan Stock | Buying and Selling</title>
    <meta name="description"
        content="Kisan Stock app is a comprehensive platform for farmers and agribusinesses to buy and sell agricultural products, access real-time market prices, and stay updated with the latest news and trends in the agricultural sector. With Kisan Stock app, farmers can easily connect with buyers and get the best prices for their produce, while buyers can easily source quality products directly from the farmers. The app also offers a range of tools and resources to help farmers make informed decisions and improve their productivity.">
    <meta name="keywords" content="kisanstock, kisan stock, crop rates">
    <meta name="og:title" property="og:title" content="@yield('title', 'Kisan Stock')">
    <meta name="robots" content="index, follow">
    <link href="URL" rel="canonical">
    
    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', 'Inter', sans-serif;
            font-weight: 600;
            letter-spacing: -0.02em;
        }
        
        .font-display {
            font-family: 'Poppins', sans-serif;
            letter-spacing: -0.03em;
        }
        
        /* Smooth animations */
        * {
            transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
        }
        
        /* Modern card hover effect */
        .hover-lift:hover {
            transform: translateY(-2px);
        }
        
        /* Better focus states */
        a:focus, button:focus {
            outline: 2px solid #22c55e;
            outline-offset: 2px;
        }
    </style>
    
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5H3WJ43');
    </script>
    <!-- End Google Tag Manager -->
</head>

<body class="bg-gray-50">
    @include('components.guest_navbar')
    @yield('body')

    <footer>
        <div class="bg-green-700 text-white text-center py-6">
            <p class="text-sm">&copy; 2024 KissanZone. All rights reserved.</p>
        </div>
    </footer>
    @yield('js')

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5H3WJ43" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BYRVJZWTZ3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-BYRVJZWTZ3');
    </script>
</body>

</html>
