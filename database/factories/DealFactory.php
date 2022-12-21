<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use MatanYadaev\EloquentSpatial\Objects\Point;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class DealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'crop_type_id' => fake()->numberBetween(1,50),
            'seller_id' => fake()->numberBetween(1,50),
            'packing_id' => fake()->numberBetween(1,4),
            'demand' => fake()->numberBetween(100000,20000),
            'qty' => fake()->numberBetween(50,999),
            'note' => fake()->word(15),
            'location' => new Point(fake()->latitude($min = 30.651693, $max = 30.693258), fake()->longitude($min = 73.638164, $max = 73.675851)),
        ];
    }
}
