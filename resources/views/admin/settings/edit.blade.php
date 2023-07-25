@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('admin.settings.update', $model->id) }}" method="post" class="">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-1 p-2 gap-2 md:p-4 items-end">
                <div>
                    <h3 class="p-1">Name</h3>
                    <input placeholder="Name or Key" name="name" value="{{ $model->name }}" class="w-full">
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Type</h3>
                    <select name="type" id="" class="w-full">
                        <option value="integer" @if ($model->type == 'integer') selected @endif>Integer</option>
                        <option value="double" @if ($model->type == 'double') selected @endif>Double</option>
                        <option value="string" @if ($model->type == 'string') selected @endif>String</option>
                        <option value="boolean" @if ($model->type == 'boolean') selected @endif>Boolean</option>
                    </select>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Default</h3>
                    <input placeholder="default" name="default" value="{{ $model->default }}" class="w-full">
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
