@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between">
            <h5 class="">Settings</h5>
            <a href="{{ route('admin.settings.create') }}">
                <div class="px-4 bg-green-700 text-white rounded-xl">
                    Add Setting
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
                                Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Default</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Enable</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 " id="chatlist">
                        @foreach ($collection as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                    {{ $item->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                    {{ $item->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                    {{ $item->type }}
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                    {{ $item->default }}
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                    {{ $item->is_enabled }}
                                </td>
                                <td>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('admin.settings.show', $item->id) }}">
                                            <span class="bi bi-eye"></span>
                                        </a>
                                        <a href="{{ route('admin.settings.edit', $item->id) }}">
                                            <span class="bi bi-pencil"></span>
                                        </a>
                                        <form action="{{ route('admin.settings.destroy', $item->id) }}" method="post">
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
            <x-web-pagination :paginator="$collection" />
        </div>
    </div>
@endsection
