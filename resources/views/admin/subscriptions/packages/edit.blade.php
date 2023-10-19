@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('admin.subscriptions.update', $item->id) }}" method="post" class="">
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
                    <h3 class="p-1">Name Urdu</h3>
                    <input type="text" placeholder="Name Urdu" name="name_ur" value="{{ $item->name_ur }}"
                        class="w-full">
                    @error('name_ur')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Description</h3>
                    <textarea name="description" id="" class="w-full" cols="30" rows="10">{{ $item->description }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Description Urdu</h3>
                    <textarea name="description_ur" id="" class="w-full" cols="30" rows="10">{{ $item->description_ur }}</textarea>
                    @error('description_ur')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Type</h3>
                    <select name="type" id="" class="w-full">
                        <option value="whatsapp" {{ $item->type == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="email" {{ $item->type == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Active</h3>
                    <input type="checkbox" name="active" id="" @if ($item->active) checked @endif>
                    @error('type')
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
