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
        $chatDealIds = Chat::where('buyer_id', $user->id)->pluck('deal_id');
        $data = Deal::with(['bids' => function($q) use($user) {
            $q->with(['buyer']);
        }, 'seller', 'packing', 'weight' , 'media', 'type.crop'])
        ->whereHas('chats')
        ->where('seller_id', $user->id)->orWhereIn('id', $chatDealIds)->paginate();
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'deal_id' => 'required',
            'buyer_id' => 'required',
        ]);
        $chat = Chat::where('deal_id', $request->deal_id)->where('buyer_id', $request->buyer_id)->first();
        if(!$chat){
            $chat = new Chat();
            $chat->deal_id = $request->deal_id;
            $chat->buyer_id = $request->buyer_id;
            $chat->save();
        }
        $data = Chat::with(['deal','buyer'])->find($chat->id);
        return response()->json($data, 200);
    }

    public function show($id)
    {
        $data = Chat::with(['deal','buyer','lastmsg'])->where('deal_id',$id)->paginate();
        return response()->json($data, 200);
    }

    public function getChat($id)
    {
        $data = Chat::with(['deal','buyer','lastmsg'])->find($id);
        return response()->json($data, 200);
    }
}
