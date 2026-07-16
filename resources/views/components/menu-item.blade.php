@php
    $isActive = request()->url() === $url || request()->is(trim(parse_url($url, PHP_URL_PATH), '/'));
@endphp
<a href="{{ $url }}" class="block">
    <div
        class="p-2.5 my-1 flex items-center rounded-xl px-4 duration-300 cursor-pointer hover:bg-slate-800 hover:text-emerald-400 text-slate-400 {{ $isActive ? 'bg-slate-800 text-emerald-400 font-semibold shadow-sm' : '' }} transition-all">
        <i class="bi {{ $icon }} text-base"></i>
        <span class="text-sm ml-3">{{ $title }}</span>
    </div>
</a>
