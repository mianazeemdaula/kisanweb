<?php

namespace App\Http\Controllers\Api\V1\Feed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\MediaHelper;

use App\Models\Media;
use App\Models\Feed;
use App\Events\FeedUpdateEvent;
use App\Jobs\SendFeedNotificationJob;
use Illuminate\Support\Facades\Cache;


class FeedController extends Controller
{
    // Same limit as the app's post field.
    const MAX_POST_LENGTH = 1000;

    /**
     * Counts what the user sees as one character (an Urdu letter with its
     * diacritics, an emoji), the way the app's counter does, so a post the
     * app accepts is never rejected here.
     */
    private function maxPostLength()
    {
        return function ($attribute, $value, $fail) {
            if (is_string($value) && preg_match_all('/\X/u', $value) > self::MAX_POST_LENGTH) {
                $fail('The post may not be longer than '.self::MAX_POST_LENGTH.' characters.');
            }
        };
    }

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
            'content' => ['required', 'string', $this->maxPostLength()],
        ]);
        $feed = null;
        if($request->has('id')){
            $feed = Feed::find($request->id);
            $oldImages = json_decode($request->oldimages ?? "[]");
            foreach ($oldImages as $imgId) {
                Media::find($imgId)->delete();
            }
        }else{
            // Duplicate guard: the same user posting the same content within
            // 10s (double tap / retry on a slow network) gets the existing post
            // back instead of creating another one.
            $userId = auth()->user()->id;
            $lock = Cache::lock('feed-post:'.$userId.':'.sha1($validatedData['content']), 10);
            if (!$lock->get()) {
                $recent = Feed::where('user_id', $userId)
                    ->where('content', $validatedData['content'])
                    ->where('created_at', '>=', now()->subSeconds(10))
                    ->latest()
                    ->first();
                if ($recent) {
                    return response()->json($recent, 200);
                }
                return response()->json(['message' => 'Your post is already being submitted.'], 409);
            }
            $feed = new Feed;
            $feed->user_id = $userId;

        }
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
        SendFeedNotificationJob::dispatch(auth()->user()->name." added post", \Illuminate\Support\Str::limit($validatedData['content'], 150), ['type' => 'feed'], auth()->id())->delay(now()->addSeconds(30));
        // \App\Helper\FCM::sendToSetting(4,auth()->user()->name." added post", substr($validatedData['content'],0,30), ['type' => 'feed']);
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
            'content' => ['required', 'string', $this->maxPostLength()],
        ]);
        $feed = Feed::findOrFail($id);
        if (!$this->isOwnerOrAdmin($feed->user_id)) {
            return $this->forbidden();
        }
        $feed->type = $validatedData['type'];
        $feed->content = $validatedData['content'];
        $medias = array();
        $feed->save();
        $oldImages = json_decode($request->oldimages ?? "[]");
        foreach ((array) $oldImages as $imgId) {
            $feed->media()->find($imgId)?->delete();
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
        $feed = Feed::findOrFail($id);
        if (!$this->isOwnerOrAdmin($feed->user_id)) {
            return $this->forbidden();
        }
        $feed = $feed->delete();
        return response()->json($feed, 200);
    }
}
