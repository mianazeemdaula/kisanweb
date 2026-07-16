@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Units Management</h1>
                <p class="text-sm text-slate-400 mt-1">Manage measurement units used for crops and deals.</p>
            </div>
            <a href="{{ route('admin.units.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <span class="bi bi-plus-lg"></span> Add Unit
            </a>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Key</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Name (English)</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Name (Urdu)</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white" id="chatlist">
                        @foreach ($units as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-500">
                                    #{{ $item->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-semibold uppercase">
                                    {{ $item->key }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 font-bold">
                                    {{ $item->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-650 font-medium">
                                    {{ $item->name_ur }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.units.edit', $item->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-150 text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all" title="Edit Unit">
                                            <span class="bi bi-pencil"></span>
                                        </a>
                                        <form action="{{ route('admin.units.destroy', $item->id) }}" method="post" class="inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center border border-red-100 text-red-400 hover:text-red-650 hover:bg-red-50 transition-all" title="Delete Unit" onclick="return confirm('Are you sure you want to delete this unit?')">
                                                <span class="bi bi-trash"></span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">
            <x-web-pagination :paginator="$units" />
        </div>
    </div>
@endsection
