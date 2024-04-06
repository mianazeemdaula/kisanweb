<?php

namespace App\Http\Controllers\Api\V1\FeedMill;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\FeedMill;

class FeedMillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = FeedMill::with(['rate' => function($q){
            // $q->whereDate('created_at','>=',Carbon::now()->subDay()->format('Y-m-d'));
        } ,'city'])
        ->whereHas('rate')->paginate(50);
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'crop_type_id' => 'required',
            'min' => 'required',
            'max' => 'required',
            'city' => 'required',
            'rate_date' => 'required',
        ]);
        $lastRate = CropRate::where('crop_type_id', $request->crop_type_id)
        ->where('city_id',$request->city)
        ->whereDate('rate_date', '<',Carbon::now()->format('Y-m-d'))
        ->orderBy('rate_date','desc')->first();
        $rate = CropRate::updateOrCreate([
            'crop_type_id' => $request->crop_type_id,
            'city_id' => $request->city,
            'user_id' => $request->user()->id,
            // 'rate_date' => Carbon::parse($request->rate_date)->format('Y-m-d'),
            'rate_date' => Carbon::now()->format('Y-m-d'),
        ],[
            'min_price' => $request->min,
            'max_price' => $request->max,
            'min_price_last' => $lastRate->min_price ?? $request->min,
            'max_price_last' => $lastRate->max_price ?? $request->max,
        ]);
        // \App\Helper\FCM::sendToSetting(6,"نرخ اپڈیٹ","آپ کے شہر کے فصلوں کے ریٹس اپڈیٹ کر دیے گئے ہیں",[
        //     'type' => 'mand_rate',
        //     'crop_id' => $request->crop_type_id,
        //     'city_id' => $request->city,
        // ]);
        return response()->json($rate, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
