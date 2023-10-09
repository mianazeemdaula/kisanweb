@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ url('/admin/send-group-message') }}" method="post" class="" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-1 p-2 gap-2
            md:p-4 items-center">
                <div>
                    <div class="flex justify-between">
                        <h3 class="p-1">Groups</h3>
                        <div class="flex space-x-2">
                            <div id="checkAll" class="cursor-pointer bg-blue-500 rounded p-1 text-white">
                                Select All
                            </div>
                            <div>
                                <a href="{{ url('admin/del-wa-group-file') }}">Delete Cache</a>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach ($groups as $item)
                            <div class="text-xs">
                                <input type="checkbox" name="to[]" value="{{ $item['id'] }}"> {{ $item['name'] }}
                            </div>
                        @endforeach
                    </div>
                    @error('to')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror

                </div>
                <div>
                    <h3 class="p-1">Message</h3>
                    <textarea placeholder="Message" name="text" value="{{ old('text') }}" class="w-full" rows="5" required></textarea>
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

@section('js')
    <script type="module">
        $("#checkAll").click(function() {
            console.log('clicked');
            $("input:checkbox").prop('checked', true);
        });
    </script>
@endsection
