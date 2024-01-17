@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between">
            <h5 class="">{{ $support->title }}</h5>
            <form action="{{ route('admin.support.update', $support->id) }}" method="post">
                @csrf
                @method('put')
                <button type="submit" class="px-4 bg-green-700 text-white rounded-xl">Close Chat</button>
            </form>
        </div>  
        <div class="mx-auto bg-white rounded shadow-md p-6 mt-4">
            <!-- Chat messages container -->
            <div class="mb-4 overflow-y-auto">
                @foreach ($collection as $item)

                    @if($item->user_id == $support->user_id)
                        <!-- Sample received message -->
                        <div class="flex items-start mb-2">
                            <div class="flex-shrink-0">
                            <img src="https://placekitten.com/40/40" alt="User Avatar" class="w-8 h-8 rounded-full">
                            </div>
                            <div class="ml-3 bg-blue-100 p-2 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $item->content }}</p>
                            </div>
                        </div>
                    @else
                        <!-- Sample sent message -->
                        <div class="flex items-end mb-2">
                            <div class="mr-3 flex-shrink-0">
                            <img src="https://placekitten.com/40/40" alt="User Avatar" class="w-8 h-8 rounded-full">
                            </div>
                            <div class="bg-green-100 p-2 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $item->content }}</p>
                            </div>
                        </div>
                    @endif
                    
                @endforeach
            </div>
        
            <!-- Input field and send button -->
            <form action="{{ route('admin.support.chat.store', $support->id) }}" method="post">
                @csrf
                <div class="flex items-center">
                    <input name="message" type="text" placeholder="Type your message..." class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring focus:border-blue-300">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">Send</button>
                  </div>
            </form>
          </div>
    </div>
@endsection
