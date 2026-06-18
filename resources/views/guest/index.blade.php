@extends('layouts.guest')
@section('body')
    @php
        // Get today's rates
$todayRates = \App\Models\CropRate::with(['cropType.crop', 'city'])
    ->whereDate('rate_date', today())
    ->orderBy('rate_date', 'desc')
    ->take(12)
    ->get();

// Get weekly trend data (last 7 days)
$weeklyTrend = \App\Models\CropRate::with(['cropType.crop', 'city'])
    ->whereDate('rate_date', '>=', now()->subDays(7))
    ->cityHistory()
    ->orderBy('rate_date', 'desc')
    ->take(21)
    ->get()
    ->groupBy(function ($item) {
        return $item->cropType->crop->name ?? 'Unknown';
    });

// Latest deals count
$dealsCount = \App\Models\Deal::whereDate('created_at', '>=', now()->subDays(7))->count();

// Active shops count
$shopsCount = \App\Models\CommissionShop::where('active', true)->count();
    @endphp

    <!-- Crop Rates Ticker -->
    <div class="bg-green-600 text-white overflow-hidden">
        <div class="marquee py-2.5">
            <ul class="crop-rates flex space-x-8 text-sm">
                @foreach (\App\Models\CropRate::with('city')->orderBy('rate_date', 'desc')->take(20)->get() as $item)
                    <li class="whitespace-nowrap flex items-center gap-2">
                        <span class="bi bi-graph-up-arrow text-green-200"></span>
                        <span class="font-semibold">{{ $item->city->name ?? '' }}:</span>
                        <span class="text-green-100">Rs. {{ $item->min_price }} – Rs. {{ $item->max_price }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative bg-mesh overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-green-300/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-lime-300/20 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-20 lg:py-28">
            <div class="text-center max-w-3xl mx-auto animate-fade-up">
                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/70 backdrop-blur border border-green-200 text-green-700 text-xs sm:text-sm font-semibold mb-6 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-600"></span>
                    </span>
                    Live mandi rates updated daily
                </span>
                <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight mb-6">
                    <span class="text-gray-900">Welcome to </span><span class="text-gradient">Digital Mandi</span>
                </h1>
                <p class="text-gray-600 text-lg sm:text-xl leading-relaxed mb-9 max-w-2xl mx-auto">
                    The smart agricultural marketplace to buy &amp; sell crops, track real-time market rates, and
                    connect with verified buyers and commission shops.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ url('rates') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 text-base font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg shadow-green-600/25 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-green-600/40 transition-base">
                        <span class="bi bi-bar-chart-line"></span> Explore Market Rates
                    </a>
                    <a href="{{ url('deals') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 text-base font-semibold text-green-700 bg-white border-2 border-green-200 rounded-xl hover:border-green-400 hover:bg-green-50 transition-base">
                        <span class="bi bi-bag-check"></span> Browse Deals
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-16 max-w-4xl mx-auto">
                @php
                    $stats = [
                        ['value' => $todayRates->count(), 'label' => "Today's Rates", 'icon' => 'bi-graph-up'],
                        ['value' => $dealsCount, 'label' => 'Active Deals', 'icon' => 'bi-bag-check'],
                        ['value' => $shopsCount, 'label' => 'Verified Shops', 'icon' => 'bi-shop'],
                        ['value' => \App\Models\Crop::count(), 'label' => 'Crop Types', 'icon' => 'bi-flower1'],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div
                        class="bg-white/80 backdrop-blur rounded-2xl p-5 sm:p-6 shadow-sm border border-white hover-lift text-center">
                        <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-green-100 text-green-600 text-xl mb-3 {{ $stat['icon'] }} bi"></span>
                        <div class="text-3xl sm:text-4xl font-extrabold text-gray-900">{{ $stat['value'] }}</div>
                        <div class="text-xs sm:text-sm text-gray-500 mt-1 font-medium">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 space-y-20">
        <!-- Today's Rates Section -->
        @if ($todayRates->count() > 0)
            <section class="reveal">
                <div class="flex flex-wrap justify-between items-end gap-4 mb-8">
                    <div>
                        <span class="text-sm font-semibold text-green-600 uppercase tracking-wider">Live Prices</span>
                        <h2 class="font-display text-3xl sm:text-4xl font-bold text-gray-900 mt-1">Today's Market Rates
                        </h2>
                    </div>
                    <a href="{{ url('rates') }}"
                        class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-semibold transition-base">
                        View All <span class="bi bi-arrow-right"></span>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($todayRates as $rate)
                        <div
                            class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:border-green-300 hover-lift">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="flex items-center justify-center w-11 h-11 rounded-xl bg-green-50 text-green-600 text-xl bi bi-flower2"></span>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg leading-tight">
                                            {{ $rate->cropType->crop->name ?? 'N/A' }}
                                        </h3>
                                        <p class="text-sm text-gray-400">{{ $rate->cropType->name ?? '' }}</p>
                                    </div>
                                </div>
                                <span
                                    class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold whitespace-nowrap">{{ $rate->city->name }}</span>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <div class="text-2xl font-extrabold text-gray-900">
                                        Rs. {{ number_format($rate->min_price, 0) }}</div>
                                    <div class="text-sm text-gray-400">to Rs.
                                        {{ number_format($rate->max_price, 0) }}</div>
                                </div>
                                @php
                                    $priceChange = $rate->min_price - $rate->min_price_last;
                                    $isUp = $priceChange > 0;
                                @endphp
                                @if ($priceChange != 0)
                                    <span
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full {{ $isUp ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }}">
                                        <span class="bi {{ $isUp ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"></span>
                                        Rs. {{ number_format(abs($priceChange), 0) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Weekly Trend Section -->
        @if ($weeklyTrend->count() > 0)
            <section class="reveal">
                <div class="mb-8">
                    <span class="text-sm font-semibold text-green-600 uppercase tracking-wider">7-Day Insights</span>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold text-gray-900 mt-1">Weekly Price Trends</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($weeklyTrend->take(4) as $cropName => $rates)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover-lift">
                            <div class="flex items-center gap-3 mb-5">
                                <span
                                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-green-50 text-green-600 bi bi-graph-up-arrow"></span>
                                <h3 class="font-bold text-gray-900 text-xl">{{ $cropName }}</h3>
                            </div>
                            <div class="space-y-2.5">
                                @foreach ($rates->take(5) as $rate)
                                    <div
                                        class="flex justify-between items-center text-sm py-2 border-b border-gray-50 last:border-0">
                                        <span
                                            class="text-gray-500 font-medium">{{ \Carbon\Carbon::parse($rate->rate_date)->format('M d') }}</span>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-xs text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full">{{ $rate->city->name ?? 'N/A' }}</span>
                                            <span class="font-bold text-green-600">Rs.
                                                {{ number_format($rate->min_rate, 0) }}–{{ number_format($rate->max_rate, 0) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Features Section -->
        <section class="reveal">
            <div class="text-center mb-10">
                <span class="text-sm font-semibold text-green-600 uppercase tracking-wider">Why Digital Mandi</span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-gray-900 mt-1">Everything you need to trade
                    smarter</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $features = [
                        ['icon' => 'bi-bar-chart-line', 'title' => 'Real-Time Rates', 'desc' => 'Track daily mandi prices across cities and make informed selling decisions.'],
                        ['icon' => 'bi-people', 'title' => 'Direct Connections', 'desc' => 'Connect directly with verified buyers, sellers and commission shops.'],
                        ['icon' => 'bi-shield-check', 'title' => 'Trusted & Secure', 'desc' => 'Verified listings and ratings help you trade with confidence.'],
                    ];
                @endphp
                @foreach ($features as $f)
                    <div
                        class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover-lift text-center md:text-left">
                        <span
                            class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-green-700 text-white text-2xl shadow-lg shadow-green-600/25 mb-5 bi {{ $f['icon'] }}"></span>
                        <h3 class="font-bold text-xl text-gray-900 mb-2">{{ $f['title'] }}</h3>
                        <p class="text-gray-500 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- CTA Section -->
        <section class="reveal">
            <div
                class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-green-600 via-green-700 to-green-800 px-6 py-14 sm:px-12 sm:py-16 text-center shadow-xl">
                <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-lime-300/10 rounded-full blur-2xl"></div>
                <div class="relative max-w-2xl mx-auto">
                    <h2 class="font-display text-3xl sm:text-4xl font-bold text-white mb-4">Take the mandi with you</h2>
                    <p class="text-green-50 text-lg mb-8">Download the Digital Mandi app to buy, sell and track rates
                        anywhere, anytime.</p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="https://play.google.com/store/apps/details?id=com.kisan.digitalmandi&hl=en" target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-2 px-7 py-3.5 text-base font-semibold text-green-700 bg-white rounded-xl hover:-translate-y-0.5 transition-base shadow-lg">
                            <span class="bi bi-google-play"></span> Download App
                        </a>
                        <a href="{{ url('commission-shops') }}"
                            class="inline-flex items-center gap-2 px-7 py-3.5 text-base font-semibold text-white border-2 border-white/40 rounded-xl hover:bg-white/10 transition-base">
                            <span class="bi bi-shop"></span> Find Shops
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .marquee {
            position: relative;
            overflow: hidden;
        }

        .marquee .crop-rates {
            width: max-content;
            animation: dm-marquee 40s linear infinite;
        }

        .marquee:hover .crop-rates {
            animation-play-state: paused;
        }

        @keyframes dm-marquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }
    </style>
@endsection
