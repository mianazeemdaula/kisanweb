<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <a href="{{ url('/') }}" class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.svg') }}" class="w-10 h-10" alt="Logo">
                <span class="text-xl font-semibold text-green-700">KisanStock</span>
            </a>
            <div class="flex space-x-1">
                <a href="{{ url('rates') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors">
                    Rates
                </a>
                <a href="{{ url('deals') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors">
                    Deals
                </a>
                <a href="{{ url('commission-shops') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors">
                    Shops
                </a>
            </div>
        </div>
    </div>
</nav>
