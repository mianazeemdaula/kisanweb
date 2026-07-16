<div class="flex items-center p-6 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
    <div class="{{ $color }} p-3.5 rounded-xl text-white shadow-sm flex justify-center items-center">
        {{ $slot }}
    </div>
    <div class="ml-4">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">{{ $title }}</p>
        <span class="text-2xl font-bold text-slate-850 tracking-tight">{{ $count }}</span>
    </div>
</div>
