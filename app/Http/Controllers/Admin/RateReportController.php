<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Crop;
use App\Models\CropRate;

class RateReportController extends Controller
{
    function reports()  {
        $crops = Crop::with('types')->get();
        return view('admin.rate-reports.index', compact('crops'));
    }

    function cropTypeLastDays(Request $request)  {
        $dates = CropRate::select('rate_date')->orderBy('rate_date','desc')->limit(7)->distinct()->get();
        if ($dates->isEmpty()) {
            return response()->json([], 200);
        }
        $startDate = $dates->last()->rate_date;
        $endDate = $dates->first()->rate_date;
        $data =  CropRate::selectRaw('MIN(min_price) as _min, MAX(max_price) as _max, rate_date, crop_type_id')
            ->whereBetween('rate_date', [$startDate, $endDate])
            ->whereIn('crop_type_id', [82,76,60])
            ->groupBy('rate_date')
            ->groupBy('crop_type_id')
            ->get();    
        return response()->json($data, 200);
    }

}