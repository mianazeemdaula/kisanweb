<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(50)->state(new Sequence(fn ($se) => ['email' => "user{$se->index}@gmail.com"],
        ))->create();
        \App\Models\User::factory(10)->state(new Sequence(fn ($se) => ['email' => "buyer{$se->index}@gmail.com", 'type' => 'buyer'],
        ))->create();
        \App\Models\Crop::insert([
            ['name' => 'wheat'],
            ['name' => 'maize'],
            ['name' => 'rice'],
            ['name' => 'cotton'],
            ['name' => 'sugarcane'],
            ['name' => 'mango'],
            ['name' => 'dates'],
        ]);
        \App\Models\CropType::factory(50)->create();
        \App\Models\Packing::insert([
            ['name' => 'open'],
            ['name' => 'tora'],
            ['name' => 'jali'],
            ['name' => 'bori'],
        ]);
        \App\Models\Offer::factory(150)->create();
        \App\Models\Bid::factory(300)->create();
        \App\Models\Media::factory(1000)->create();
    }
}
