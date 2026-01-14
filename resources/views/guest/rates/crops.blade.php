@extends('layouts.guest')
@section('body')
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="font-display text-5xl font-bold text-gray-900 mb-4 tracking-tight">Market Crop Rates</h1>
                <p class="text-gray-600 text-lg font-light max-w-2xl mx-auto">
                    Browse our comprehensive collection of crops and their varieties with real-time market rates
                </p>
            </div>

            <!-- Crops Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach (\App\Models\Crop::orderBy('sort')->get() as $item)
                    <div
                        class="group relative bg-white rounded-2xl shadow-sm border-2 border-gray-100 hover:border-green-400 transition-all duration-300 overflow-hidden hover-lift">
                        <!-- Color accent bar -->
                        @if ($item->color)
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-green-400 to-green-600"
                                style="background: {{ $item->color }};"></div>
                        @endif

                        <div class="p-6">
                            <!-- Icon Section -->
                            @if ($item->icon)
                                <div
                                    class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <img src="{{ $item->icon }}" alt="{{ $item->name }}"
                                        class="w-10 h-10 object-contain">
                                </div>
                            @else
                                <div
                                    class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Crop Name -->
                            <div class="text-center mb-3">
                                <h3 class="font-bold text-lg text-gray-900 group-hover:text-green-600 transition-colors">
                                    {{ $item->name }}
                                </h3>
                                @if ($item->name_ur)
                                    <p class="text-sm text-gray-500 font-light mt-1">{{ $item->name_ur }}</p>
                                @endif
                            </div>

                            <!-- Types Section -->
                            @if ($item->types->count() > 0)
                                <div class="border-t border-gray-100 pt-3 mt-3">
                                    <div class="flex items-center justify-center mb-2">
                                        <span
                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Varieties</span>
                                        <span
                                            class="ml-2 bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">
                                            {{ $item->types->count() }}
                                        </span>
                                    </div>
                                    <div
                                        class="space-y-1.5 max-h-32 overflow-y-auto scrollbar-thin scrollbar-thumb-green-200 scrollbar-track-gray-100">
                                        @foreach ($item->types as $type)
                                            <div class="flex items-center justify-center">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 hover:bg-green-100 transition-colors">
                                                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $type->name }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center pt-3 mt-3 border-t border-gray-100">
                                    <span class="text-xs text-gray-400 italic">No varieties available</span>
                                </div>
                            @endif

                            <!-- Hover Effect Overlay -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-green-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none rounded-2xl">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            @if (\App\Models\Crop::count() === 0)
                <div class="text-center py-20">
                    <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">No crops available</h3>
                    <p class="mt-2 text-sm text-gray-500">Check back later for updated crop rates.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Custom scrollbar for varieties list */
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #bbf7d0;
            border-radius: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #86efac;
        }
    </style>
@endsection
