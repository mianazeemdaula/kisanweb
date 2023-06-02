<div class="flex shadow-lg p-6 rounded-xl w-30 bg-white">
    <div class="{{ $color }} p-2 rounded-lg w-12 h-12 flex justify-center items-center">
        {{ $slot }}
    </div>
    <div class="ml-4">
        <p class="text-gray-400">{{ $title }}</p>
        <span class="font-medium">{{ $count }}</span>
    </div>
</div>
