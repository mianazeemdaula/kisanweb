<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Province;
use App\Models\District;
use App\Models\City;
use App\Models\CropRate;
use LevelUp\Experience\Models\Level;

class DataController extends Controller
{
    public function cities()
    {
        $path = public_path() . "/data/cities.json";
        $cities = json_decode(file_get_contents($path), true);
        foreach ($cities as $city) {
            $province = Province::updateOrCreate([
                'name'=> $city['province'],
                'code'=> $city['province_code'],
            ]);

            $district = District::updateOrCreate([
                'name'=> $city['district'],
                'code'=> $city['distric_code'],
                'province_id' => $province->id,
            ]);

            $city = City::updateOrCreate([
                'name'=> $city['name'],
                'district_id' => $district->id,
                'code' => $city['code'],
            ]);
        }
        return response()->json($cities, 200);
    }
    public function lastRatesUpdate(){
        $results = DB::table('crop_rates as cr')
            // ->select('cr.id', 'cr.city_id', 'cr.crop_type_id', 'cr.rate_date')
            ->whereIn(DB::raw('(cr.city_id, cr.crop_type_id, cr.rate_date)'), function ($query) {
                $query->select(DB::raw('city_id,crop_type_id, MIN(rate_date)'))
                    ->from('crop_rates')
                    ->groupBy('city_id', 'crop_type_id');
            })
            ->orderBy('cr.id')
            ->get();
        // return count($results);
        foreach ($results as $result) {
            $rate = CropRate::find($result->id);
            if($rate && $rate->max_price_last == 0){
                $rate->max_price_last = $rate->max_price;
                $rate->min_price_last = $rate->min_price;
                $rate->save();
            } 
            if($rate){
                $nexts  = CropRate::where('max_price_last','<',1)
                ->where('city_id',$rate->city_id)
                ->where('crop_type_id', $rate->crop_type_id)
                ->whereDate('rate_date','>', $rate->rate_date)
                ->orderBy('rate_date','asc')->limit(5)->get();
                $upcoming = $rate;
                foreach ($nexts as $next) {
                    $next->max_price_last = $upcoming->max_price;
                    $next->min_price_last = $upcoming->min_price;
                    $next->save();
                    $upcoming = $next;
                }
            }
        }
        return [CropRate::where('max_price_last','<',1)->count(), count($results)];
    }


    public function pointsLevel() {
        $level = Level::find(1);
        if(!$level){
            Level::add(
                ['level' => 1, 'next_level_experience' => null],
                ['level' => 2, 'next_level_experience' => 1000],
                ['level' => 3, 'next_level_experience' => 2000],
                ['level' => 4, 'next_level_experience' => 5000],
                ['level' => 5, 'next_level_experience' => 10000],
            );
        }
        
    }
}
