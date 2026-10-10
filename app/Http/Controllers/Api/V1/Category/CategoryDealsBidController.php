<?php

namespace App\Http\Controllers\Api\V1\Category;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Events\DealUpdateEvent;
// Models
use App\Models\CategoryDealBid;
use App\Models\CategoryDeal;
use App\Helper\FCM;

class CategoryDealsBidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bids = CategoryDealBid::with(['buyer', 'categoryDeal'])->paginate();
        return response()->json($bids, 200);
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
        $dealId = $request->deal_id;
        // One bid per buyer and deal, even if two requests arrive together
        // (double tap, retry on a slow network).
        $bid = Cache::lock('cat-bid:'.$dealId.':'.$user->id, 10)->block(5, function () use ($request, $user, $dealId) {
            $bid = CategoryDealBid::where('category_deal_id', $dealId)->where('buyer_id', $user->id)->first();
            if(!$bid){
                $bid = new CategoryDealBid();
                $bid->category_deal_id = $dealId;
                $bid->buyer_id = $user->id;
            }
            $bid->bid_price = $request->bid_price;
            $bid->save();
            return $bid;
        });
        // Notifications run after the response is sent, so the buyer is not
        // kept waiting for them.
        $userId = $user->id;
        $userName = $user->name;
        app()->terminating(function () use ($dealId, $userId, $userName) {
            try {
                $deal = CategoryDeal::find($dealId);
                if($deal){
                    $data =  [
                        'type' => 'cat_deal',
                        'id' => $deal->id,
                        'deal_id' => $deal->id,
                    ];
                    \App\Jobs\ActivityNotificationJob::dispatch([$deal->user_id], "Bid", "$userName bid on your deal", $data, 3, $userId);
                }
                \App\Jobs\CategoryBidNotificationJob::dispatch($dealId, $userId);
            } catch (\Throwable $e) {
                report($e);
            }
        });
        return response()->json($bid, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bid = CategoryDealBid::with(['buyer', 'categoryDeal'])->findOrFail($id);
        return response()->json($bid, 200);
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
        $deal = CategoryDeal::find($request->deal_id);
        if (!$deal || !$this->isOwnerOrAdmin($deal->user_id)) {
            return $this->forbidden();
        }
        if (!CategoryDealBid::where('id', $id)->where('category_deal_id', $deal->id)->exists()) {
            return response()->json(['message' => 'Invalid bid for this deal'], 422);
        }
        if($deal->accept_bid_id != null){
            return response()->json(['message'=>'You have already accepted'], 409);
        }
        $deal->status = 'accepted';
        $deal->accept_bid_id = $id;
        $deal->save();
        // DealUpdateEvent::dispatch($request->deal_id);
        return response()->json($deal, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bid = CategoryDealBid::findOrFail($id);
        if (!$this->isOwnerOrAdmin($bid->buyer_id, CategoryDeal::find($bid->category_deal_id)?->user_id)) {
            return $this->forbidden();
        }
        $bid->delete();
        return response()->json(['message' => 'deleted', 'status' => true], 200);
    }
}

