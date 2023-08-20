<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\FeedMillRate;
use App\Models\FeedMill;

class FeedMillRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mills = FeedMill::orderBy('name')->get();
        return view('admin.feedmills.rates.create', compact('mills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'feed_mill_id' => 'required',
            'min' => 'required',
            'max' => 'required',
        ]);
        $lastRate = FeedMillRate::where('feed_mill_id', $request->feed_mill_id)
        ->whereDate('created_at', '<',Carbon::now()->format('Y-m-d'))
        ->orderBy('created_at','desc')->first();
        $feedrate = FeedMillRate::where('feed_mill_id', $request->feed_mill_id)
        ->whereDate('created_at',Carbon::now()->format('Y-m-d'))->first();
        if(!$feedrate){
            $feedrate = new FeedMillRate;
        }
        $feedrate->feed_mill_id = $request->feed_mill_id;
        $feedrate->user_id =  $request->user()->id;
        $feedrate->min_price =  $request->min;
        $feedrate->max_price =  $request->max;
        $feedrate->min_price_last =  $lastRate->min_price ?? $request->min;
        $feedrate->max_price_last =  $lastRate->max_price ?? $request->max;
        $feedrate->save();
        return redirect()->route('admin.feedmills.index');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
