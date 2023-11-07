@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('admin.ads.store') }}" method="post" class="">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-1 p-2 gap-2 md:p-4 items-end">
                <div>
                    <h3 class="p-1">Title</h3>
                    <input type="text" placeholder="Title" name="title" value="{{ old('title') }}" class="w-80">
                    @error('title')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Title Urdu</h3>
                    <input type="text" placeholder="Title Urdu" name="title_ur" value="{{ old('title_ur') }}" class="w-80">
                    @error('title_ur')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Type</h3>
                    <select name="action" id="" class="w-80">
                        <option value="whatsapp">Whatsapp</option>
                        <option value="link">Web Link</option>
                        <option value="deal">Deal</option>
                        <option value="shop">Shop</option>
                    </select>
                    @error('action')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Link</h3>
                    <input type="text" placeholder="Link" name="link" value="{{ old('link') }}" class="w-80">
                    @error('link')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Start Date</h3>
                    <input type="date" placeholder="Start Date" name="start_date" value="{{ old('start_date') }}" class="w-80">
                    @error('start_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">End Date</h3>
                    <input type="date" placeholder="End Date" name="end_date" value="{{ old('end_date') }}" class="w-80">
                    @error('end_date')
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
