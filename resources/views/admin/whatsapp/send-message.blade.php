@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ url('/admin/send-message') }}" method="post" class="" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-1 p-2 gap-2
            md:p-4 items-center">
                <div>
                    <h3 class="p-1">Phone Number</h3>
                    <input type="text" placeholder="Phone Number" name="to" value="{{ old('to') }}" class="w-80">
                    @error('to')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror

                </div>
                <div>
                    <h3 class="p-1">Message</h3>
                    <textarea placeholder="Message" name="text" value="{{ old('text') }}" class="w-80"></textarea>
                    @error('text')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h3 class="p-1">Media</h3>
                    <input type="file" name="media" value="{{ old('media') }}" class="w-80">
                    @error('media')
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
