@extends('layouts.guest')
@section('title')
    {{ $feed->user->name }}: {{ \Illuminate\Support\Str::limit($feed->content, 60) }} | Digital Mandi
@endsection
@section('meta')
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url('feeds/' . $feed->id) }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($feed->content, 160) }}">
    @php($ogImage = $feed->media->first(fn($m) => strtolower($m->ext) !== 'mp4'))
    @if ($ogImage)
        <meta property="og:image" content="{{ str_replace('http://127.0.0.1:8000', 'https://digitalmandi.online', $ogImage->path) }}">
    @endif
@endsection
@section('body')
    <div class="bg-mesh min-h-screen py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-green-600 transition-base">Home</a>
                <span class="bi bi-chevron-right text-xs"></span>
                <span class="text-gray-700 font-medium">Post</span>
            </nav>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Author -->
                <div class="flex items-center gap-3 p-5 sm:p-6">
                    <img src="{{ $feed->user->image }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-green-100" alt="">
                    <div>
                        <div class="font-bold text-gray-900">{{ $feed->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $feed->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                @if ($feed->content)
                    <p dir="auto" class="px-5 sm:px-6 pb-5 text-gray-800 leading-relaxed" style="white-space: pre-line; overflow-wrap: anywhere;">{{ $feed->content }}</p>
                @endif

                <!-- Media -->
                @if ($feed->media->count() > 0)
                    <div class="bg-gray-100">
                        <div class="owl-carousel owl-theme">
                            @foreach ($feed->media as $item)
                                @php($src = str_replace('http://127.0.0.1:8000', 'https://digitalmandi.online', $item->path))
                                <div>
                                    @if (strtolower($item->ext) === 'mp4')
                                        <video src="{{ $src }}" controls class="w-full bg-black" style="max-height: 32rem;"></video>
                                    @else
                                        <img src="{{ $src }}" class="w-full object-contain" style="max-height: 32rem;" alt="Post image">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Counts -->
                <div class="flex items-center gap-6 px-5 sm:px-6 py-4 border-t border-gray-100 text-sm font-semibold text-gray-700">
                    <span class="flex items-center gap-2">
                        <span class="bi bi-hand-thumbs-up text-green-500"></span> {{ $feed->likes_count }} Likes
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="bi bi-chat-dots text-green-500"></span> {{ $feed->comments_count }} Comments
                    </span>
                </div>

                @if ($feed->comments->count() > 0)
                    <div class="px-5 sm:px-6 pb-5 space-y-2">
                        @foreach ($feed->comments as $comment)
                            @if ($comment->user)
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                                    <img src="{{ $comment->user->image }}" alt=""
                                        class="w-9 h-9 rounded-full object-cover border-2 border-green-100">
                                    <div class="min-w-0">
                                        <div class="text-sm font-medium text-gray-900">{{ $comment->user->name }}
                                            <span class="text-xs font-normal text-gray-400">· {{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p dir="auto" class="text-sm text-gray-700" style="overflow-wrap: anywhere;">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- App download -->
            <div class="mt-6 bg-white rounded-3xl shadow-sm border border-gray-100 p-5 sm:p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-gray-700 text-center sm:text-left">Like, comment and post in the Digital Mandi app.</div>
                <a href="https://play.google.com/store/apps/details?id=com.kisan.digitalmandi&hl=en" target="_blank"
                    rel="noopener"
                    class="flex items-center justify-center gap-2 px-5 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-base whitespace-nowrap">
                    <span class="bi bi-google-play"></span> Get the App
                </a>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="module">
        $('.owl-carousel').owlCarousel({ items: 1 });
    </script>
@endsection
