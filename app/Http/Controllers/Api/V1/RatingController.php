<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rating;

class RatingController extends Controller
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
            'rate' =>'required',
            'description' => 'required',
            'type' => 'required',
            'id' => 'required',
        ]);
        $user = auth()->user();
        $type = '';
        if($request->type == 'shop'){
            $type = 'App\Models\CommissionShop';
        }
    
        $rating = Rating::updateOrInsert(
            ['ratingable_type' => $type, 'ratingable_id' => $request->id, 'user_id' => $user->id],
            ['description' => $request->description, 'rate' => $request->rate, 
            'created_at' =>  now(), 'updated_at' =>now()]
        );
        if($request->type == 'shop'){
            $shop = \App\Models\CommissionShop::find($request->id);
            $shop->rating =  $shop->ratings()->avg('rate');
            $shop->rating_count =  $shop->ratings()->count();
            $shop->save();
        }
        return response()->json($rating, 200);
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

    function getRatings(Request $request){
        $query = null;
        if($request->type == 'shop'){
            $query = \App\Models\CommissionShop::find($request->id)->ratings();
        }
        $query->with(['user' => function($q){
            $q->select(['id','name', 'image']);
        }]);
        return response()->json($query->paginate(), 200);
    }
}
