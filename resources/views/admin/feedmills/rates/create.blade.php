@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('admin.feedmillsrate.store') }}" method="post" class="">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 p-2 gap-2 md:p-4">
                <div>
                    <h3 class="p-1">Mills</h3>
                    <select name="feed_mill_id" id="" class="w-80">
                        @foreach ($mills as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->city->name }})</option>
                        @endforeach
                    </select>
                    @error('feed_mill_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror

                </div>
                <div>
                    <h3 class="p-1">Price</h3>
                    <input type="number" placeholder="Price" name="price" value="{{ old('price') }}" class="w-80">
                    @error('price')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                {{-- <div>
                    <h3 class="p-1">Maximum</h3>
                    <input type="number" placeholder="Maximum" name="max" value="{{ old('max') }}" class="w-80">
                    @error('max')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div> --}}
                <div>
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Send
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
