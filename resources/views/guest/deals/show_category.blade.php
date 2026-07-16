@extends('layouts.guest')
@section('title')
    {{ $deal->subcategory->name ?? 'Category Deal' }} for sale of Rs. {{ $deal->demand }} / {{ $deal->weight->name ?? '' }} at {{ $deal->address }}
@endsection
@section('body')
    <div class="bg-mesh min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ url('/') }}" class="hover:text-green-600 transition-base">Home</a>
                <span class="bi bi-chevron-right text-xs"></span>
                <a href="{{ url('deals?tab=category') }}" class="hover:text-green-600 transition-base">Deals</a>
                <span class="bi bi-chevron-right text-xs"></span>
                <span class="text-gray-700 font-medium">{{ $deal->subcategory->name ?? 'Category Deal' }}</span>
            </nav>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="grid lg:grid-cols-2 gap-0">
                    <!-- Image Gallery -->
                    <div class="bg-gray-100">
                        <div class="owl-carousel owl-theme">
                            @foreach ($deal->media as $item)
                                <div class="aspect-square">
                                    <img src="{{ str_replace('http://127.0.0.1:8000', 'https://kisanstock.com', $item->path) }}" class="w-full h-full object-cover" alt="Deal image">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Deal Details -->
                    <div class="p-6 sm:p-8 lg:p-10">
                        <div class="flex items-center gap-2 mb-4">
                            <span
                                class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full">
                                <span class="bi bi-tag"></span> {{ $deal->subcategory->category->name ?? 'Category' }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $deal->created_at->diffForHumans() }}</span>
                        </div>
                        <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">
                            {{ $deal->subcategory->name ?? 'Category Deal' }}</h1>

                        <!-- Price highlight -->
                        <div
                            class="rounded-2xl bg-gradient-to-br from-green-50 to-green-100/60 border border-green-100 p-5 mb-6">
                            <div class="text-sm text-green-700 font-medium">Asking Price</div>
                            <div class="text-3xl font-extrabold text-green-700">
                                Rs. {{ $deal->demand }}<span
                                    class="text-base font-semibold text-green-600/70">/{{ $deal->weight->name ?? '' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="rounded-2xl border border-gray-100 p-4">
                                <div class="text-xs text-gray-400 mb-1">Available Quantity</div>
                                <div class="font-bold text-gray-900 flex items-center gap-2">
                                    <span class="bi bi-box-seam text-green-500"></span> {{ $deal->qty }}
                                    {{ $deal->weight->name ?? '' }}
                                </div>
                            </div>
                            <div class="rounded-2xl border border-gray-100 p-4">
                                <div class="text-xs text-gray-400 mb-1">Reactions</div>
                                <div class="font-bold text-gray-900 flex items-center gap-2">
                                    <span class="bi bi-hand-thumbs-up text-green-500"></span>
                                    {{ $deal->reactions->count() }}
                                </div>
                            </div>
                        </div>

                        @if ($deal->note)
                            <div class="mb-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Note</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $deal->note }}</p>
                            </div>
                        @endif

                        <div class="mb-6">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Location</h3>
                            <p class="text-gray-700 flex items-start gap-2">
                                <span class="bi bi-geo-alt text-green-500 mt-0.5"></span>
                                <span>{{ $deal->address }}</span>
                            </p>
                        </div>

                        <!-- Seller card -->
                        <div class="rounded-2xl border border-gray-100 p-5 mb-6">
                            <div class="flex items-center gap-3 mb-4">
                                <img src="{{ $deal->user->image ?? asset('default-avatar.png') }}"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-green-100" alt="">
                                <div>
                                    <div class="font-bold text-gray-900">{{ $deal->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">Seller</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <a href="tel:{{ $deal->user->mobile ?? '' }}"
                                    class="flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-base">
                                    <span class="bi bi-telephone"></span> Call Seller
                                </a>
                                @if ($deal->user && $deal->user->whatsapp)
                                    <a href="https://wa.me/{{ $deal->user->whatsapp }}" target="_blank" rel="noopener"
                                        class="flex items-center justify-center gap-2 px-4 py-3 bg-green-50 text-green-700 font-semibold rounded-xl hover:bg-green-100 transition-base">
                                        <span class="bi bi-whatsapp"></span> WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if ($deal->bids->count() > 0)
                            <div>
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Bids
                                    ({{ $deal->bids->count() }})</h3>
                                <div class="space-y-2 max-h-60 overflow-y-auto scrollbar-thin pr-1">
                                    @foreach ($deal->bids as $bid)
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $bid->buyer->image ?? asset('default-avatar.png') }}" alt=""
                                                    class="w-9 h-9 rounded-full object-cover border-2 border-green-100">
                                                <span
                                                    class="font-medium text-gray-900">{{ $bid->buyer->name ?? 'N/A' }}</span>
                                            </div>
                                            <span class="font-bold text-green-600">Rs. {{ $bid->bid_price }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="module">
        $('.owl-carousel').owlCarousel();
    </script>
@endsection
