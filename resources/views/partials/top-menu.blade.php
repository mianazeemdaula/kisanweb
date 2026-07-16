<div class="px-6 py-4 bg-white border-b border-slate-100 sticky top-0 z-40 backdrop-blur-md bg-white/90">
    <div class="flex items-center justify-between">
        <div class="md:flex items-center hidden">
            <span class="bi bi-compass mr-3.5 text-slate-400 text-lg"></span>
            <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 rounded-full px-4 py-1.5 text-xs font-semibold text-slate-600 shadow-sm">
                <?php $segments = ''; ?>
                @foreach (Request::segments() as $segment)
                    <?php $segments .= '/' . $segment; ?>
                    @if ($loop->last)
                        <span class="text-slate-800 capitalize">{{ str_replace('-', ' ', $segment) }}</span>
                    @else
                        <span class="text-slate-400 capitalize">{{ str_replace('-', ' ', $segment) }}</span>
                        <span class="bi bi-chevron-right text-[10px] text-slate-300"></span>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <div class="text-sm font-bold text-slate-850">{{ auth()->user()->name ?? 'Administrator' }}</div>
                <div class="text-xs text-slate-400">Admin Account</div>
            </div>
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=10b981&color=fff" alt="Avatar"
                class="rounded-full w-10 h-10 border border-emerald-100 shadow-sm">
        </div>
    </div>
</div>
