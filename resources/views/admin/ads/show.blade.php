@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between">
            <h5 class="">Advertisement</h5>
            <a href="{{ route('admin.ads.create') }}">
                <div class="px-4 bg-green-700 text-white rounded-xl">
                    Add Ad
                </div>
            </a>
        </div>
        <div class="bg-white">
            <div class="overflow-x-auto mt-6 ">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Link</th>

                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Expire On</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Active</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Image</th>
                                
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 " id="chatlist">
                        <tr>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                {{ $ad->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                {{ $ad->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                {{ $ad->action }} 
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                @if($ad->action == 'whatsapp')
                                    <a href="https://wa.me/{{ $ad->link }}" target="_blank">{{ $ad->link }}</a>
                                @elseif($ad->action == 'link')
                                    <a href="{{ $ad->link }}" target="_blank">Link</a>
                                @elseif($ad->action == 'deal')
                                    <a href="{{ route('deal', $ad->link) }}" target="_blank">Deal</a>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                {{ $ad->end_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                {{ $ad->active }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                <img src="{{ asset($ad->image) }}" alt="" class="w-20 h-20">
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                <a href="{{ route('admin.ads.edit', $ad->id) }}" class="text-blue-500">Edit</a>
                                <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="post" class="inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="text-red-500">Delete</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection