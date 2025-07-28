<?php

namespace App\Http\Controllers\Api\V1\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CategoryDeal;
use App\Models\CategoryDealReaction;
use App\Helper\FCM;

class CategoryDealReactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return auth()->user()->catDealreactions->pluck('id');
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
        $user = $request->user();
        $reaction = CategoryDealReaction::where('user_id', $user->id)->where('category_deal_id', $request->deal_id)->first();
        if($reaction){
            $reaction->delete();
        }else{
            $reaction = new CategoryDealReaction;
            $reaction->user_id = $user->id;
            $reaction->category_deal_id = $request->deal_id;
            $reaction->save();
            $fcmToken = CategoryDeal::find($request->deal_id)->user->fcm_token;    
            $data =  [
                'type' => 'deal',
                'deal_id' => $request->deal_id,
            ];
            FCM::send([$fcmToken],"Reaction", "$user->name react to your deal", $data);
        }
        // DealUpdateEvent::dispatch($request->deal_id);
        return response()->json($reaction, 200,);
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
