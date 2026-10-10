<?php

namespace App\Http\Controllers\Api\V1;

use App\Support\ApiCache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\City;
use App\Models\Province;

class CityController extends Controller
{
    public function index()
    {
        return ApiCache::json('cities', 'all', 86400, function () {
            return City::orderBy('name')->select(['id','name', 'name_ur'])->get();
        });
    }
}
