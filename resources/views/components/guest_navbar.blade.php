@php
    $navLinks = [
        ['url' => url('/'), 'label' => 'Home', 'active' => request()->is('/')],
        ['url' => url('rates'), 'label' => 'Market Rates', 'active' => request()->is('rates*')],
        ['url' => url('deals'), 'label' => 'Deals', 'active' => request()->is('deals*')],
        ['url' => url('commission-shops'), 'label' => 'Shops', 'active' => request()->is('commission-shops*')],
    ];
@endphp

<nav id="dm-navbar"
    class="sticky top-0 z-50 backdrop-blur-xl border-b border-green-100/70 bg-white/70 transition-base">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center h-16">
            <!-- Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                <span
                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-700 text-white shadow-md shadow-green-600/30 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22c4.97 0 9-4.03 9-9-4.97 0-9 4.03-9 9Z" />
                        <path d="M12 22c0-4.97-4.03-9-9-9 0 4.97 4.03 9 9 9Z" />
                        <path d="M12 13c0-3.31 2.69-6 6-6 0 3.31-2.69 6-6 6Z" />
                        <path d="M12 13V2" />
                    </svg>
                </span>
                <span class="flex flex-col leading-none">
                    <span class="font-display text-lg sm:text-xl font-extrabold tracking-tight">
                        <span class="text-gray-900">Digital</span><span class="text-green-600">Mandi</span>
                    </span>
                    <span class="hidden sm:block text-[10px] font-medium text-gray-400 tracking-wide uppercase">Smart
                        Agri Marketplace</span>
                </span>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-1">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['url'] }}"
                        class="px-4 py-2 text-sm font-semibold rounded-xl transition-base {{ $link['active'] ? 'text-green-700 bg-green-50' : 'text-gray-600 hover:text-green-700 hover:bg-green-50' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <a href="https://play.google.com/store/apps/details?id=com.kisan.digitalmandi&hl=en" target="_blank"
                    rel="noopener"
                    class="ml-2 inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-md shadow-green-600/25 hover:shadow-lg hover:shadow-green-600/40 hover:-translate-y-0.5 transition-base">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M3 20.5v-17c0-.6.3-1.1.8-1.4l10.6 9.9L3.8 21.9c-.5-.3-.8-.8-.8-1.4Zm13.8-7.5 2.7 2.5-2.7 2.5-3-2.7 3-2.3Zm-2.4-2.2L5.4 2.4 16 8.5l-1.6 1.8Zm0 4.4 1.6 1.8L5.4 22.1l9-8.4Z" />
                    </svg>
                    Get the App
                </a>
            </div>

            <!-- Mobile toggle -->
            <button id="dm-menu-btn" type="button" aria-label="Toggle menu" aria-expanded="false"
                class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-600 hover:bg-green-50 hover:text-green-700 transition-base">
                <svg id="dm-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="dm-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="dm-mobile-menu" class="md:hidden hidden border-t border-green-100 bg-white/95 backdrop-blur-xl">
        <div class="px-4 py-4 space-y-1">
            @foreach ($navLinks as $link)
                <a href="{{ $link['url'] }}"
                    class="block px-4 py-3 text-sm font-semibold rounded-xl transition-base {{ $link['active'] ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-green-50' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="https://play.google.com/store/apps/details?id=com.kisan.digitalmandi&hl=en" target="_blank" rel="noopener"
                class="flex items-center justify-center gap-2 mt-2 px-5 py-3 text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-md">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M3 20.5v-17c0-.6.3-1.1.8-1.4l10.6 9.9L3.8 21.9c-.5-.3-.8-.8-.8-1.4Zm13.8-7.5 2.7 2.5-2.7 2.5-3-2.7 3-2.3Zm-2.4-2.2L5.4 2.4 16 8.5l-1.6 1.8Zm0 4.4 1.6 1.8L5.4 22.1l9-8.4Z" />
                </svg>
                Download App
            </a>
        </div>
    </div>
</nav>

<script>
    (function() {
        const btn = document.getElementById('dm-menu-btn');
        const menu = document.getElementById('dm-mobile-menu');
        const iconOpen = document.getElementById('dm-icon-open');
        const iconClose = document.getElementById('dm-icon-close');
        const nav = document.getElementById('dm-navbar');

        if (btn && menu) {
            btn.addEventListener('click', function() {
                const isHidden = menu.classList.toggle('hidden');
                iconOpen.classList.toggle('hidden', !isHidden);
                iconClose.classList.toggle('hidden', isHidden);
                btn.setAttribute('aria-expanded', String(!isHidden));
            });
        }

        if (nav) {
            const onScroll = function() {
                if (window.scrollY > 8) {
                    nav.classList.add('shadow-lg', 'shadow-green-900/5', 'bg-white/90');
                    nav.classList.remove('bg-white/70');
                } else {
                    nav.classList.remove('shadow-lg', 'shadow-green-900/5', 'bg-white/90');
                    nav.classList.add('bg-white/70');
                }
            };
            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            onScroll();
        }
    })();
</script>
