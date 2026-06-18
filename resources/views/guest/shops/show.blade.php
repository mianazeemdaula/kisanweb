@extends('layouts.guest')
@section('title')
    {{ $shop->name }} is provieding services in different crops located at {{ $shop->address }}
@endsection
@section('body')
    <div class="bg-mesh min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-green-600 transition-base">Home</a>
                <span class="bi bi-chevron-right text-xs"></span>
                <a href="{{ url('commission-shops') }}" class="hover:text-green-600 transition-base">Shops</a>
                <span class="bi bi-chevron-right text-xs"></span>
                <span class="text-gray-700 font-medium">{{ $shop->name }}</span>
            </nav>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Banner -->
                <div class="relative aspect-[21/9] bg-gray-100">
                    <img src="{{ $shop->banner }}" class="w-full h-full object-cover" alt="{{ $shop->name }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 flex items-end gap-4">
                        <img src="{{ $shop->logo }}"
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover border-4 border-white shadow-lg bg-white"
                            alt="{{ $shop->name }}">
                        <div class="pb-1">
                            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-white drop-shadow">
                                {{ $shop->name }}</h1>
                            <p class="text-white/90 text-sm flex items-center gap-1.5 mt-1">
                                <span class="bi bi-geo-alt"></span> {{ $shop->city->name ?? $shop->address }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8 lg:p-10">
                    <div class="grid lg:grid-cols-3 gap-8">
                        <!-- About -->
                        <div class="lg:col-span-2">
                            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">About</h2>
                            <p class="text-gray-700 leading-relaxed">{{ $shop->about }}</p>

                            <div class="mt-8">
                                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Location
                                </h2>
                                <p class="text-gray-700 flex items-start gap-2">
                                    <span class="bi bi-geo-alt text-green-500 mt-0.5"></span>
                                    <span>{{ $shop->address }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-4">
                            <div class="rounded-2xl border border-gray-100 p-5">
                                <div class="flex items-center gap-2 text-gray-700 mb-4">
                                    <span class="bi bi-star-fill text-amber-400"></span>
                                    <span class="font-bold">{{ $shop->ratings->count() }}</span>
                                    <span class="text-sm text-gray-400">ratings</span>
                                </div>
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Contact
                                </h3>
                                <div class="flex flex-col gap-2.5">
                                    @if (isset($shop->social_links['mobile']))
                                        <a href="tel:{{ $shop->social_links['mobile'] }}"
                                            class="flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-base">
                                            <span class="bi bi-telephone"></span> Call Shop
                                        </a>
                                    @endif
                                    @if (isset($shop->social_links['whatsapp']))
                                        <a href="https://wa.me/{{ $shop->social_links['whatsapp'] }}" target="_blank"
                                            rel="noopener"
                                            class="flex items-center justify-center gap-2 px-4 py-3 bg-green-50 text-green-700 font-semibold rounded-xl hover:bg-green-100 transition-base">
                                            <span class="bi bi-whatsapp"></span> WhatsApp
                                        </a>
                                    @endif
                                    @if (!isset($shop->social_links['mobile']) && !isset($shop->social_links['whatsapp']))
                                        <p class="text-sm text-gray-400 text-center">No contact info available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="module">
        $('.owl-carousel').owlCarousel();
    </script>
@endsection
