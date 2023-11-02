<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Support\Facades\DB;
use Image;

use App\Models\Advertisement;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user  = auth()->user();
        if($user){
            $address = $user->addresses()->whereDefault(true)->first();
            $ads = Advertisement::query()->active()
            ->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'))
            ->viewRange($address->location->latitude, $address->location->longitude)
            ->orderBy('position', 'asc')->take(5)->get();
            // increase the impression of ads by 1 in bulk
            Advertisement::whereIn('id', $ads->pluck('id'))->increment('impressions', 1);
            return response()->json($ads, 200);
        }
        return response()->json([]);
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
        $request->validate([
            'title' => 'required',
            'title_ur' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'required',
            'action' => 'required',
            'action_text' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'view_km' => 'required',
        ]);
        $ad = new Advertisement();
        $ad->user_id = auth()->id();
        $ad->title = $request->title;
        $ad->title_ur = $request->title_ur;
        $ad->slug = Str::slug($request->title);
        $ad->link = $request->link;
        $ad->action = $request->action;
        $ad->action_text = $request->action_text;
        $ad->start_date = $request->start_date;
        $ad->end_date = $request->end_date;
        $ad->location = new Point($request->lat, $request->lng);
        $ad->view_km = $request->view_km;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('ads'), $filename);
            $ad->image = "ads/".$filename;
        }
        $ad->save();
        return response()->json($ad, 200);
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
        $request->validate([
            'title' => 'required',
            'title_ur' => 'required',
            'link' => 'required',
            'action' => 'required',
            'action_text' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'view_km' => 'required',
        ]);
        $ad = Advertisement::find($id);
        $ad->title = $request->title;
        $ad->title_ur = $request->title_ur;
        $ad->slug = Str::slug($request->title);
        $ad->link = $request->link;
        $ad->action = $request->action;
        $ad->action_text = $request->action_text;
        $ad->location = new Point($request->lat, $request->lng);
        $ad->view_km = $request->view_km;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('ads'), $filename);
            $ad->image = "ads/".$filename;
        }
        $ad->save();
        return response()->json($ad, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
