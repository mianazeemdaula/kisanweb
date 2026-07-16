@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Category Deals Management</h1>
                <p class="text-sm text-slate-400 mt-1">Manage all category-specific deals published by users.</p>
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Username</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Category & Subcategory</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Demand</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Qty</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white" id="chatlist">
                        @foreach ($deals as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-500">
                                    #{{ $item->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">
                                    {{ $item->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    <span class="font-semibold text-slate-800">{{ $item->subcategory->category->name ?? 'N/A' }}</span>
                                    <span class="text-xs text-slate-400 block mt-0.5">{{ $item->subcategory->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600">
                                    Rs. {{ number_format($item->demand) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $item->qty }} <span class="text-slate-400 text-xs font-medium">{{ $item->weight->name ?? '' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.category-deals.show', $item->id) }}" target="_blank" class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-150 text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all" title="View Deal">
                                            <span class="bi bi-eye"></span>
                                        </a>
                                        <a href="{{ route('admin.category-deals.edit', $item->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-150 text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all" title="Edit Deal">
                                            <span class="bi bi-pencil"></span>
                                        </a>
                                        <form action="{{ route('admin.category-deals.destroy', $item->id) }}" method="post" class="inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center border border-red-100 text-red-400 hover:text-red-650 hover:bg-red-50 transition-all" title="Delete Deal" onclick="return confirm('Are you sure you want to delete this category deal?')">
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
            <x-web-pagination :paginator="$deals" />
        </div>
    </div>
@endsection
