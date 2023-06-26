@extends('layouts.guest')
@section('body')
    <div>
        <div class="grid md:grid-cols-5 grid-cols-1 gap-4 p-4">
            @foreach ($deals as $item)
                <div class="shadow-xl p-4 rounded-lg">
                    <img src="{{ $item->media()->first()->path }}" class="h-56" alt="Image" srcset="">
                    <div class="flex space-x-1">
                        <img src="{{ $item->seller->image }}" class="w-8 h-8 rounded-sm" alt="" srcset="">
                        <div>
                            <div>{{ $item->seller->name }}</div>
                            <div class="text-sm">{{ $item->created_at }}</div>
                        </div>
                    </div>
                    <div class="flex space-x-1">
                        <div>{{ $item->type->crop->name }}</div>
                        <div>({{ $item->type->name }})</div>
                    </div>
                    <div>{{ $item->qty }} {{ $item->weight->name }}</div>
                    <div class="flex justify-between">
                        <div class="font-bold">RS.{{ $item->demand }} / {{ $item->weight->name }}</div>
                        <div class="flex space-x-1 bg-gray-200 rounded-2xl px-3 ">
                            <div>{{ $item->reactions->count() }}</div>
                            <span class="bi bi-hand-thumbs-up"></span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
