@extends('layouts.guest')
@section('title')
    {{ $deal->type->crop->name }} for sale of RS.{{ $deal->demand }} / {{ $deal->weight->name }} at {{ $deal->address }}
@endsection
@section('body')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Image Gallery -->
                <div class="bg-gray-100">
                    <div class="owl-carousel owl-theme">
                        @foreach ($deal->media as $item)
                            <div class="aspect-square">
                                <img src="{{ $item->path }}" class="w-full h-full object-cover" alt="Deal image">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Deal Details -->
                <div class="p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $deal->type->crop->name }}</h1>
                    <p class="text-gray-600 mb-6">Type: {{ $deal->type->name }}</p>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">Price</span>
                            <span
                                class="text-2xl font-bold text-green-600">₹{{ $deal->demand }}/{{ $deal->weight->name }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">Available Quantity</span>
                            <span class="font-semibold text-gray-900">{{ $deal->qty }}
                                {{ $deal->weight->name ?? '' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">Reactions</span>
                            <div class="flex items-center space-x-2">
                                <span class="bi bi-hand-thumbs-up text-green-600"></span>
                                <span class="font-semibold">{{ $deal->reactions->count() }}</span>
                            </div>
                        </div>
                    </div>

                    @if ($deal->note)
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Note</h3>
                            <p class="text-gray-700">{{ $deal->note }}</p>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Location</h3>
                        <p class="text-gray-700">{{ $deal->address }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Contact Seller</h3>
                        <div class="flex flex-col space-y-2">
                            <a href="tel:{{ $deal->seller->mobile }}"
                                class="flex items-center space-x-3 px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                                <span class="bi bi-phone text-lg"></span>
                                <span class="font-medium">{{ $deal->seller->mobile }}</span>
                            </a>
                            @if ($deal->seller->whatsapp)
                                <a href="https://wa.me/{{ $deal->seller->whatsapp }}"
                                    class="flex items-center space-x-3 px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                                    <span class="bi bi-whatsapp text-lg"></span>
                                    <span class="font-medium">{{ $deal->seller->whatsapp }}</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if ($deal->bids->count() > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Bids</h3>
                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                @foreach ($deal->bids as $bid)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ $bid->buyer->image }}" alt=""
                                                class="w-10 h-10 rounded-full object-cover border-2 border-green-100">
                                            <span class="font-medium text-gray-900">{{ $bid->buyer->name }}</span>
                                        </div>
                                        <span class="font-bold text-green-600">₹{{ $bid->bid_price }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
