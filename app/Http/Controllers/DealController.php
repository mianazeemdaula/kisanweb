<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Deal;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'crop');
        
        if ($tab === 'category') {
            $deals = \App\Models\CategoryDeal::with(['bids' => function($q){
                $q->with(['buyer']);
            }, 'user', 'packing', 'weight', 'media', 'subcategory.category'])
            ->whereHas('user')->latest()->paginate();
        } else {
            $deals = Deal::with(['bids' => function($q){
                $q->with(['buyer']);
            }, 'seller', 'packing', 'weight', 'media', 'type.crop'])
            ->whereHas('seller')->latest()->paginate();
        }
        return view('guest.deals.index', compact('deals', 'tab'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->get('type') === 'category') {
            $deal = \App\Models\CategoryDeal::with(['bids' => function($q){
                $q->with(['buyer']);
            }, 'user', 'packing', 'weight', 'media', 'subcategory.category'])
            ->findOrFail($id);
            return view('guest.deals.show_category', compact('deal'));
        }
        
        $deal = Deal::with(['bids' => function($q){
            $q->with(['buyer']);
        }, 'seller', 'packing', 'weight', 'media', 'type.crop'])
        ->findOrFail($id);
        return view('guest.deals.show', compact('deal'));
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
}
