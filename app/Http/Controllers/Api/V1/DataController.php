<?php

namespace App\Http\Controllers\Api\V1;

use App\Support\ApiCache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WeightType;
use App\Models\Packing;

class DataController extends Controller
{
    public function getCreateDealData()
    {
        return ApiCache::json('static', 'create_deal', 86400, function () {
            $data['weights'] = WeightType::all();
            $data['packings'] = Packing::all();
            return $data;
        });
    }
}
