<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Digital Mandi | Smart Agricultural Marketplace')</title>
    <meta name="description"
        content="Digital Mandi is a modern marketplace for farmers and agribusinesses to buy and sell agricultural products, access real-time market prices, and connect with verified buyers and commission shops across the country.">
    <meta name="keywords" content="digital mandi, mandi rates, crop rates, agriculture marketplace, farmer deals, commission shops">
    <meta name="og:title" property="og:title" content="@yield('title', 'Digital Mandi')">
    <meta name="theme-color" content="#16a34a">
    <meta name="robots" content="index, follow">

    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --dm-primary: #16a34a;
            --dm-primary-dark: #15803d;
            --dm-accent: #f59e0b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-display {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            letter-spacing: -0.02em;
        }

        .transition-base {
            transition: color .2s ease, background-color .2s ease, border-color .2s ease, transform .2s ease, box-shadow .2s ease;
        }

        .hover-lift {
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(22, 163, 74, .18);
        }

        a:focus-visible,
        button:focus-visible {
            outline: 2px solid var(--dm-primary);
            outline-offset: 2px;
            border-radius: .5rem;
        }

        /* Brand gradient text */
        .text-gradient {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #84cc16 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-mesh {
            background-color: #f0fdf4;
            background-image:
                radial-gradient(at 0% 0%, rgba(34, 197, 94, .12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(245, 158, 11, .10) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(132, 204, 22, .10) 0px, transparent 50%);
        }

        @keyframes dm-fade-up {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: dm-fade-up .6s ease both;
        }

        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 8px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #bbf7d0;
            border-radius: 8px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #86efac;
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

<body class="bg-gray-50 text-gray-900 antialiased">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5H3WJ43" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @include('components.guest_navbar')

    <main class="min-h-[70vh]">
        @yield('body')
    </main>

    @include('components.dm_footer')

    @yield('js')

    <script>
        // Reveal-on-scroll animation
        (function() {
            const reveals = document.querySelectorAll('.reveal');
            if (!('IntersectionObserver' in window) || reveals.length === 0) {
                reveals.forEach(el => el.classList.add('is-visible'));
                return;
            }
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, {
                threshold: .12
            });
            reveals.forEach(el => io.observe(el));
        })();
    </script>

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
