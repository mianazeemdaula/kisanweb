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
        $data =  CropRate::selectRaw('MIN(min_price) as _min, MAX(max_price) as _max, rate_date')
            ->whereBetween('rate_date', [$dates[$dates->count()-1]['rate_date'],$dates[0]['rate_date']])
            ->where('crop_type_id', $request->type_id)
            ->groupBy('rate_date')
            ->groupBy('crop_type_id')
            ->get();
        return response()->json($data, 200);
    }

}