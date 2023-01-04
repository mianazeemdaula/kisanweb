<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use MatanYadaev\EloquentSpatial\Objects\Point;
use App\Helper\MediaHelper;
use App\Events\DealUpdateEvent;

// Models
use App\Models\Deal;
use App\Models\Bid;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Deal::with(['bids' => function($q){
            $q->with(['buyer']);
        }, 'seller', 'packing', 'weight', 'media', 'type.crop'])->paginate();
        return response()->json($data, 200);
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
            'weight_type_id' => 'required',
            'demand' => 'required',
            'note' => 'required',
            'qty' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'address' => 'address',
            'images' => 'required',
            'images.*' => 'required|mimes:jpg,jpeg,png',
        ]);
        $deal = new Deal();
        $deal->seller_id = $request->user()->id;
        $deal->crop_type_id = $request->crop_type_id;
        $deal->packing_id = $request->packing_id;
        $deal->demand = $request->demand;
        $deal->weight_type_id = $request->weight_type_id;
        $deal->note = $request->note;
        $deal->qty = $request->qty;
        $deal->location = new Point($request->lat,$request->lng);
        $deal->address = $request->address;
        $deal->save();
        foreach ($request->file('images') as $key => $file) {
            MediaHelper::save($file, $deal);
        }
        DealUpdateEvent::dispatch($deal->id);
        return  response()->json($deal, 200);
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
        }, 'seller', 'packing', 'weight' ,'media', 'type.crop'])->find($id);
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

    public function history()
    {
        $user = auth()->user();
        $bidsDealIds = Bid::where('buyer_id', $user->id)->pluck('deal_id');
        $data = Deal::with(['bids' => function($q){
            $q->with(['buyer']);
        }, 'seller', 'packing', 'weight', 'media', 'type.crop', 'reviews'])
        ->where('seller_id', $user->id)
        ->orWhereIn('id', $bidsDealIds)
        ->paginate();
        return response()->json($data, 200);
    }
}
