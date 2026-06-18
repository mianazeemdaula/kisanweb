@extends('layouts.guest')
@section('title', 'Available Deals | Digital Mandi')
@section('body')
    <div class="bg-mesh min-h-screen">
        <!-- Header -->
        <section class="relative overflow-hidden">
            <div class="absolute -top-20 left-0 w-80 h-80 bg-green-300/20 rounded-full blur-3xl"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-16 pb-10 animate-fade-up">
                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/70 backdrop-blur border border-green-200 text-green-700 text-sm font-semibold mb-5 shadow-sm">
                    <span class="bi bi-bag-check"></span> Marketplace
                </span>
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h1 class="font-display text-4xl sm:text-5xl font-extrabold text-gray-900">
                            Available <span class="text-gradient">Deals</span>
                        </h1>
                        <p class="text-gray-600 text-lg mt-3 max-w-2xl">Fresh crop deals from verified sellers near you.
                        </p>
                    </div>
                    <span class="text-sm font-semibold text-green-700 bg-green-100 px-4 py-2 rounded-full">
                        {{ $deals->count() }} {{ Str::plural('deal', $deals->count()) }}
                    </span>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-20">
            @if ($deals->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($deals as $item)
                        <a href="{{ url("/deals/$item->id") }}" class="reveal group block">
                            <div
                                class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-green-300 hover-lift h-full flex flex-col">
                                <div class="relative aspect-video bg-gray-100 overflow-hidden">
                                    @php $media = $item->media()->first(); @endphp
                                    @if ($media)
                                        <img src="{{ str_replace('http://127.0.0.1:8000', 'https://kisanstock.com', $media->path) }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                            alt="{{ $item->type->crop->name ?? 'Deal' }}" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-green-200 text-4xl bi bi-image"></div>
                                    @endif
                                    <span
                                        class="absolute top-3 left-3 inline-flex items-center gap-1.5 bg-white/90 backdrop-blur text-green-700 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                        <span class="bi bi-flower2"></span> {{ $item->type->crop->name ?? 'Crop' }}
                                    </span>
                                </div>
                                <div class="p-5 flex flex-col flex-1">
                                    <div class="flex items-center gap-2.5 mb-4">
                                        <img src="{{ $item->seller->image }}"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-green-100" alt=""
                                            loading="lazy">
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-gray-900 truncate">{{ $item->seller->name }}
                                            </div>
                                            <div class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-sm text-gray-500">{{ $item->type->name }}</span>
                                        <div class="text-sm text-gray-600 mt-0.5">
                                            <span class="bi bi-box-seam text-green-500"></span> {{ $item->qty }}
                                            {{ $item->weight->name }}
                                        </div>
                                    </div>
                                    <div class="mt-auto flex items-end justify-between pt-3 border-t border-gray-50">
                                        <div>
                                            <div class="text-xs text-gray-400">Demand</div>
                                            <div class="text-green-600 font-extrabold text-lg leading-tight">Rs.
                                                {{ $item->demand }}<span
                                                    class="text-xs text-gray-400 font-medium">/{{ $item->weight->name }}</span>
                                            </div>
                                        </div>
                                        <div
                                            class="flex items-center gap-1.5 text-gray-500 text-sm bg-gray-50 px-2.5 py-1 rounded-full">
                                            <span class="bi bi-hand-thumbs-up text-green-500"></span>
                                            <span class="font-semibold">{{ $item->reactions->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if (method_exists($deals, 'links'))
                    <div class="mt-10">{{ $deals->links() }}</div>
                @endif
            @else
                <div class="text-center py-24">
                    <div
                        class="mx-auto w-20 h-20 rounded-2xl bg-green-50 text-green-300 flex items-center justify-center text-4xl bi bi-bag-x mb-5"></div>
                    <h3 class="text-lg font-semibold text-gray-900">No deals available</h3>
                    <p class="mt-2 text-sm text-gray-500">Check back soon for new crop deals.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
