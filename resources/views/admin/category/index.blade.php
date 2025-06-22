@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between">
            <h5 class="">Categories</h5>
            <a href="{{ route('admin.category.create') }}">
                <div class="px-4 bg-green-700 text-white rounded-xl">
                    Add Category
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
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name (Urdu)
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 " id="chatlist">
                        @foreach ($cats as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                    {{ $item->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                    <div>
                                        {{ $item->name }}
                                    </div>
                                    <div>
                                        @foreach ($item->categories as $cat)
                                            <a href="{{ route('admin.category.sub.index', $cat->id) }}">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $cat->name }}
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->name_ur }} </td>
                                <td>
                                    <div class="flex space-x-3">

                                        <a href="{{ route('admin.cities.show', $item->id) }}">
                                            <span class="bi bi-eye"></span>
                                        </a>
                                        <a href="{{ route('admin.cities.edit', $item->id) }}">
                                            <span class="bi bi-pencil"></span>
                                        </a>
                                        <form action="{{ route('admin.cities.destroy', $item->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"><span class="bi bi-trash"></span></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            <x-web-pagination :paginator="$cats" />
        </div>
    </div>
@endsection
