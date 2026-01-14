@extends('layouts.guest')
@section('body')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="font-display text-4xl font-bold text-gray-900 mb-8 tracking-tight">Available Deals</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($deals as $item)
                <a href="{{ url("/deals/$item->id") }}" class="group">
                    <div
                        class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-green-500 hover:shadow-lg transition-all hover-lift">
                        <div class="aspect-video bg-gray-100">
                            <img src="{{ str_replace('http://127.0.0.1:8000', 'https://kisanstock.com', $item->media()->first()->path) }}"
                                class="w-full h-full object-cover" alt="Deal image">
                        </div>
                        <div class="p-4">
                            <div class="flex items-center space-x-2 mb-3">
                                <img src="{{ $item->seller->image }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-green-100" alt="">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">{{ $item->seller->name }}</div>
                                    <div class="text-xs text-gray-500 font-normal">{{ $item->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <span class="text-sm font-semibold text-gray-900">{{ $item->type->crop->name }}</span>
                                <span class="text-sm text-gray-500">({{ $item->type->name }})</span>
                            </div>
                            <div class="text-sm text-gray-600 mb-3 font-normal">{{ $item->qty }} {{ $item->weight->name }}</div>
                            <div class="flex justify-between items-center">
                                <div class="text-green-600 font-bold text-lg">Rs. {{ $item->demand }}/{{ $item->weight->name }}
                                </div>
                                <div class="flex items-center space-x-1 text-gray-500 text-sm">
                                    <span>{{ $item->reactions->count() }}</span>
                                    <span class="bi bi-hand-thumbs-up"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
