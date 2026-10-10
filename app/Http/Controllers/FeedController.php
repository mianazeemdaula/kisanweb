<?php

namespace App\Http\Controllers;

use App\Models\Feed;

class FeedController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $feed = Feed::with(['user' => function($q){
            $q->select('id', 'name', 'image');
        }, 'media', 'comments' => function($q){
            $q->with(['user' => function($q){
                $q->select('id', 'name', 'image');
            }])->latest()->limit(20);
        }])->withCounts()
        ->whereHas('user')->findOrFail($id);
        return view('guest.feeds.show', compact('feed'));
    }
}
