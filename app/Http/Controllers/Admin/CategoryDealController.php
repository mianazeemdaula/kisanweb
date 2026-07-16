<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryDeal;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\CommissionShop;
use App\Models\Packing;
use App\Models\WeightType;

class CategoryDealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deals = CategoryDeal::latest()->paginate();
        return view('admin.category_deals.index', compact('deals'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deal = CategoryDeal::with(['bids'])->findOrFail($id);
        
        $shops = [];
        if ($deal->location) {
            $shops = CommissionShop::query()->orderByDistance('location', $deal->location)
                ->withDistance('location', $deal->location)
                ->whereDistanceSphere('location', $deal->location, '<=', 25 * 1000)
                ->whereActive(true)
                ->with(['city', 'user'])
                ->get();
        }
        
        return view('admin.category_deals.show', compact('deal', 'shops'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::with('subcategories')->get();
        $weights = WeightType::all();
        $packings = Packing::all();
        $deal = CategoryDeal::findOrFail($id);
        return view('admin.category_deals.edit', compact('deal', 'categories', 'weights', 'packings'));
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
            'sub_category_id' => 'required',
            'weight_type_id' => 'required',
            'packing_id' => 'required',
            'demand' => 'required',
            'qty' => 'required',
            'note' => 'required',
        ]);
        $deal = CategoryDeal::findOrFail($id);
        $deal->sub_category_id = $request->sub_category_id;
        $deal->weight_type_id = $request->weight_type_id;
        $deal->packing_id = $request->packing_id;
        $deal->demand = $request->demand;
        $deal->qty = $request->qty;
        $deal->note = $request->note;
        $deal->save();
        return redirect()->route('admin.category-deals.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deal = CategoryDeal::findOrFail($id);
        $deal->delete();
        return redirect()->back();
    }
}
