<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use MatanYadaev\EloquentSpatial\Objects\Point;
use App\Helper\MediaHelper;
use App\Events\DealUpdateEvent;
use App\Jobs\CreateDealJob;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Deal;
use App\Models\Bid;
use App\Models\Notification;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Deal::with(['bids' => function($q){
            $q->with(['buyer']);
        }, 'seller', 'packing', 'weight', 'media', 'type.crop'])
        ->whereHas('seller')->paginate();
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
        $this->validate($request,[
            'crop_id' => 'required|integer',
            'crop_type_id' => 'required|integer',
            'packing_id' => 'sometimes|integer',
            'weight_type_id' => 'required',
            'demand' => 'required',
            'note' => 'required',
            'qty' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'address' => 'required',
            'images' => 'required',
            'images.*' => 'required|mimes:jpg,jpeg,png',
        ]);
        try {
            DB::beginTransaction();
            $deal = new Deal();
            $deal->seller_id = $request->user()->id;
            $deal->crop_type_id = $request->crop_type_id;
            $deal->packing_id = $request->packing_id;
            $deal->demand = $request->demand;
            $deal->weight_type_id = $request->weight_type_id;
            $deal->note = $request->note;
            $deal->qty = $request->qty;
            $deal->location = new Point($request->lat,$request->lng);
            $deal->address = $request->address;
            $deal->save();
            foreach ($request->file('images') as $key => $file) {
                MediaHelper::save($file, $deal);
            }
            DealUpdateEvent::dispatch($deal->id);
            CreateDealJob::dispatch($deal->id);
            DB::commit();
            return  response()->json($deal, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return  response()->json($deal, 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deal = Deal::with(['bids' => function($q){
            $q->with(['buyer']);
        }, 'seller', 'packing', 'weight' ,'media', 'type.crop'])->find($id);
        if($deal){
            $deal->visit();
            return response()->json($deal, 200);
        }
        return response()->json(['message' => 'Deal is removed'], 204);
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
        Deal::find($id)->delete();
        return response()->json(['message'=>'deleted', 'status'=>true], 200, []);
    }

    public function history(Request $request)
    {
        $user = auth()->user();
        $query = $data = Deal::with(['bids' => function($q){
            $q->with(['buyer']);
        }, 'seller', 'packing', 'weight', 'media', 'type.crop', 'reviews']);
        if($request->type && $request->type == 1){
            $bidsDealIds = Bid::where('buyer_id', $user->id)->pluck('deal_id');
            $query->whereIn('id', $bidsDealIds);
        }else{
            $query->where('seller_id', $user->id);
        }
        $data = $query->orderBy('id','desc')->paginate();
        return response()->json($data, 200);
    }
}
