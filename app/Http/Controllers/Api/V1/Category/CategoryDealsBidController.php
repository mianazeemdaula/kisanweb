<?php

namespace App\Http\Controllers\Api\V1\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\DealUpdateEvent;
// Models
use App\Models\CategoryDealBid;
use App\Models\CategoryDeal;

class CategoryDealsBidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'deal_id' => 'required',
            'bid_price' => 'required',
        ]);
        $user = $request->user();
        $bid = CategoryDealBid::where('category_deal_id', $request->deal_id)->where('buyer_id',$user->id)->first();
        if(!$bid){
            $bid = new CategoryDealBid();
            $bid->deal_id = $request->deal_id;
            $bid->buyer_id = $user->id;
            $bid->bid_price = $request->bid_price;
            $bid->save();
        }else{
            $bid->bid_price = $request->bid_price;
            $bid->save();
        }
        // \App\Jobs\BidNotificationJob::dispatch($bid->deal_id, $user->id);
        // DealUpdateEvent::dispatch($request->deal_id);
        return response()->json($bid, 200);
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
        $this->validate($request,[
            'deal_id' => 'required'
        ]);
        $bid = CategoryDealBid::find($request->deal_id);
        if($bid->accept_bid_id != null){
            return response()->json(['message'=>'You have already accepted'], 409);
        }
        $bid->status = 'accepted';
        $bid->accept_bid_id = $id;
        $bid->save();
        // DealUpdateEvent::dispatch($request->deal_id);
        return response()->json($bid, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
