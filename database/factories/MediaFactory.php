<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'path' => fake()->imageUrl(576, 720, 'animals', true, 'cats'),
            'ext' => 'jpg',
            'blursh' => 'LENBb?%2}@.5R$InIA%M?btBk-xc',
            'mediaable_id' =>fake()->numberBetween(1,300),
            'mediaable_type' => 'App\Models\Deal',
        ];
    }
}
