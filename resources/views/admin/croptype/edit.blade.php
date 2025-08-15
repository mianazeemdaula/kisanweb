@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('admin.croptype.update', $croptype) }}" method="POST" class="">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 p-2 gap-2 md:p-4">
                <div>
                    <h3 class="p-1">Crop</h3>
                    <select name="crop_id" class="w-80" required>
                        @foreach ($crops as $crop)
                            <option value="{{ $crop->id }}" {{ $croptype->crop_id == $crop->id ? 'selected' : '' }}>
                                {{ $crop->name }}</option>
                        @endforeach
                    </select>
                    @error('crop_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Name</h3>
                    <input type="text" placeholder="Name" name="name" value="{{ $croptype->name }}" class="w-80">
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Code</h3>
                    <input type="text" placeholder="Code" name="code" value="{{ $croptype->code }}" class="w-80">
                    @error('code')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Sort</h3>
                    <input type="number" name="sort" class="w-80" value="{{ $croptype->sort }}">
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-xl">Update</button>
        </form>
    </div>
@endsection
