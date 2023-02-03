<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CropRate;

class CropRateController extends Controller
{
    public function index()
    {
        $data = CropRate::where('crop_type_id',1)->get();
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

    public function filter(Reqeust $request)
    {
        $data = CropRate::where([
            'city_id' => $request->city,
            'crop_type_id' => $request->crop_type_id,
        ])->get();
        return response()->json($data, 200);
    }
    
}
