<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Events\DealUpdateEvent;
use Illuminate\Support\Facades\Cache;
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
        $bids = Bid::with(['buyer'])->paginate();
        return response()->json($bids, 200);
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
        $user = $request->user();
        $dealId = $request->deal_id;
        // One bid per buyer and deal, even if two requests arrive together
        // (double tap, retry on a slow network).
        $bid = Cache::lock('bid:'.$dealId.':'.$user->id, 10)->block(5, function () use ($request, $user, $dealId) {
            $bid = Bid::where('deal_id', $dealId)->where('buyer_id', $user->id)->first();
            if(!$bid){
                $bid = new Bid();
                $bid->deal_id = $dealId;
                $bid->buyer_id = $user->id;
            }
            $bid->bid_price = $request->bid_price;
            $bid->save();
            return $bid;
        });
        // Notifications and the live update run after the response is sent,
        // so the buyer is not kept waiting for them.
        $userId = $user->id;
        $userName = $user->name;
        app()->terminating(function () use ($dealId, $userId, $userName) {
            try {
                $deal = Deal::find($dealId);
                if($deal){
                    $data =  [
                        'type' => 'deal',
                        'id' => $deal->id,
                        'deal_id' => $deal->id,
                    ];
                    \App\Jobs\ActivityNotificationJob::dispatch([$deal->seller_id], "Bid", "$userName bid on your deal", $data, 3, $userId);
                }
                \App\Jobs\BidNotificationJob::dispatch($dealId, $userId);
                DealUpdateEvent::dispatch($dealId);
            } catch (\Throwable $e) {
                report($e);
            }
        });
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
        $bid = Bid::with(['buyer'])->findOrFail($id);
        return response()->json($bid, 200);
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
        if (!$bid || !$this->isOwnerOrAdmin($bid->seller_id)) {
            return $this->forbidden();
        }
        if (!Bid::where('id', $id)->where('deal_id', $bid->id)->exists()) {
            return response()->json(['message' => 'Invalid bid for this deal'], 422);
        }
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
        $bid = Bid::findOrFail($id);
        if (!$this->isOwnerOrAdmin($bid->buyer_id, Deal::find($bid->deal_id)?->seller_id)) {
            return $this->forbidden();
        }
        $bid->delete();
        return response()->json(['message' => 'deleted', 'status' => true], 200);
    }
}

