@extends('layouts.admin')
@section('content')
    <div class="bg-white rounded-lg">
        <form action="{{ route('quotes.update', $quote->id) }}" method="post" class="">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-1 p-2 gap-2 md:p-4 items-end">
                <div>
                    <h3 class="p-1">Quote</h3>
                    <textarea placeholder="Quote" name="quote" cols="100">{{ $quote->quote }}</textarea>
                    @error('quote')
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
