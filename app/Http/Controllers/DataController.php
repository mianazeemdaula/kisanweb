<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Province;
use App\Models\District;
use App\Models\City;

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
}
