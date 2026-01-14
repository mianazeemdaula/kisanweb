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
$shopsCount = \App\Models\CommissionShop::where('is_active', true)->count();
    @endphp

    <!-- Crop Rates Ticker -->
    <div class="bg-green-50 border-b border-green-100">
        <div class="marquee py-3">
            <ul class="crop-rates flex space-x-8 text-sm text-green-800">
                @foreach (\App\Models\CropRate::orderBy('rate_date', 'desc')->take(20)->get() as $item)
                    <li class="whitespace-nowrap">
                        <span class="font-medium">{{ $item->city->name }}:</span>
                        <span class="text-green-600">₹{{ $item->min_price }} - ₹{{ $item->max_price }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-b from-white to-green-50 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-5xl md:text-7xl font-bold mb-6">
                    <span class="text-gray-900">Kisan</span><span class="text-green-600">Stock</span>
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Your trusted platform for buying and selling agricultural products with real-time market rates.
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-16">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-green-100">
                    <div class="text-3xl font-bold text-green-600">{{ $todayRates->count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Today's Rates</div>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-green-100">
                    <div class="text-3xl font-bold text-green-600">{{ $dealsCount }}</div>
                    <div class="text-sm text-gray-600 mt-1">Active Deals</div>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-green-100">
                    <div class="text-3xl font-bold text-green-600">{{ $shopsCount }}</div>
                    <div class="text-sm text-gray-600 mt-1">Verified Shops</div>
                </div>
                <div class="bg-white rounded-lg p-6 shadow-sm border border-green-100">
                    <div class="text-3xl font-bold text-green-600">{{ \App\Models\Crop::count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Crop Types</div>
                </div>
            </div>

            <!-- Today's Rates Section -->
            @if ($todayRates->count() > 0)
                <div class="mb-16">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold text-gray-900">Today's Market Rates</h2>
                        <a href="{{ url('rates') }}"
                            class="text-green-600 hover:text-green-700 font-medium flex items-center">
                            View All <span class="ml-2">→</span>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($todayRates as $rate)
                            <div
                                class="bg-white rounded-lg p-5 shadow-sm border border-gray-200 hover:border-green-400 transition-all">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $rate->cropType->crop->name ?? 'N/A' }}
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $rate->cropType->name ?? '' }}</p>
                                    </div>
                                    <span
                                        class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">{{ $rate->city->name }}</span>
                                </div>
                                <div class="flex items-end justify-between">
                                    <div>
                                        <div class="text-2xl font-bold text-green-600">
                                            ₹{{ number_format($rate->min_price, 2) }}</div>
                                        <div class="text-sm text-gray-500">to ₹{{ number_format($rate->max_price, 2) }}
                                        </div>
                                    </div>
                                    @php
                                        $priceChange = $rate->min_price - $rate->min_price_last;
                                        $isUp = $priceChange > 0;
                                        $isDown = $priceChange < 0;
                                    @endphp
                                    @if ($priceChange != 0)
                                        <div class="text-right">
                                            <span class="text-xs {{ $isUp ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $isUp ? '↑' : '↓' }} ₹{{ number_format(abs($priceChange), 2) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Weekly Trend Section -->
            @if ($weeklyTrend->count() > 0)
                <div class="mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Weekly Price Trends</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($weeklyTrend->take(4) as $cropName => $rates)
                            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                                <h3 class="font-bold text-gray-900 mb-4">{{ $cropName }}</h3>
                                <div class="space-y-2">
                                    @foreach ($rates->take(5) as $rate)
                                        <div class="flex justify-between items-center text-sm">
                                            <span
                                                class="text-gray-600">{{ \Carbon\Carbon::parse($rate->rate_date)->format('M d') }}</span>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-gray-500">{{ $rate->city->name ?? 'N/A' }}</span>
                                                <span
                                                    class="font-medium text-green-600">₹{{ number_format($rate->min_rate, 0) }}-{{ number_format($rate->max_rate, 0) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Social Links -->
            <div class="text-center">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Connect With Us</h3>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="https://www.facebook.com/kisanstock"
                        class="px-6 py-3 bg-white border-2 border-green-600 text-green-700 font-medium rounded-lg hover:bg-green-600 hover:text-white transition-all">
                        Facebook
                    </a>
                    <a href="https://tiktok.com/@kisanstock"
                        class="px-6 py-3 bg-white border-2 border-green-600 text-green-700 font-medium rounded-lg hover:bg-green-600 hover:text-white transition-all">
                        TikTok
                    </a>
                    <a href="https://youtube.com/@kisanstock/"
                        class="px-6 py-3 bg-white border-2 border-green-600 text-green-700 font-medium rounded-lg hover:bg-green-600 hover:text-white transition-all">
                        Youtube
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=com.kisanstock.app"
                        class="px-8 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-all shadow-md">
                        Download App
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
