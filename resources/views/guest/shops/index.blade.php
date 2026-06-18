@extends('layouts.guest')
@section('title', 'Commission Shops | Digital Mandi')
@section('body')
    <div class="bg-mesh min-h-screen">
        <!-- Header -->
        <section class="relative overflow-hidden">
            <div class="absolute -top-20 right-0 w-80 h-80 bg-green-300/20 rounded-full blur-3xl"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-16 pb-10 animate-fade-up">
                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/70 backdrop-blur border border-green-200 text-green-700 text-sm font-semibold mb-5 shadow-sm">
                    <span class="bi bi-shop"></span> Trusted Partners
                </span>
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h1 class="font-display text-4xl sm:text-5xl font-extrabold text-gray-900">
                            Commission <span class="text-gradient">Shops</span>
                        </h1>
                        <p class="text-gray-600 text-lg mt-3 max-w-2xl">Discover verified commission shops and connect
                            with the right partners.</p>
                    </div>
                    <span class="text-sm font-semibold text-green-700 bg-green-100 px-4 py-2 rounded-full">
                        {{ $shops->count() }} {{ Str::plural('shop', $shops->count()) }}
                    </span>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-20">
            @if ($shops->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($shops as $item)
                        <a href="{{ url("/commission-shops/$item->id") }}" class="reveal group block">
                            <div
                                class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-green-300 hover-lift h-full flex flex-col">
                                <div class="relative aspect-video bg-gray-100 overflow-hidden">
                                    <img src="{{ $item->banner }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        alt="{{ $item->name }}" loading="lazy">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                                    <span
                                        class="absolute top-3 left-3 inline-flex items-center gap-1.5 bg-white/90 backdrop-blur text-green-700 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                        <span class="bi bi-geo-alt"></span> {{ $item->city->name ?? '' }}
                                    </span>
                                </div>
                                <div class="p-5 flex flex-col flex-1">
                                    <div class="flex items-center gap-3 -mt-10 mb-3 relative">
                                        <img src="{{ $item->logo }}"
                                            class="w-14 h-14 rounded-2xl object-cover border-4 border-white shadow-md bg-white"
                                            alt="" loading="lazy">
                                    </div>
                                    <div class="font-bold text-gray-900 text-lg group-hover:text-green-600 transition-base">
                                        {{ $item->name }}</div>
                                    <div class="text-xs text-gray-400 mb-3">{{ $item->created_at->diffForHumans() }}</div>
                                    <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-1">{{ $item->about }}</p>
                                    <div
                                        class="flex justify-between items-center text-sm pt-3 border-t border-gray-50">
                                        <span
                                            class="inline-flex items-center gap-1.5 text-green-700 font-semibold bg-green-50 px-3 py-1 rounded-full">
                                            <span class="bi bi-shop-window"></span> Commission Shop
                                        </span>
                                        <div
                                            class="flex items-center gap-1.5 text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full">
                                            <span class="bi bi-star-fill text-amber-400"></span>
                                            <span class="font-semibold">{{ $item->ratings->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if (method_exists($shops, 'links'))
                    <div class="mt-10">{{ $shops->links() }}</div>
                @endif
            @else
                <div class="text-center py-24">
                    <div
                        class="mx-auto w-20 h-20 rounded-2xl bg-green-50 text-green-300 flex items-center justify-center text-4xl bi bi-shop mb-5"></div>
                    <h3 class="text-lg font-semibold text-gray-900">No shops available</h3>
                    <p class="mt-2 text-sm text-gray-500">Check back soon for verified commission shops.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
