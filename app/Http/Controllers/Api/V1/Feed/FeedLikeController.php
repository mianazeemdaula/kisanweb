<?php

namespace App\Http\Controllers\Api\V1\Feed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feed;
use App\Models\FeedLike;
use App\Events\FeedUpdateEvent;

class FeedLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($feed)
    {
        $data = FeedLike::with('user')->where('feed_id', $feed)->get(); 
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
    public function store(Request $request, $feed)
    {
        $userId = auth()->user()->id;
        $like = FeedLike::where('user_id',$userId )->where('feed_id', $feed)->first();
        if($like){
            $like->delete();
        }else{
            $like = new FeedLike;
            $like->user_id = 
            $like->feed_id = $feed;
            $like->save();
        }
        // update feed pusher
        FeedUpdateEvent::dispatch($this->getFeedData($feed));
        return response()->json($like, 200);
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
        $like = Like::where('user_id', auth()->user()->id)
                    ->where('feed_id', $feed->id)
                    ->firstOrFail();

    }
    
    public function getFeedData($id)
    {
        return Feed::with(['user' => function($q){
            $q->select('id','name', 'image');
        }, 'media', 'scopeWithCounts'])->find($id);
    }
}
