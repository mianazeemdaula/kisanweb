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
            ['color'=>'#FFF6E4','icon'=>'wheat','name' => 'wheat'],
            ['color'=>'#E4F3EA','icon'=>'wheat','name' => 'maize'],
            ['color'=>'#F3E4E4','icon'=>'wheat','name' => 'rice'],
            ['color'=>'blue','icon'=>'wheat','name' => 'cotton'],
            ['color'=>'blue','icon'=>'wheat','name' => 'sugarcane'],
            ['color'=>'blue','icon'=>'wheat','name' => 'sorghum'],
        ]);
        \App\Models\CropType::factory(50)->create();
        \App\Models\Packing::insert([
            ['name' => 'open'],
            ['name' => 'tora'],
            ['name' => 'jali'],
            ['name' => 'bori'],
        ]);
        \App\Models\Deal::factory(150)->create();
        \App\Models\Bid::factory(300)->create();
        \App\Models\Media::factory(1000)->create();
    }
}
