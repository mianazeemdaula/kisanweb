<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Crop;
use App\Models\CropRate;
use App\Models\CropType;
use Illuminate\Support\Facades\DB;

class CropCityRateController extends Controller
{
    public function index()
    {
        $data = Crop::with(['types' => function($q){
            $q->with(['rate' => function($r){
                $r->select(
                    'rate_date','crop_type_id',
                    \DB::raw('cast(min(min_price) as float) as min_rate'),
                    \DB::raw('cast(max(max_price) as float) as max_rate'),
                )->groupBy('rate_date','crop_type_id')
                ->whereDate('rate_date','2023-02-02');
            }])->whereHas('rate');
        }])->get();
        return response()->json($data, 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'crop_type_id' => 'required',
            'min' => 'required',
            'max' => 'required',
            'city' => 'required',
            'rate_date' => 'required',
        ]);
        $rate = CropRate::updateOrCreate([
            'crop_type_id' => $request->crop_type_id,
            'city_id' => $request->city,
            'user_id' => $request->user()->id,
            'rate_date' => Carbon::parse($request->rate_date)->format('Y-m-d'),
        ],[
            'min_price' => $request->min,
            'max_price' => $request->max,
        ]);
        return response()->json($rate, 200);
    }

    public function show($id)
    {
            $rates  = CropRate::cityWiseRate()
            ->with(['city'])
            ->where('cr.crop_type_id',$id)
            ->orderBy('cities.name','asc')
            ->paginate();
            return response()->json($rates, 200,[]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

  
    public function destroy($id)
    {
        //
    }

    public function cityHistory(Request $request)
    {
        $paginate =  CropRate::select(
            'city_id',
            'crop_type_id',
            DB::raw('min_price AS min_rate'),
            DB::raw('max_price AS max_rate'),
            'rate_date',
            'min_price_last',
            'max_price_last'
        )
        ->orderBy('rate_date', 'desc')
        ->where('crop_type_id', $request->crop)
        ->where('city_id', $request->city)
        ->paginate();
        return response()->json($paginate, 200, []);
    }
    
}
