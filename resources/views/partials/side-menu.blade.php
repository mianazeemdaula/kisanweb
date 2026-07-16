<div>
    <span class="absolute text-white text-4xl top-5 left-4 cursor-pointer lg:hidden z-50" onclick="Openbar()">
        <i class="bi bi-filter-left px-2 bg-slate-900 rounded-md"></i>
    </span>
    <div
        class="sidebar top-0 bottom-0 lg:left-0 left-[-300px] duration-1000
        p-4 overflow-y-auto bg-slate-950 shadow-2xl h-screen overflow-x-hidden border-r border-slate-800 scrollbar-thin w-full">
        <div class="text-gray-100 text-xl">
            <div class="p-1.5 mt-1 flex items-center rounded-md justify-start mb-3">
                <i class="bi bi-app-indicator px-2 py-1.5 bg-gradient-to-br from-emerald-400 to-green-600 text-white rounded-lg shadow-md text-sm"></i>
                <h1 class="text-sm ml-2.5 text-slate-105 font-bold tracking-tight">Kisan Stock</h1>
                <i class="bi bi-x ml-auto cursor-pointer lg:hidden" onclick="Openbar()"></i>
            </div>
            
            <div class="space-y-1">
                <x-menu-item title="Statistics" icon="bi-grid-1x2" url="{{ url('/admin/home') }}" />
                <x-menu-item title="Category" icon="bi-tags" url="{{ route('admin.category.index') }}" />
                <x-menu-item title="Shops" icon="bi-shop-window" url="{{ route('admin.shops.index') }}" />
                <x-menu-item title="Cities" icon="bi-building" url="{{ route('admin.cities.index') }}" />
                <x-menu-item title="Quotes" icon="bi-quote" url="{{ route('admin.quotes.index') }}" />
                <x-menu-item title="Feeds" icon="bi-newspaper" url="{{ route('admin.feeds.index') }}" />
                <x-menu-item title="Deals" icon="bi-cart2" url="{{ route('admin.deals.index') }}" />
                <x-menu-item title="Category Deals" icon="bi-cart" url="{{ route('admin.category-deals.index') }}" />
                <x-menu-item title="Rate Reports" icon="bi-graph-up" url="{{ url('admin/rate-reports') }}" />
                <x-menu-item title="User Settings" icon="bi-gear" url="{{ route('admin.settings.index') }}" />
                <x-menu-item title="Feed Mills" icon="bi-buildings" url="{{ route('admin.feedmills.index') }}" />
                <x-menu-item title="Sugar Mills" icon="bi-gear" url="{{ route('admin.sugarmills.index') }}" />
                <x-menu-item title="WhatsApp" icon="bi-whatsapp" url="{{ url('admin/send-message') }}" />
                <x-menu-item title="Subscriptions" icon="bi-credit-card" url="{{ route('admin.subscriptions.index') }}" />
                <x-menu-item title="Ads" icon="bi-megaphone" url="{{ route('admin.ads.index') }}" />
                <x-menu-item title="Support" icon="bi-chat-left-dots" url="{{ route('admin.support.index') }}" />
                <x-menu-item title="DEO Rates" icon="bi-check2-circle" url="{{ route('admin.deorates.index') }}" />
                <x-menu-item title="Crops" icon="bi-flower3" url="{{ route('admin.crop.index') }}" />
                <x-menu-item title="Crop Types" icon="bi-list-ul" url="{{ route('admin.croptype.index') }}" />
                
                <div class="border-t border-slate-800 my-2"></div>

                <div
                    class="p-1.5 mt-1.5 flex items-center rounded-lg px-3 duration-300 cursor-pointer text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition-all"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-in-right text-sm"></i>
                    <span class="text-xs ml-2.5">Logout</span>
                </div>
                <form id="logout-form" action="{{ route('login') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <script>
        function Openbar() {
            document.querySelector('.sidebar').classList.toggle('left-[-300px]')
        }
    </script>
</div>
