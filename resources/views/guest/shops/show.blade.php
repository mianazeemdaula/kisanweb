@extends('layouts.guest')
@section('title')
    {{ $shop->name }} is provieding services in different crops located at {{ $shop->address }}
@endsection
@section('body')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="aspect-[21/9] bg-gray-100">
                <img src="{{ $shop->banner }}" class="w-full h-full object-cover" alt="{{ $shop->name }}">
            </div>
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $shop->name }}</h1>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase mb-2">About</h2>
                        <p class="text-gray-700">{{ $shop->about }}</p>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-2">Location</h2>
                            <p class="text-gray-700">{{ $shop->address }}</p>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-2">Contact</h2>
                            <div class="flex flex-col space-y-2">
                                @if (isset($shop->social_links['mobile']))
                                    <a href="tel:{{ $shop->social_links['mobile'] }}"
                                        class="flex items-center space-x-2 text-green-600 hover:text-green-700">
                                        <span class="bi bi-phone"></span>
                                        <span>{{ $shop->social_links['mobile'] }}</span>
                                    </a>
                                @endif
                                @if (isset($shop->social_links['whatsapp']))
                                    <a href="https://wa.me/{{ $shop->social_links['whatsapp'] }}"
                                        class="flex items-center space-x-2 text-green-600 hover:text-green-700">
                                        <span class="bi bi-whatsapp"></span>
                                        <span>{{ $shop->social_links['whatsapp'] }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-600">
                            <span class="bi bi-hand-thumbs-up text-green-600"></span>
                            <span>{{ $shop->ratings->count() }} ratings</span>
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
