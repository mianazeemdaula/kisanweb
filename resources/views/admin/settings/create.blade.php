@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('admin.settings.store') }}" method="post" class="">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 p-2 gap-2 md:p-4 items-end">
                <div>
                    <h3 class="p-1">Name</h3>
                    <input placeholder="Name or Key" name="name" value="{{ old('name') }}" class="w-full">
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Type</h3>
                    <select name="type" id="" class="w-full">
                        <option value="integer">Integer</option>
                        <option value="double">Double</option>
                        <option value="string">String</option>
                        <option value="boolean">Boolean</option>
                    </select>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Default</h3>
                    <input placeholder="default" name="default" value="{{ old('default') }}" class="w-full">
                    @error('default')
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
