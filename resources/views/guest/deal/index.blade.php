@extends('layouts.guest')
@section('body')
    <div>
        <div class="grid md:grid-cols-4 grid-cols-1 gap-4 p-4">
            @foreach ($deals as $item)
                <div class="shadow-xl p-4 rounded-lg">
                    <img src="{{ $item->media()->first()->path }}" alt="" srcset="">
                    <div>{{ $item->type->crop->name }}</div>
                    <div>{{ $item->qty }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
