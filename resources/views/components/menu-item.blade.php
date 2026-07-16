@php
    $isActive = request()->url() === $url || request()->is(trim(parse_url($url, PHP_URL_PATH), '/'));
@endphp
<a href="{{ $url }}">
    <div
        class="p-1.5 my-0.5 flex items-center rounded-lg px-3 duration-300 cursor-pointer hover:bg-emerald-500/10 hover:text-emerald-400 text-slate-450 {{ $isActive ? 'bg-emerald-500/10 text-emerald-400 font-semibold' : '' }} transition-all">
        <i class="bi {{ $icon }} text-sm"></i>
        <span class="text-xs ml-2.5">{{ $title }}</span>
    </div>
</a>
