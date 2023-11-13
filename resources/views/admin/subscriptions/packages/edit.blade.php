@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <!-- if there are creation errors, they will show here -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            
        @endif
        <form action="{{ route('admin.subscriptions.packages.update',[$subscriptionId,$item->id]) }}" method="post" class="">
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
                    <h3 class="p-1">Fee</h3>
                    <input type="number" placeholder="Fee" name="fee" value="{{ $item->fee }}" class="w-full">
                    @error('fee')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Duration</h3>
                    <input type="number" placeholder="Duration" name="duration" value="{{ $item->duration }}"
                        class="w-full">
                    @error('duration')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Duration Unit</h3>
                    <select name="duration_unit" id="">
                        <option value="day" @if ($item->duration_unit == 'day') selected @endif>Day</option>
                        <option value="week" @if ($item->duration_unit == 'week') selected @endif>Week</option>
                        <option value="month" @if ($item->duration_unit == 'month') selected @endif>Month</option>
                        <option value="year" @if ($item->duration_unit == 'year') selected @endif>Year</option>
                    </select>
                    @error('duration_unit')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Trial</h3>
                    <input type="checkbox" name="trial" id="" @if ($item->trial) checked @endif>
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
