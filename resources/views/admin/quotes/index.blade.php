@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between">
            <h5 class="">Quotoes</h5>
            <a href="{{ route('quotes.create') }}">
                <div class="px-4 bg-green-700 text-white rounded-xl">
                    Add Quote
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
                                Qutoe</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 " id="chatlist">
                        @foreach ($quotes as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                    {{ $item->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
                                    {{ $item->quote }}
                                </td>
                                <td>
                                    <div class="flex space-x-3">

                                        <a href="{{ route('quotes.show', $item->id) }}">
                                            <span class="bi bi-eye"></span>
                                        </a>
                                        <a href="{{ route('quotes.edit', $item->id) }}">
                                            <span class="bi bi-pencil"></span>
                                        </a>
                                        <form action="{{ route('quotes.destroy', $item->id) }}" method="post">
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
            <x-web-pagination :paginator="$quotes" />
        </div>
    </div>
@endsection
