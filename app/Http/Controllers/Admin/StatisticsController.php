<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function todayCropRatesDeos(){
        // select distint user_id from crop_rates where rate_date = now()
        $ids = \App\Models\CropRate::whereDate('rate_date', now())->distinct('user_id')->select('user_id')->get();
        $users = \App\Models\User::whereIn('id', $ids)->get();
        return response()->json($users, 200, [], JSON_PRETTY_PRINT);
    }
}
