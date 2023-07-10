@extends('layouts.guest')
@section('body')
    <div class="flex p-4">
        <div class="bg-green h-96 min-w-[30%] max-w-[30%] owl-carousel owl-theme">
            {{-- <img src="{{ $deal->media()->first()->path }}" alt="Image not found"> --}}
            @foreach ($deal->media as $item)
                <img src="{{ $item->path }}" alt="Image not found">
            @endforeach
        </div>
        <div class="ml-2 flex-1">
            <h2 class="text-2xl">{{ $deal->type->crop->name }}</h2>
            <h2>Type: {{ $deal->type->name }}</h2>
            <div>Note: {{ $deal->note }}</div>
            <div>Available {{ $deal->qty }} {{ $deal->weight->name ?? '' }}</div>
            <div class="flex justify-between">
                <div class="font-bold">RS.{{ $deal->demand }} / {{ $deal->weight->name }}</div>
                <div class="flex space-x-1 bg-gray-200 rounded-2xl px-3 ">
                    <div>{{ $deal->reactions->count() }}</div>
                    <span class="bi bi-hand-thumbs-up"></span>
                </div>
            </div>
            <div class="flex space-x-4">
                <span class="bi bi-phone">{{ $deal->seller->mobile }}</span>
                <span class="bi bi-whatsapp">{{ $deal->seller->whatsapp }}</span>
            </div>
            <div>
                {{ $deal->address }}
            </div>
            <div>
                @foreach ($deal->bids as $bid)
                    <div class="flex justify-between my-2">
                        <div class="flex space-x-3">
                            <img src="{{ $bid->buyer->image }}" alt="" srcset=""
                                class="w-10 h-10 rounded-full object-cover">
                            <div> {{ $bid->buyer->name }}</div>
                        </div>
                        <div>Rs.{{ $bid->bid_price }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="module">
            $('.owl-carousel').owlCarousel();
    </script>
@endsection
