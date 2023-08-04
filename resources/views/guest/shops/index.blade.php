@extends('layouts.guest')
@section('body')
    <div>
        <div class="grid md:grid-cols-5 grid-cols-1 gap-4 p-4">
            @foreach ($shops as $item)
                <div class="shadow-xl p-4 rounded-lg hover:shadow-sm">
                    <a href="{{ url("/commission-shops/$item->id") }}">
                        <img src="{{ $item->banner }}" class="h-56 w-full object-contain" alt="Image" srcset="">
                    </a>
                    <div class="flex space-x-1 mt-2">
                        <img src="{{ $item->logo }}" class="w-8 h-8 rounded-md object-cover" alt="" srcset="">
                        <div>
                            <div class="font-bold">{{ $item->name }}</div>
                            <div class="font-thin text-xs">{{ $item->created_at }}</div>
                        </div>
                    </div>
                    <div>{{ $item->about }}</div>
                    <div class="flex justify-between">
                        <div class="font-bold">{{ $item->city->name }}</div>
                        <div class="flex space-x-1 bg-gray-200 rounded-2xl px-3 ">
                            <div>{{ $item->ratings->count() }}</div>
                            <span class="bi bi-hand-thumbs-up"></span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
