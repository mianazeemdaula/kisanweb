@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between">
            <h5 class="">Pendings for Subscription</h5>
        </div>

        <div class="bg-white">
            <div class="overflow-x-auto mt-6 ">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Package</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trial</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duration
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Screenshot
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 " id="chatlist">
                        @foreach ($collection as $package)
                            @foreach ($package->users as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                        {{ $package->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->name }} </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $package->trial ? 'Trial' : '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                        {{ $item->pivot->start_date }}<br />
                                        {{ $item->pivot->end_date }}</td>
                                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                        {{ $item->pivot->contact }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                        <a href="{{ asset($item->pivot->screenshot) }}" target="_blank">Screenshot</a>
                                    </td>
                                    <td>
                                        <div class="flex space-x-3">

                                            <form action="{{ route('admin.pending-subscriptions.update', $item->id) }}"
                                                method="post">
                                                @csrf
                                                @method('put')
                                                <input type="hidden" name="user_id" value="{{ $item->pivot->user_id }}">
                                                <input type="hidden" name="status" value="accept">
                                                <button type="submit"><span class="bi bi-check-circle"></span></button>
                                            </form>
                                            <form action="{{ route('admin.pending-subscriptions.destroy', $item->id) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit"><span class="bi bi-dash-circle"></span></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
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
