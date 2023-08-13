<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Province;
use App\Models\District;
use App\Models\City;
use App\Models\CropRate;

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
        $rates = CropRate::where('max_price_last','<',1)->orderBy('rate_date','asc')->limit(200)->get();
        foreach ($rates as $rate) {    
            // $rate = $rates[0];
            $nextRate = CropRate::where('crop_type_id', $rate->crop_type_id)
            ->where('city_id',$rate->city_id)->orderBy('rate_date','asc')
            ->whereDate('rate_date','>', $rate->rate_date)->first();
            if($nextRate){
                $nextRate->max_price_last = $rate->max_price;
                $nextRate->min_price_last = $rate->min_price;
                $nextRate->save();
            }
            if(!$nextRate || $rate->max_price_last == 0){
                $rate->max_price_last = $rate->max_price;
                $rate->min_price_last = $rate->min_price;
                $rate->save();
            }
        }
        return [CropRate::where('max_price_last','<',1)->count(), count($rates), $rates[0] ?? ''];
    }
}
