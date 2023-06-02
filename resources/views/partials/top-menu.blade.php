<div class="p-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <span class="bi bi-app-indicator mr-4"></span>
            <div class="flex bg-white rounded-3xl p-1">
                @for ($i = 0; $i < 5; $i++)
                    <div class="px-2">
                        {{ Str::random(5) }}
                        @if ($i < 4)
                            <span class="bi bi-arrow-right"></span>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
        <img src="https://ui-avatars.com/api/?name=John+Doe" alt="" srcset="" class="rounded-full w-10 h-10">
    </div>
    <div class="border-b-2 my-2"></div>
</div>
