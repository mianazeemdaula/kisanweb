<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function popular(Reqeust $reqeust)
    {
        $data = Deal::with(['bids' => function($q){
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
