<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Deal;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $data =  Review::with(['reviewer', 'user'])->where('user_id', $user->id)->orWhere('review_by', $user->id)->paginate();
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
            'deal_id' =>'required',
            'content' => 'required',
            'rating' => 'required',
        ]);
        $user = auth()->user();
        $deal = Deal::findOrFail($request->deal_id);
        if($deal->accept_bid_id){
            $type = 0;
            $review = new Review();
            // seller to buyer review
            if($user->id == $deal->seller_id){
                $review->user_id = $deal->acceptedBid->buyer->id;
                $review->review_by = $user->id;
            }else{
                $review->user_id = $deal->seller_id;
                $review->review_by = $user->id;
                $type = 1;
            }
            $data = Review::where('user_id',$review->user_id)
            ->where('review_by', $user->id)
            ->where('deal_id', $request->deal_id)->first();
            if($data){
                return response()->json(['message'=> 'Review already done'], 409);
            }
            $review->content = $request->content;
            $review->rating = $request->rating;
            $review->deal_id = $request->deal_id;
            $review->type = $type;
            $review->save();
            $request->json($review,200);
        }else{
            return response()->json(['message'=> 'Deal not accepted'], 409);
        }
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

    public function history(Request $request)
    {
        $user = auth()->user();
        $query =  Review::with(['reviewer', 'user']);
        if($request->type && $request->type == 1){
            $query->where('user_id', $user->id);
        }else{
            $query->where('review_by', $user->id);
        }
        $data = $query->paginate();
        return response()->json($data, 200);
    }
}
