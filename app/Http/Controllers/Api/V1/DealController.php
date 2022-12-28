<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use MatanYadaev\EloquentSpatial\Objects\Point;

// Models
use App\Models\Deal;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'crop_id' => 'required|integer',
            'crop_type_id' => 'required|integer',
            'packing_id' => 'required|integer',
            'demand' => 'required',
            'weight_scale' => 'required',
            'description' => 'required',
            'qty' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);
        $deal = new Deal();
        $deal->seller_id = $request->user()->id;
        $deal->crop_type_id = $request->crop_type_id;
        $deal->packing_id = $request->packing_id;
        $deal->demand = $request->demand;
        $deal->weight_scale = $request->weight_scale;
        $deal->note = $request->description;
        $deal->qty = $request->qty;
        $deal->location = new Point($request->lat,$request->lng);
        $deal->save();
        return  response()->json($deal, 200,);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deal = Deal::with(['bids' => function($q){
            $q->with(['buyer']);
        }, 'seller', 'packing', 'media', 'type.crop'])->find($id);
        $deal->visit();
        return response()->json($deal, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}