<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use MatanYadaev\EloquentSpatial\Objects\Point;
// Models
use App\Models\Crop;
use App\Models\Deal;

class HomeController extends Controller
{
    public function crops()
    {
        $data = Crop::with('types')->has('types')->where('active', true)
        ->orderBy('sort')->get();
        return response()->json($data, 200);
    }

    public function popular(Request $reqeust)
    {
        $query = Deal::query();
        if($reqeust->crop){
            $query->whereHas('type', function($query) use($reqeust) {
                $query->where('crop_id', $reqeust->crop);
            });
        }
        if($reqeust->lat && $reqeust->lng){
            $point = new Point($reqeust->lat, $reqeust->lng, 4326);
            $query->whereDistance('location', $point , '<', 10)->count();
        }
        if($reqeust->sortype){
            // $query->orderBy();
        }else{
            $query->orderBy('id', 'desc');
        }
        $data = $query->with(['bids' => function($q){
            $q->with(['buyer'])->whereHas('buyer');
        }, 'seller', 'packing', 'weight', 'media', 'type.crop'])
        ->whereHas('seller')
        ->where('status','!=', 'accepted')
        ->paginate();
        return response()->json($data, 200);
    }

    public function userDeals()
    {
        $user = auth()->user();
        $data = Deal::with(['bids' => function($q) use($user) {
            $q->with(['buyer'])->where('buyer_id', $user->id);
        }, 'seller', 'packing', 'weight' , 'media', 'type.crop'])->where('seller_id', $user->id)->paginate();
        return response()->json($data, 200);
    }

    public function wamessage(Request $request)
    {   Log::debug($request->all());
        return response()->json($request->hub_challenge ?? 'Thank you for testing', 200);
    }
}
