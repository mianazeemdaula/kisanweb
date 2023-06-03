@extends('layouts.admin')
@section('content')
    <form action="{{ url('/news-send') }}" method="post" class="max-w-lg mx-auto">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="title">
                Title
            </label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="title" type="text" placeholder="Enter a title">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2" for="body">
                Body
            </label>
            <textarea
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="body" placeholder="Enter the body text"></textarea>
        </div>
        <div class="flex justify-end">
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                type="submit">
                Send
            </button>
        </div>

    </form>
@endsection
