<?php

namespace App\Http\Controllers\Api\V1\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CategoryDeal;
use App\Helper\MediaHelper;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;

class CategoryDealsController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'sub_cat_id' => 'required|integer',
            'attr' => 'sometimes|array',
            'demand' => 'required',
            'note' => 'required',
            'qty' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'address' => 'required',
            'images' => 'required',
            'images.*' => 'required|mimes:jpg,jpeg,png',
        ]);
        $deal = CategoryDeal::where('sub_category_id', $request->sub_cat_id)
        ->where('user_id', $request->user()->id)
        ->where('qty', $request->qty)
        ->where('demand', $request->demand)->first();

        if($deal){
            return  response()->json(['message' => 'Deal already inprocess'], 422);
        }
        try {
            DB::beginTransaction();
            $deal = new CategoryDeal();
            $deal->user_id = $request->user()->id;
            $deal->sub_category_id = $request->sub_cat_id;
            $deal->demand = $request->demand;
            $deal->note = $request->note;
            $deal->qty = $request->qty;
            $deal->packing_id = $request->packing_id;
            $deal->weight_type_id = $request->weight_type_id;
            $deal->location = new Point($request->lat,$request->lng);
            $deal->address = $request->address;

            // attributes
            if($request->has('attr') && is_array($request->attr)){
                $deal->attr = $request->attr;
            }

            $medias = array();
            foreach ($request->file('images') as $key => $file) {
                $medias[] = MediaHelper::save($file, $deal);
            }
            $deal->save();
            foreach ($medias as $img) {
                $deal->media()->save($img);
            }
            DB::commit();
            return  response()->json($deal, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return  response()->json(['message' => $th->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deal = CategoryDeal::with(['bids' => function($q){
            $q->with(['buyer']);
        }, with(['bids' => function($q){
            $q->with(['buyer'])->whereHas('buyer');
        }, 'user', 'media', 'subcategory.category'])
        ])->whereHas('user')->findOrFail($id);
        return response()->json($deal);
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
