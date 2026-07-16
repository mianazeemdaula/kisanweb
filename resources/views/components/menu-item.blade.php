@php
    $isActive = request()->url() === $url || request()->is(trim(parse_url($url, PHP_URL_PATH), '/'));
@endphp
<a href="{{ $url }}">
    <div
        class="p-2.5 my-1.5 flex items-center rounded-xl px-4 duration-300 cursor-pointer hover:bg-emerald-500/10 hover:text-emerald-400 text-slate-400 {{ $isActive ? 'bg-emerald-500/10 text-emerald-400 font-semibold' : '' }} transition-all">
        <i class="bi {{ $icon }} text-lg"></i>
        <span class="text-sm ml-3.5">{{ $title }}</span>
    </div>
</a>
