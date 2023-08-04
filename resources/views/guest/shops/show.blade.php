@extends('layouts.guest')
@section('title')
    {{ $shop->name }} is provieding services in different crops located at {{ $shop->address }}
@endsection
@section('body')
    <div class="flex p-4">
        <div class="bg-green h-96 min-w-[30%] max-w-[30%] owl-carousel owl-theme">
            {{-- <img src="{{ $deal->media()->first()->path }}" alt="Image not found"> --}}
            <img src="{{ $shop->banner }}" alt="Image not found">
        </div>
        <div class="ml-2 flex-1">
            <h2 class="text-2xl">{{ $shop->name }}</h2>
            <div>About: {{ $shop->about }}</div>
            <div class="flex justify-between">
                <div class="font-bold">{{ $shop->address }}</div>
                <div class="flex space-x-1 bg-gray-200 rounded-2xl px-3 ">
                    <div>{{ $shop->ratings->count() }}</div>
                    <span class="bi bi-hand-thumbs-up"></span>
                </div>
            </div>
            <div class="flex space-x-4">
                <span class="bi bi-phone">{{ $shop->social_links['mobile'] }}</span>
                <span class="bi bi-whatsapp">{{ $shop->social_links['whatsapp'] }}</span>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="module">
        $('.owl-carousel').owlCarousel();
    </script>
@endsection
