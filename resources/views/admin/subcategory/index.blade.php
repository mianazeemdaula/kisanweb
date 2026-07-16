@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-400">
                    <a href="{{ route('admin.category.index') }}" class="hover:text-emerald-600 font-medium transition-colors">Categories</a>
                    <span class="bi bi-chevron-right text-[10px]"></span>
                    <span class="text-slate-650 font-semibold">{{ $category->name }}</span>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 mt-2">Subcategories of {{ $category->name }}</h1>
                <p class="text-sm text-slate-400 mt-1">Manage subcategories belonging to this category.</p>
            </div>
            <a href="{{ route('admin.category.sub.create', $category->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <span class="bi bi-plus-lg"></span> Add Subcategory
            </a>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Name (English)</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Name (Urdu)</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Icon</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white" id="chatlist">
                        @foreach ($cats as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-500">
                                    #{{ $item->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 font-bold">
                                    {{ $item->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-650 font-medium">
                                    {{ $item->name_ur }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    @if($item->icon)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-50 border border-slate-100 rounded-lg text-slate-600 text-xs">
                                            <i class="bi {{ $item->icon }} text-sm"></i> {{ $item->icon }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">None</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.category.sub.edit', [$category->id, $item->id]) }}" class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-150 text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all" title="Edit Subcategory">
                                            <span class="bi bi-pencil"></span>
                                        </a>
                                        <form action="{{ route('admin.category.sub.destroy', [$category->id, $item->id]) }}" method="post" class="inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center border border-red-100 text-red-400 hover:text-red-650 hover:bg-red-50 transition-all" title="Delete Subcategory" onclick="return confirm('Are you sure you want to delete this subcategory?')">
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
            <x-web-pagination :paginator="$cats" />
        </div>
    </div>
@endsection
