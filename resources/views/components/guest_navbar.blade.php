<div class="p-2">
    <div class="flex w-full justify-between items-center bg-white/80 rounded-sm">
        <div class="px-4 py-2">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.svg') }}" class="w-12 h-12" alt="Logo">
            </a>
        </div>
        <nav class="px-4 flex space-x-3">
            <a href="{{ url('rates') }}">
                <div class="py-1 w-20 text-sm text-center rounded-md">Rates</div>
            </a>
            <div class="border-r"></div>
            <a href="{{ url('deals') }}">
                <div class="py-1 w-20 text-sm text-center rounded-md">Deals</div>
            </a>
            <div class="border-r"></div>
            <a href="{{ url('commission-shops') }}">
                <div class=" py-1 w-20 text-sm text-center rounded-md">Shops</div>
            </a>
        </nav>
    </div>
</div>
