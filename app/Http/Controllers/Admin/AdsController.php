<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Advertisement;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ads = Advertisement::paginate();
        return view('admin.ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'title_ur' => 'required',
            'image' => 'required|image',
            'link' => 'required',
            'action' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'view_km' => 'required',
        ]);
        $ad = new Advertisement();
        $ad->title = $request->title;
        $ad->title_ur = $request->title_ur;
        $ad->link = $request->link;
        $ad->action = $request->action;
        $ad->start_date = $request->start_date;
        $ad->end_date = $request->end_date;
        $ad->location = new Point($request->lat, $request->lng);
        $ad->view_km = $request->view_km;
        $ad->slug = Str::slug($request->title);
        $ad->user_id = auth()->user()->id;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $location = public_path('ads/'.$filename);
            Image::make($image)->save($location);
            $ad->image = $filename;
        }
        $ad->save();
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ad = Advertisement::findOrFail($id);
        return view('admin.ads.show', compact('ad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ad = Advertisement::findOrFail($id);
        return view('admin.ads.edit', compact('ad'));
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
            'start_date' => 'required',
            'end_date' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'view_km' => 'required',
        ]);
        $ad = Advertisement::findOrFail($id);
        $ad->title = $request->title;
        $ad->title_ur = $request->title_ur;
        $ad->link = $request->link;
        $ad->action = $request->action;
        $ad->start_date = $request->start_date;
        $ad->end_date = $request->end_date;
        $ad->location = new Point($request->lat, $request->lng);
        $ad->view_km = $request->view_km;
        $ad->slug = Str::slug($request->title);
        if($request->hasFile('image')){
            File::delete(public_path('ads/'.$ad->image));
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $location = public_path('ads/'.$filename);
            Image::make($image)->save($location);
            $ad->image = $filename;
        }
        $ad->save();
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ad = Advertisement::findOrFail($id);
        File::delete(public_path('ads/'.$ad->image));
        $ad->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement deleted successfully.');
    }
}
