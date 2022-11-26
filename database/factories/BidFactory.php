<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bid>
 */
class BidFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'offer_id' => fake()->numberBetween(1,150),
            'buyer_id' => fake()->numberBetween(51,60),
            'bid_price' => fake()->numberBetween(10000,1000000),
        ];
    }
}
