<div>
    <span class="absolute text-white text-4xl top-5 left-4 cursor-pointer lg:hidden" onclick="Openbar()">
        <i class="bi bi-filter-left px-2 bg-gray-900 rounded-md"></i>
    </span>
    <div
        class="sidebar top-0 bottom-0 lg:left-0 left-[-300px] duration-1000
        p-2 overflow-y-auto text-center bg-gray-900 shadow h-screen overflow-x-auto">
        <div class="text-gray-100 text-xl">
            <div class="p-2.5 mt-1 flex items-center rounded-md ">
                <i class="bi bi-app-indicator px-2 py-1 bg-blue-600 rounded-md"></i>
                <h1 class="text-[15px]  ml-3 text-xl text-gray-200 font-bold">Kisan Stock</h1>
                <i class="bi bi-x ml-20 cursor-pointer lg:hidden" onclick="Openbar()"></i>
            </div>
            {{-- <hr class="my-2 text-gray-600"> --}}
            <div>
                {{-- <div
                    class="p-2.5 my-3 flex items-center rounded-md 
            px-4 duration-300 cursor-pointer  bg-gray-700">
                    <i class="bi bi-search text-sm"></i>
                    <input class="text-[15px] ml-4 w-full bg-transparent focus:outline-none" placeholder="Serach" />
                </div> --}}
                {{-- <x-menu-item title="Home" icon="bi-house-door-fill" active="true" />
                <hr class="my-4 text-gray-600"> --}}
                <x-menu-item title="Statistics" icon="bi-envelope-fill" active="false" url="{{ url('/admin/home') }}" />
                <x-menu-item title="Shops" icon="bi-shop" active="false" url="{{ route('admin.shops.index') }}" />
                <x-menu-item title="Cities" icon="bi-building" active="false" url="{{ route('admin.cities.index') }}" />
                <x-menu-item title="Quotes" icon="bi-quote" active="false" url="{{ route('admin.quotes.index') }}" />
                <x-menu-item title="Feeds" icon="bi-bookshelf" active="false" url="{{ route('admin.feeds.index') }}" />
                <x-menu-item title="Deals" icon="bi-cart2" active="false" url="{{ route('admin.deals.index') }}" />
                <x-menu-item title="Rate Reports" icon="bi-graph-up" active="false"
                    url="{{ url('admin/rate-reports') }}" />
                <x-menu-item title="User Settings" icon="bi-gear" active="false"
                    url="{{ route('admin.settings.index') }}" />
                <x-menu-item title="Feed Mills" icon="bi-buildings" active="false"
                    url="{{ route('admin.feedmills.index') }}" />

                <x-menu-item title="Sugar Mills" icon="bi-gear" active="false"
                    url="{{ route('admin.sugarmills.index') }}" />
                <x-menu-item title="WhatsApp" icon="bi-whatsapp" active="false"
                    url="{{ url('admin/send-message') }}" />
                <x-menu-item title="Subscriptions" icon="bi-search" active="false"
                    url="{{ route('admin.subscriptions.index') }}" />
                    <x-menu-item title="Ads" icon="bi-clipboard2-heart" active="false"
                    url="{{ route('admin.ads.index') }}" />

                {{-- <div
                    class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-blue-600">
                    <i class="bi bi-chat-left-text-fill"></i>
                    <div class="flex justify-between w-full items-center" onclick="dropDown()">
                        <span class="text-[15px] ml-4 text-gray-200">Chatbox</span>
                        <span class="text-sm rotate-180" id="arrow">
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </div>
                </div>
                <div class=" leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenu">
                    <h1 class="cursor-pointer p-2 hover:bg-gray-700 rounded-md mt-1">Social</h1>
                    <h1 class="cursor-pointer p-2 hover:bg-gray-700 rounded-md mt-1">Personal</h1>
                    <h1 class="cursor-pointer p-2 hover:bg-gray-700 rounded-md mt-1">Friends</h1>
                </div> --}}
                <div
                    class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-blue-600">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="text-[15px] ml-4 text-gray-200">Logout</span>
                </div>

            </div>
        </div>
    </div>

    <script>
        function dropDown() {
            document.querySelector('#submenu').classList.toggle('hidden')
            document.querySelector('#arrow').classList.toggle('rotate-0')
        }
        dropDown()

        function Openbar() {
            document.querySelector('.sidebar').classList.toggle('left-[-300px]')
        }
    </script>

</div>
