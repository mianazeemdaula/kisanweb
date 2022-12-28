<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WeightType;
use App\Models\Packing;

class DataController extends Controller
{
    public function getCreateDealData()
    {
        $data['weights'] = WeightType::all();
        $data['packings'] = Packing::all();
        return response()->json($data, 200);
    }
}
