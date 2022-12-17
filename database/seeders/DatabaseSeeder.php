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
        \App\Models\User::factory(50)->state(new Sequence(fn ($se) => ['email' => "user{$se->index}@gmail.com",'mobile' => "030011223".sprintf('%02d',$se->index)],
        ))->create();
        \App\Models\User::factory(10)->state(new Sequence(fn ($se) => ['email' => "buyer{$se->index}@gmail.com",'mobile' => "032111223".sprintf('%02d',$se->index)],
        ))->create();
        \App\Models\Crop::insert([
            ['name_ur'=>'what','color'=>'#FFF6E4','icon'=>'wheat','name' => 'wheat'],
            ['name_ur'=>'what','color'=>'#E4F3EA','icon'=>'wheat','name' => 'maize'],
            ['name_ur'=>'what','color'=>'#F3E4E4','icon'=>'wheat','name' => 'rice'],
            ['name_ur'=>'what','color'=>'blue','icon'=>'wheat','name' => 'cotton'],
            ['name_ur'=>'what','color'=>'blue','icon'=>'wheat','name' => 'sugarcane'],
            ['name_ur'=>'what','color'=>'blue','icon'=>'wheat','name' => 'sorghum'],
        ]);
        \App\Models\CropType::factory(50)->create();
        \App\Models\Packing::insert([
            ['name' => 'open','key'=>'wheat','name_ur'=>'توڑا'],
            ['name' => 'tora','key'=>'wheat','name_ur'=>'توڑا'],
            ['name' => 'jali','key'=>'wheat','name_ur'=>'توڑا'],
            ['name' => 'bori','key'=>'wheat','name_ur'=>'توڑا'],
        ]);
        \App\Models\Deal::factory(150)->create();
        \App\Models\Bid::factory(300)->create();
        \App\Models\Media::factory(1000)->create();
    }
}
