@extends('layouts.admin')
@section('content')
    <div>
        <div class="container mx-auto">
            <!-- Deal Information -->
            <div class="bg-gray-100 p-4">
                <h1 class="text-xl">{{ $deal->crop }}</h1>
                <p>Crop: {{ $deal->type->crop->name }}</p>
                <p>Type: {{ $deal->type->name }}</p>
                <p>Demand Price: RS{{ $deal->demand }}</p>
                <p>Quantity: {{ $deal->qty }} {{ $deal->weight->name }}</p>
                <p>Location: {{ $deal->address }}</p>
                <p>Phone: {{ $deal->seller->mobile }}</p>
                <img src="{{ $deal->media[0]->path }}" alt="Images">
            </div>

            <!-- Bids Information -->
            <div class="bg-white p-4 mt-4">
                <h2 class="text-lg">Bids</h2>
                @foreach ($deal->bids as $bid)
                    <div class="flex items-center mt-2">
                        <img src="{{ $bid->buyer->image }}" alt="Bidder Image" class="w-10 h-10 rounded-full">
                        <div class="ml-4">
                            <p>{{ $bid->buyer->name }}</p>
                            <p>Bidding Price: RS{{ $bid->bid_price }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Shops Information -->
            <div class="bg-gray-100 p-4 mt-4">
                <h2 class="text-lg">Nearby Shops</h2>
                @foreach ($shops as $shop)
                    <div class="flex items-center mt-2">
                        <p>Name: {{ $shop->name }}</p>
                        <p>Location: {{ $shop->address }}</p>
                        <p>Phone: {{ $shop->social_links['mobile'] }}</p>
                        <p>WhatsApp: {{ $shop->social_links['whatsapp'] }}</p>
                        <p>Distance: {{ $shop->distance }} km</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
