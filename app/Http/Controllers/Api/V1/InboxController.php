<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Deal;
use App\Models\Chat;
use App\Models\CategoryDeal;
use App\Models\CategoryDealChat;

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
        $data = Chat::with(['deal','buyer','lastmsg'])
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
        $data = Chat::with(['deal','buyer','lastmsg'])->find($id);
        return response()->json($data, 200);
    }

    public function chatType(Request $request)
    {
        $user = auth()->user();
        if ($request->type === 'bids') {
            // Get crop deals with chats where user is buyer
            $chatDealIds = Chat::where('buyer_id', $user->id)
                ->leftJoin('messages', function($join) { 
                    $join->on('messages.chat_id', '=', 'chats.id')
                    ->on('messages.id', '=', \DB::raw("(SELECT max(id) from messages WHERE messages.chat_id = chats.id)"));
                })
                ->orderBy('messages.created_at','desc')->pluck('deal_id');

            $cropDeals = $chatDealIds->isEmpty() ? collect() : Deal::with(['bids' => function($q) use($user) {
                $q->with(['buyer']);
            }, 'seller', 'packing', 'weight' , 'media', 'type.crop'])
            ->whereIn('id', $chatDealIds)
            ->get()
            ->map(function($deal) {
                $deal->deal_type = 'crop';
                return $deal;
            });

            if ($cropDeals->isNotEmpty() && $chatDealIds->isNotEmpty()) {
                $cropDeals = $cropDeals->sortBy(function($deal) use($chatDealIds) {
                    return array_search($deal->id, $chatDealIds->toArray());
                })->values();
            }

            // Get category deals with chats where user is buyer
            $catChatDealIds = CategoryDealChat::where('buyer_id', $user->id)
                ->leftJoin('category_deal_chat_messages', function($join) { 
                    $join->on('category_deal_chat_messages.chat_id', '=', 'category_deal_chats.id')
                    ->on('category_deal_chat_messages.id', '=', \DB::raw("(SELECT max(id) from category_deal_chat_messages WHERE category_deal_chat_messages.chat_id = category_deal_chats.id)"));
                })
                ->orderBy('category_deal_chat_messages.created_at','desc')->pluck('deal_id');

            $catDeals = $catChatDealIds->isEmpty() ? collect() : CategoryDeal::with(['bids' => function($q) use($user) {
                $q->with(['buyer']);
            }, 'user', 'packing', 'weight' , 'media', 'subcategory.category'])
            ->whereIn('id', $catChatDealIds)
            ->get()
            ->map(function($deal) {
                $deal->deal_type = 'category';
                return $deal;
            });

            if ($catDeals->isNotEmpty() && $catChatDealIds->isNotEmpty()) {
                $catDeals = $catDeals->sortBy(function($deal) use($catChatDealIds) {
                    return array_search($deal->id, $catChatDealIds->toArray());
                })->values();
            }

            // Merge and sort by latest first
            $merged = $cropDeals->concat($catDeals)->sortByDesc('created_at')->values();

            // Manual pagination
            $perPage = 15;
            $page = $request->get('page', 1);
            $total = $merged->count();
            $items = $merged->forPage($page, $perPage)->values();

            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $items, $total, $perPage, $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json($data, 200);
        }

        // Default path: seller's own crop deals (normal flow)
        $data = Deal::with(['bids' => function($q) use($user) {
            $q->with(['buyer']);
        }, 'seller', 'packing', 'weight' , 'media', 'type.crop'])
        ->where('seller_id', $user->id)->paginate();

        return response()->json($data, 200);
    }
}
