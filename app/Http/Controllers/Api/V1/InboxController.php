<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Deal;

class InboxController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = Deal::with(['bids' => function($q) use($user) {
            $q->with(['buyer'])->where('buyer_id', $user->id);
        }, 'seller', 'packing', 'weight' , 'media', 'type.crop'])->where('seller_id', $user->id)->paginate();
        return response()->json($data, 200);
    }
}
