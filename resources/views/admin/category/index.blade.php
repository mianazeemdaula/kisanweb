@extends('layouts.admin')
@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Categories Management</h1>
                <p class="text-sm text-slate-400 mt-1">Manage main categories, child categories, and their subcategories.</p>
            </div>
            <a href="{{ route('admin.category.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                <span class="bi bi-plus-lg"></span> Add Category
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
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Subcategories</th>
                            <th scope="col" class="px-6 py-4 class-left text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
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
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    <div class="flex flex-col gap-2.5">
                                        <!-- Subcategories of main category -->
                                        <div>
                                            <a href="{{ route('admin.category.sub.index', $item->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100/80 px-2.5 py-1 rounded-full transition-all">
                                                <span class="bi bi-gear-fill"></span> Manage ({{ $item->subcategories->count() }})
                                            </a>
                                        </div>
                                        <!-- Child Categories -->
                                        @if($item->categories->isNotEmpty())
                                            <div class="flex flex-wrap gap-1.5 mt-1 items-center">
                                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mr-1">Child Categories:</span>
                                                @foreach ($item->categories as $cat)
                                                    <a href="{{ route('admin.category.sub.index', $cat->id) }}" class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100/80 px-2 py-0.5 rounded-md transition-all" title="Manage subcategories for {{ $cat->name }}">
                                                        {{ $cat->name }} <span class="text-[9px] bg-blue-100 text-blue-800 px-1 rounded">{{ $cat->subcategories->count() }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.category.edit', $item->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-150 text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all" title="Edit Category">
                                            <span class="bi bi-pencil"></span>
                                        </a>
                                        <form action="{{ route('admin.category.destroy', $item->id) }}" method="post" class="inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center border border-red-100 text-red-400 hover:text-red-650 hover:bg-red-50 transition-all" title="Delete Category" onclick="return confirm('Are you sure you want to delete this category? It will delete all subcategories and child categories!')">
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
