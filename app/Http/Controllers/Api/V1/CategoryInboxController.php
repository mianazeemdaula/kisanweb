<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CategoryDeal;
use App\Models\CategoryDealChat;

class CategoryInboxController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $chatDealIds = CategoryDealChat::where('buyer_id', $user->id)->pluck('deal_id');
        $data = CategoryDeal::with(['bids' => function($q) use($user) {
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
        $chat = CategoryDealChat::where('deal_id', $request->deal_id)->where('buyer_id', $request->buyer_id)->first();
        if(!$chat){
            $chat = new CategoryDealChat();
            $chat->deal_id = $request->deal_id;
            $chat->buyer_id = $request->buyer_id;
            $chat->save();
        }
        $data = Chat::with(['deal','buyer'])->find($chat->id);
        return response()->json($data, 200);
    }

    public function show($id)
    {
        $data = CategoryDealChat::with(['deal','buyer','lastmsg'])
        ->leftJoin('messages', function($join) { 
            $join->on('messages.chat_id', '=', 'chats.id')
            ->on('messages.id', '=', \DB::raw("(SELECT max(id) from messages WHERE messages.chat_id = chats.id)"));
        })
        ->orderBy('messages.created_at','desc')
        ->select('chats.*')
        ->whereHas('lastmsg')
        ->where('chats.deal_id',$id)->paginate();
        return response()->json($data, 200);
    }

    public function getChat($id)
    {
        $data = CategoryDealChat::with(['deal','buyer','lastmsg'])->find($id);
        return response()->json($data, 200);
    }

    public function chatType(Request $request)
    {
        $user = auth()->user();
        $query = CategoryDeal::with(['bids' => function($q) use($user) {
            $q->with(['buyer']);
        }, 'seller', 'packing', 'weight' , 'media', 'type.crop'])
        ->whereHas('chats');
        if($request->type == 'bids'){
            $chatDealIds = CategoryDealChat::where('buyer_id', $user->id)
            ->leftJoin('messages', function($join) { 
                $join->on('messages.chat_id', '=', 'chats.id')
                ->on('messages.id', '=', \DB::raw("(SELECT max(id) from messages WHERE messages.chat_id = chats.id)"));
            })
            ->orderBy('messages.created_at','desc')->pluck('deal_id');
            $query->whereIn('id', $chatDealIds)
            ->orderByRaw('FIELD(id, '.$chatDealIds->implode(',').')')
            ;
        }else{
            $query->where('seller_id', $user->id);
        }
        $data = $query->paginate();
        return response()->json($data, 200);
    }
}
