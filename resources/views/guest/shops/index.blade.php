@extends('layouts.guest')
@section('body')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="font-display text-4xl font-bold text-gray-900 mb-8 tracking-tight">Commission Shops</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($shops as $item)
                <a href="{{ url("/commission-shops/$item->id") }}" class="group">
                    <div
                        class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-green-500 hover:shadow-lg transition-all hover-lift">
                        <div class="aspect-video bg-gray-100">
                            <img src="{{ $item->banner }}" class="w-full h-full object-cover" alt="{{ $item->name }}">
                        </div>
                        <div class="p-4">
                            <div class="flex items-center space-x-2 mb-2">
                                <img src="{{ $item->logo }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-green-100" alt="">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900 group-hover:text-green-600">{{ $item->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 font-normal">{{ $item->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2 font-normal">{{ $item->about }}</p>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-green-600 font-semibold">{{ $item->city->name }}</span>
                                <div class="flex items-center space-x-1 text-gray-500">
                                    <span>{{ $item->ratings->count() }}</span>
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
