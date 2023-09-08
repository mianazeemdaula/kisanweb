<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deal;
use App\Models\Crop;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DealsExport;
use App\Exports\DealNearByShopsExport;
use App\Models\CommissionShop;


class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deals = Deal::latest()->paginate();
        return view('admin.deals.index', compact('deals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.deals.create');
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
            'quote' => 'required',
        ]);
        $quote = new Deal;
        $quote->quote = $request->quote;
        $quote->save();
        return redirect()->route('feeds.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deal = Deal::with(['bids'])->findOrFail($id);
        $shops =  CommissionShop::query()->orderByDistance('location',$deal->location)
        ->withDistance('location', $deal->location)->whereDistanceSphere('location',$deal->location, '<=', 25000)
        ->whereActive(true)->with(['city', 'user'])->get();
        return view('admin.deals.show', compact('deal','shops'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $crops = Crop::with('types')->get();
        $weights = \App\Models\WeightType::all();
        $packings = \App\Models\Packing::all();
        $deal = Deal::findOrFail($id);
        return view('admin.deals.edit', compact('deal','crops','weights','packings'));
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
        $request->validate([
            'crop_type_id' => 'required',
            'weight_type_id' => 'required',
            'packing_id' => 'required',
            'demand' => 'required',
            'qty' => 'required',
            'note' => 'required',
        ]);
        $deal = Deal::findOrFail($id);
        $deal->crop_type_id = $request->crop_type_id;
        $deal->weight_type_id = $request->weight_type_id;
        $deal->packing_id = $request->packing_id;
        $deal->demand = $request->demand;
        $deal->qty = $request->qty;
        $deal->note = $request->note;
        $deal->save();
        return redirect()->route('admin.deals.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deal = Deal::findOrFail($id);
        $deal->delete();
        return redirect()->back();
    }

    function export() {
        return Excel::download(new DealsExport, 'deals.xlsx');
    }

    function exportNearBuyers($id) {
        return Excel::download(new DealNearByShopsExport($id), $id.'-buyers.xlsx');
    }
}
