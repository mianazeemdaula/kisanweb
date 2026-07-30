@extends('layouts.admin')
@section('content')
    <div class="max-w-4xl bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Edit Feed</h1>
            <p class="text-sm text-slate-400 mt-1">Update the content for feed post #{{ $feed->id }}</p>
        </div>
        <form action="{{ route('admin.feeds.update', $feed->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Feed Content</label>
                    <textarea placeholder="Write feed content..." name="content" rows="6" class="w-full rounded-xl border border-slate-200 p-4 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">{{ old('content', $feed->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl transition-all shadow-sm"
                        type="submit">
                        Update Feed
                    </button>
                    <a href="{{ route('admin.feeds.index') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm rounded-xl transition-all">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
