<a href="{{ $url }}">
    <div
        class="p-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 {{ $active == 'true' ? 'bg-blue-600' : '' }}">
        <i class="bi {{ $icon }}"></i>
        <span class="text-[15px] ml-4 text-gray-200">{{ $title }} </span>
    </div>
</a>
