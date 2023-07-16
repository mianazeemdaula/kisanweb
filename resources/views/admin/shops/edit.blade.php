@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('shops.update', $item->id) }}" method="post" class="">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 p-2 gap-2 md:p-4 items-end">
                <div>
                    <h3 class="p-1">Name</h3>
                    <input type="text" placeholder="Name" name="name" value="{{ $item->name }}" class="w-full">
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">About</h3>
                    <input type="text" placeholder="About" name="about" value="{{ $item->about }}" class="w-full">
                    @error('about')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Shop #</h3>
                    <input type="text" placeholder="Shop Number" name="shop_number" value="{{ $item->shop_number }}"
                        class="w-full">
                    @error('shop_number')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
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
