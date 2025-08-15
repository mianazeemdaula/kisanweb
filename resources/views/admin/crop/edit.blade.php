@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('admin.crop.update', $crop) }}" method="POST" class="">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 p-2 gap-2 md:p-4">
                <div>
                    <h3 class="p-1">Name</h3>
                    <input type="text" placeholder="Name" name="name" value="{{ $crop->name }}" class="w-80">
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Name Urdu</h3>
                    <input type="text" placeholder="Name Urdu" name="name_ur" value="{{ $crop->name_ur }}"
                        class="w-80">
                    @error('name_ur')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Icon</h3>
                    <input type="text" placeholder="Icon" name="icon" value="{{ $crop->icon }}" class="w-80">
                    @error('icon')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Color</h3>
                    <input type="text" placeholder="Color" name="color" value="{{ $crop->color }}" class="w-80">
                    @error('color')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Active</h3>
                    <input type="checkbox" name="active" value="1" {{ $crop->active ? 'checked' : '' }}>
                </div>
                <div>
                    <h3 class="p-1">Sort</h3>
                    <input type="number" name="sort" class="w-80" value="{{ $crop->sort }}">
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-xl">Update</button>
        </form>
    </div>
@endsection
