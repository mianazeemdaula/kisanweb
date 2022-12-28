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
            ['name_ur'=>'آلو','color'=>'#E0947F','icon'=>'potato','name' => 'Potato'],
            ['name_ur'=>'مکئی','color'=>'#CE8919','icon'=>'maize','name' => 'Maize'],
            ['name_ur'=>'چاول','color'=>'#9DAC4F','icon'=>'rice','name' => 'Rice'],
            ['name_ur'=>'گندم','color'=>'#DCAB65','icon'=>'wheat','name' => 'Wheat'],
            ['name_ur'=>'گنا','color'=>'#A89E22','icon'=>'sugarcane','name' => 'Sugarcane'],
            ['name_ur'=>'روئی','color'=>'#8B5321','icon'=>'cotton','name' => 'Cotton'],
            ['name_ur'=>'جوار','color'=>'#ECD392','icon'=>'sorghum','name' => 'Sorghum'],
        ]);
        \App\Models\CropType::factory(50)->create();
        \App\Models\Packing::insert([
            ['name' => 'open','key'=>'wheat','name_ur'=>'کھولی'],
            ['name' => 'tora','key'=>'wheat','name_ur'=>'توڑا'],
            ['name' => 'jali','key'=>'wheat','name_ur'=>'جالی'],
            ['name' => 'bori','key'=>'wheat','name_ur'=>'بوری'],
        ]);

        \App\Models\WeightType::insert([
            ['name' => 'kg','key'=>'kg','name_ur'=>'کلوگرام'],
            ['name' => 'mund','key'=>'mund','name_ur'=>'من'],
            ['name' => 'tora','key'=>'tora','name_ur'=>'توڑا'],
            ['name' => 'jali','key'=>'jali','name_ur'=>'جالی'],
            ['name' => 'bori','key'=>'bori','name_ur'=>'بوری'],
            ['name' => 'acre','key'=>'acre','name_ur'=>'ایکٹر'],
        ]);
        \App\Models\Deal::factory(150)->create();
        \App\Models\Bid::factory(300)->create();
        \App\Models\Media::factory(1000)->create();
    }
}
