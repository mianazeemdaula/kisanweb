@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('admin.crop.store') }}" method="POST" class="">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 p-2 gap-2 md:p-4">
                <div>
                    <h3 class="p-1">Name</h3>
                    <input type="text" placeholder="Name" name="name" value="{{ old('name') }}" class="w-80">
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Name Urdu</h3>
                    <input type="text" placeholder="Name Urdu" name="name_ur" value="{{ old('name_ur') }}"
                        class="w-80">
                    @error('name_ur')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Icon</h3>
                    <input type="text" placeholder="Icon" name="icon" value="{{ old('icon') }}" class="w-80">
                    @error('icon')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Color</h3>
                    <input type="text" placeholder="Color" name="color" value="{{ old('color') }}" class="w-80">
                    @error('color')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Active</h3>
                    <input type="checkbox" name="active" value="1" checked>
                </div>
                <div>
                    <h3 class="p-1">Sort</h3>
                    <input type="number" name="sort" class="w-80" value="1">
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-xl">Save</button>
        </form>
    </div>
@endsection
