@extends('layouts.guest')
@section('body')
    <div class="p-4 grid grid-cols-5 gap-3">
        @foreach (\App\Models\Crop::orderBy('sort')->get() as $item)
            <div class="bg-green-400 text-center h-20 rounded-md flex justify-center items-center">
                {{ $item->name }}
            </div>
        @endforeach
    </div>
@endsection
