<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Events\DealUpdateEvent;
// Models
use App\Models\Bid;
use App\Models\Deal;

class BidController extends Controller
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
            'deal_id' => 'required',
            'bid_price' => 'required',
        ]);
        $bid = Bid::where('deal_id', $request->deal_id)->where('buyer_id',$request->user()->id)->first();
        if(!$bid){
            $bid = new Bid();
            $bid->deal_id = $request->deal_id;
            $bid->buyer_id = $request->user()->id;
            $bid->bid_price = $request->bid_price;
            $bid->save();
        }else{
            $bid->bid_price = $request->bid_price;
            $bid->save();
        }
        \App\Jobs\BidNotificationJob::dispatch($bid->deal_id);
        DealUpdateEvent::dispatch($request->deal_id);
        return response()->json($bid, 200);
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $this->validate($request,[
            'deal_id' => 'required'
        ]);
        $bid = Deal::find($request->deal_id);
        if($bid->accept_bid_id != null){
            return response()->json(['message'=>'You have already accepted'], 409);
        }
        $bid->status = 'accepted';
        $bid->accept_bid_id = $id;
        $bid->save();
        DealUpdateEvent::dispatch($request->deal_id);
        return response()->json($bid, 200);
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
