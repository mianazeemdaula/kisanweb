<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\City;
use App\Models\Province;

class CityController extends Controller
{
    public function index()
    {
        $data = City::whereHas('district', function($q){
            $q->whereIn('province_id', [4,6,7]);
        })->orderBy('name')->select(['id','name'])->get();
        return response()->json($data, 200);
    }
}
