<?php

namespace App\Http\Controllers\Api\V1\Feed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feed;
use App\Models\FeedComment;
use App\Events\FeedUpdateEvent;

class FeedCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($feed)
    {
        $data = FeedComment::with('user')->where('feed_id', $feed)->get(); 
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
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = new FeedComment;
        $comment->user_id = auth()->user()->id;
        $comment->feed_id = $feed;
        $comment->content = $request->content;
        $comment->save();
        FeedUpdateEvent::dispatch($this->getFeedData($feed));
        return response()->json($comment, 200);
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
        $this->authorize('delete', $comment);

        $comment->delete();
    }

    public function getFeedData($id)
    {
        return Feed::withCounts()->with(['user' => function($q){
            $q->select('id','name', 'image');
        }, 'media'])->find($id);
    }
}
