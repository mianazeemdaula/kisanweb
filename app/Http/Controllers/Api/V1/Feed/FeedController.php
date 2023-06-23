<?php

namespace App\Http\Controllers\Api\V1\Feed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\MediaHelper;

use App\Models\Media;
use App\Models\Feed;
use App\Events\FeedUpdateEvent;


class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = FeedItem::select('feed_items.*')
        // ->selectSub(function ($query) {
        //     $query->from('comments')
        //         ->whereColumn('comments.feed_item_id', 'feed_items.id')
        //         ->selectRaw('COUNT(*)');
        // }, 'comments_count')
        // ->selectSub(function ($query) {
        //     $query->from('likes')
        //         ->whereColumn('likes.feed_item_id', 'feed_items.id')
        //         ->selectRaw('COUNT(*)');
        // }, 'likes_count')
        // ->paginate();
        $data = Feed::with(['user' => function($q){
            $q->select('id','name', 'image');
        }, 'media'])->withCounts()
        ->latest()->paginate();
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
        $validatedData = $request->validate([
            'type' => 'required|string',
            'content' => 'required|string',
        ]);
        $feed = new Feed;
        $feed->user_id = auth()->user()->id;
        $feed->type = $validatedData['type'];
        $feed->content = $validatedData['content'];
        $medias = array();
        $feed->save();
        if($request->has('images')){
            foreach ($request->file('images') as $key => $file) {
                $medias[] = MediaHelper::save($file, $feed);
            }
            foreach ($medias as $img) {
                $feed->media()->save($img);
            }
        }
        FeedUpdateEvent::dispatch($feed->id);
        return response()->json($feed, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Feed::with(['user' => function($q){
            $q->select('id','name', 'image');
        }, 'media'])->withCounts()->findOrFail($id);
        return response()->json($data, 200);
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
        $validatedData = $request->validate([
            'type' => 'required|string',
            'content' => 'required|string',
        ]);
        $feed = Feed::find($id);
        $feed->type = $validatedData['type'];
        $feed->content = $validatedData['content'];
        $medias = array();
        $feed->save();
        $oldImages = json_decode($request->oldimages ?? "[]");
        foreach ($imgId as $oldImages) {
            Media::find($imgId)->delete();
        }
        if($request->has('images')){
            foreach ($request->file('images') as $key => $file) {
                $medias[] = MediaHelper::save($file, $feed);
            }
            foreach ($medias as $img) {
                $feed->media()->save($img);
            }
        }
        return response()->json($feed, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feed = Feed::findOrFail($id)->delete();
        return response()->json($feed, 200);
    }
}
