<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Deal;
use App\Models\Chat;

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

    public function store(Request $request)
    {
        $this->validate($request,[
            'deal_id' => 'required',
            'buyer_id' => 'required',
        ]);
        $chat = Chat::where('deal_id', $request->deal_id)->where('buyer_id', $buyer_id)->first();
        if(!$chat){
            $chat = new Chat();
            $chat->deal_id = $request->deal_id;
            $chat->deal_id = $request->deal_id;
            $chat->save();
        }
        $data = Chat::with(['deal','buyer'])->find($chat->id);
        return response()->json($data, 200);
    }
}
