<nav class="bg-white shadow-sm sticky top-0 z-50 backdrop-blur-sm bg-white/95">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                <img src="{{ asset('images/logo.svg') }}" class="w-10 h-10 transition-transform group-hover:scale-105"
                    alt="Logo">
                <span class="font-display text-xl font-bold text-green-700 tracking-tight">KisanStock</span>
            </a>
            <div class="flex space-x-1">
                <a href="{{ url('rates') }}"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-xl transition-all">
                    Rates
                </a>
                <a href="{{ url('deals') }}"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-xl transition-all">
                    Deals
                </a>
                <a href="{{ url('commission-shops') }}"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-xl transition-all">
                    Shops
                </a>
            </div>
        </div>
    </div>
</nav>
