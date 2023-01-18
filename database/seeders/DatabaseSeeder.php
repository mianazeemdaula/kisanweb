<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Models\CropType;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(50)->state(new Sequence(fn ($se) => ['email' => "user{$se->index}@gmail.com",'mobile' => "030011223".sprintf('%02d',$se->index)],
        // ))->create();
        // \App\Models\User::factory(10)->state(new Sequence(fn ($se) => ['email' => "buyer{$se->index}@gmail.com",'mobile' => "032111223".sprintf('%02d',$se->index)],
        // ))->create();
        \App\Models\Crop::insert([
            ['name_ur'=>'آلو','color'=>'#E0947F','icon'=>'potato','name' => 'Potato'],
            ['name_ur'=>'لہسن','color'=>'#CE8919','icon'=>'garlic','name' => 'Garlic'],
            ['name_ur'=>'گنا','color'=>'#A89E22','icon'=>'sugarcane','name' => 'Sugarcane'],
            ['name_ur'=>'مکئی','color'=>'#CE8919','icon'=>'maize','name' => 'Maize'],
            ['name_ur'=>'گندم','color'=>'#DCAB65','icon'=>'wheat','name' => 'Wheat'],
            ['name_ur'=>'چاول','color'=>'#9DAC4F','icon'=>'rice','name' => 'Rice'],
            ['name_ur'=>'روئی','color'=>'#8B5321','icon'=>'cotton','name' => 'Cotton'],
            ['name_ur'=>'جوار','color'=>'#ECD392','icon'=>'sorghum','name' => 'Sorghum'],
        ]);
        // \App\Models\CropType::factory(50)->create();
        CropType::create(['crop_id'=>1, 'name' => 'Caroda', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>1, 'name' => 'Mozika', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>1, 'name' => 'Santa', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>1, 'name' => 'Astres', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>1, 'name' => 'L.R', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>1, 'name' => 'Arvira', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>1, 'name' => 'Flaminco', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>1, 'name' => 'Esmi', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>1, 'name' => 'Rosi', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>2, 'name' => 'NARC G1', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CoL-29', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CoL-44', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CoL-54', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'BL-19', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'BL-4', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'L-116', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'L-118', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'Triton', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'BF-162', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CP43-33', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CP72-2086', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CP77-400', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CoJ-84', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'SPF-213', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CPF-237', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'HSF-240', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'SPF-234', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'HSF-242', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'HSF-242', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CPF-243', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CPF-246', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CPF-247', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CPF-249', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CPF-250', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CPF-251', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CPF-252', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>3, 'name' => 'CPF-253', 'code' =>'CF-185']);
        CropType::create(['crop_id'=>4, 'name' => 'DK 9108', 'code' =>'DK 9108']);
        CropType::create(['crop_id'=>4, 'name' => 'DK 7024', 'code' =>'DK 7024']);
        CropType::create(['crop_id'=>4, 'name' => 'DK 8022', 'code' =>'DK 8022']);
        CropType::create(['crop_id'=>4, 'name' => 'DK 6317', 'code' =>'DK 6317']);
        CropType::create(['crop_id'=>4, 'name' => 'DK 8148', 'code' =>'DK 8148']);
        CropType::create(['crop_id'=>4, 'name' => 'DK 6724', 'code' =>'DK 6724']);
        CropType::create(['crop_id'=>4, 'name' => 'DK 6103', 'code' =>'DK 6103']);
        CropType::create(['crop_id'=>4, 'name' => 'DK 6714', 'code' =>'DK 6714']);
        CropType::create(['crop_id'=>4, 'name' => 'DK 6789', 'code' =>'DK 6789']);
        CropType::create(['crop_id'=>4, 'name' => 'GARANON', 'code' =>'GARANON']);
        CropType::create(['crop_id'=>4, 'name' => 'GORILA', 'code' =>'GORILA']);
        CropType::create(['crop_id'=>4, 'name' => 'P1429', 'code' =>'P1429']);
        CropType::create(['crop_id'=>4, 'name' => '31P41', 'code' =>'31P41']);
        CropType::create(['crop_id'=>4, 'name' => 'P1543', 'code' =>'P1543']);
        CropType::create(['crop_id'=>4, 'name' => 'P4040', 'code' =>'P4040']);
        CropType::create(['crop_id'=>4, 'name' => '30T60', 'code' =>'30T60']);
        CropType::create(['crop_id'=>4, 'name' => '30Y87', 'code' =>'30Y87']);
        CropType::create(['crop_id'=>4, 'name' => 'P3939', 'code' =>'P3939']);
        CropType::create(['crop_id'=>4, 'name' => '30K08', 'code' =>'30K08']);
        CropType::create(['crop_id'=>4, 'name' => '3025', 'code' =>'3025']);
        CropType::create(['crop_id'=>4, 'name' => '30Y87', 'code' =>'30Y87']);
        
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
        // \App\Models\Deal::factory(150)->create();
        // \App\Models\Bid::factory(300)->create();
        // \App\Models\Media::factory(1000)->create();
    }
}
