@extends('layouts.guest')
@section('body')
    <div>
        <div class="grid md:grid-cols-5 grid-cols-1 gap-4 p-4">
            @foreach ($deals as $item)
                <div class="shadow-xl p-4 rounded-lg hover:shadow-sm bg-gray-50">
                    <a href="{{ url("/deals/$item->id") }}">
                        <img src="{{ str_replace("http://127.0.0.1:8000","https://kisanstock.com",$item->media()->first()->path) }}" class="w-full h-40 object-cover rounded" alt="Image"
                            srcset="">
                    </a>
                    <div class="flex space-x-1 mt-2">
                        <img src="{{ $item->seller->image }}" class="w-8 h-8 rounded-md object-cover" alt=""
                            srcset="">
                        <div>
                            <div class="font-bold">{{ $item->seller->name }}</div>
                            <div class="font-thin text-xs">{{ $item->created_at }}</div>
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
