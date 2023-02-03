<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Crop;
use App\Models\CropRate;
use App\Models\CropType;

class CropRateController extends Controller
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
            'crop_type_id' => $this->crop_type_id,
            'city_id' => $this->city,
            'user_id' => $request->user()->id,
            'rate_date' => $request->rate_date,
        ],[
            'min_price' => $request->min,
            'max_price' => $request->max,
        ]);
        return response()->json($rate, 200);
    }

    public function show($id)
    {
        //
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

    public function filter(Request $request)
    {
        // $data = Crop::with(['types' => function($q) use($request) {
        //     $q->with(['rate' => function($r) use($request) {
        //         $r->select(
        //             'rate_date','crop_type_id',
        //             \DB::raw('cast(min(min_price) as float) as min_rate'),
        //             \DB::raw('cast(max(max_price) as float) as max_rate'),
        //         )->groupBy('rate_date','crop_type_id')
        //         ->whereDate('rate_date',Carbon::parse($request->date));
        //     }]);
        //     // ->whereHas('rate');
        // }])->get();
        $data = CropType::with(['rate' => function($r) use($request) {
            $r->select(
                'rate_date','crop_type_id',
                \DB::raw('cast(min(min_price) as float) as min_rate'),
                \DB::raw('cast(max(max_price) as float) as max_rate'),
            )->groupBy('rate_date','crop_type_id')
            ->whereDate('rate_date',Carbon::parse($request->date));
        }])->whereHas('rate')->where('crop_id', $request->crop)->get();
        return response()->json($data, 200);
    }
    
}
