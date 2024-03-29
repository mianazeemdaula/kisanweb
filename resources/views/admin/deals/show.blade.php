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
                <img src="{{ $deal->media[0]->path }}" class="w-40 " alt="Images">
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
                            <p>Mobile: {{ $bid->buyer->mobile }}</p>
                            <p>WhatsApp: {{ $bid->buyer->whatsapp }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Shops Information -->
            <div class="bg-gray-100 p-4 mt-4">
                <h2 class="text-lg">Nearby Shops</h2>
                <table class="min-w-full text-left text-sm font-light">
                    <thead class="border-b font-medium dark:border-neutral-500">
                        <tr>
                            <td>Name</td>
                            <td>Location</td>
                            <td>Phone</td>
                            <td>WhatsApp</td>
                            <td>Distance</td>
                        </tr>
                    </thead>
                    @foreach ($shops as $shop)
                        <tr class="border-b dark:border-neutral-500">
                            <td>{{ $shop->name }}</td>
                            <td class="whitespace-normal">{{ $shop->address }}</td>
                            <td>{{ $shop->social_links['mobile'] }}</td>
                            <td>{{ $shop->social_links['whatsapp'] }}</td>
                            <td>{{ $shop->distance * 100 }} km</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
