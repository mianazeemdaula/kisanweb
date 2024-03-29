@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between">
            <h5 class="">Shops</h5>
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
                                Trial</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duration
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Unit
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fee
                            </th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->name }} </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <form action="{{ url("admin/shop-stauts/$item->id") }}" method="post">
                                        @csrf
                                        <button class="bg-gray-200 px-2 rounded-xl" type="submit"
                                            class="">{{ $item->trial ? 'Trial' : '' }}</button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">{{ $item->duration }}</td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">{{ $item->duration_unit }}
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">{{ $item->fee }}
                                </td>
                                <td>
                                    <div class="flex space-x-3">

                                        <a href="{{ route('admin.subscriptions.packages.show', [$subscriptionId,$item->id]) }}">
                                            <span class="bi bi-eye"></span>
                                        </a>
                                        <a href="{{ route('admin.subscriptions.packages.edit', [$subscriptionId,$item->id]) }}">
                                            <span class="bi bi-pencil"></span>
                                        </a>
                                        {{-- <form action="{{ route('admin.subscriptions.destroy', $item->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"><span class="bi bi-trash"></span></button>
                                        </form> --}}
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
