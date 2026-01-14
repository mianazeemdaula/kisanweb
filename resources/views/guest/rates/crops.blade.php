@extends('layouts.guest')
@section('body')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Crop Rates</h1>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach (\App\Models\Crop::orderBy('sort')->get() as $item)
                <div
                    class="bg-white border border-green-200 hover:border-green-400 rounded-lg p-4 text-center transition-all hover:shadow-md">
                    <div class="font-medium text-gray-900">{{ $item->name }}</div>
                    @if ($item->types->count() > 0)
                        <div class="mt-2 text-xs text-gray-500 space-y-1">
                            @foreach ($item->types as $type)
                                <div class="text-green-600">{{ $type->name }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
