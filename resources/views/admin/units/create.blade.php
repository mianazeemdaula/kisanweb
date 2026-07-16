@extends('layouts.admin')
@section('content')
    <div class="w-full max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.units.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-450 hover:text-slate-600 transition-colors mb-2">
                <span class="bi bi-arrow-left"></span> Back to Units
            </a>
            <h1 class="text-2xl font-bold text-slate-800">Add New Unit</h1>
            <p class="text-sm text-slate-400 mt-1">Create a new measurement unit for weight calculations.</p>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
            <form action="{{ route('admin.units.store') }}" method="post">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Key (Lowercase Code)</label>
                        <input type="text" placeholder="e.g., kg, maund" name="key" value="{{ old('key') }}" class="w-full">
                        @error('key')
                            <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Name (English)</label>
                        <input type="text" placeholder="e.g., Kilogram" name="name" value="{{ old('name') }}" class="w-full">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Name (Urdu)</label>
                        <input type="text" placeholder="e.g., کلو گرام" name="name_ur" value="{{ old('name_ur') }}" class="w-full text-right" dir="rtl">
                        @error('name_ur')
                            <p class="text-red-500 text-xs mt-1.5 font-semibold text-left">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100 justify-end">
                    <a href="{{ route('admin.units.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-xs transition-colors">
                        Cancel
                    </a>
                    <button class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold shadow-sm hover:shadow hover:-translate-y-0.5 transition-all duration-200 rounded-xl px-5 py-2.5 border-none cursor-pointer text-xs" type="submit">
                        Save Unit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
