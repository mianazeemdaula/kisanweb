<?php

namespace App\Http\Controllers\Api\V1\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\MediaHelper;

use App\Models\Support;
use App\Models\Media;
use App\Events\FeedUpdateEvent;


class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Support::with(['user' => function($q){
            $q->select('id','name', 'image');
        }, 'detail'])->latest()->paginate();
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
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $support = new Support();
        $support->title = $request->title;
        $support->user_id = $request->user()->id;
        $support->save();
        $support->details()->insert([
            'content' => $request->content,
            'user_id' => $request->user()->id,
        ]);
        return response()->json($support, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Support::with(['user' => function($q){
            $q->select('id','name', 'image');
        }, 'detail'])->findOrFail($id);
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
        $feed = Support::findOrFail($id)->delete();
        return response()->json($feed, 200);
    }
}
