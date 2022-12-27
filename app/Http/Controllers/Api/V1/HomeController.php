<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use MatanYadaev\EloquentSpatial\Objects\Point;
// Models
use App\Models\Crop;
use App\Models\Deal;

class HomeController extends Controller
{
    public function crops()
    {
        $data = Crop::with('types')->get();
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
          return  $query->whereDistance('location', $point , '<', 10)->count();
        }
        $data = $query->with(['bids' => function($q){
            $q->with(['buyer']);
        }, 'seller', 'packing', 'media', 'type.crop'])->paginate();
        return response()->json($data, 200);
    }

    public function latest()
    {
        $data = Deal::with(['bids'])->orderBy('id','desc')->paginate();
        return response()->json($data, 200);
    }
}
