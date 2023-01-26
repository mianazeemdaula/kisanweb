<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Events\ChatMessageEvent;
// Models
use App\Models\Message;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = auth()->user();
        $dealIds = Deal::where('seller_id', $auth->id)->modelKeys();
        $data = Chat::where('buyer_id',$auth->id)
        ->orWhereIn('deal_id', $dealIds)
        ->with(['deal'])
        ->join('messages','messages.chat_id','chats.id')
        ->orderBy('messages.created_at','desc')
        // ->groupBy('conversations.conversation_id')
        ->paginate();
        return $data;
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
        try {
            $this->validate($request,[
                'chat_id' => 'required',
                'message' => 'required',
                'type' => 'required'
            ]);
            $user = $request->user();
            $message = new Message();
            $message->chat_id = $request->chat_id;
            $message->sender_id = $user->id;
            $message->message = $request->message;
            $message->type = $request->type;
            $message->save();
            \App\Jobs\ChatMessageJob::dispatch($message->id);
            ChatMessageEvent::dispatch($message);
            return response()->json($message, 200);
        } catch (\Throwable $th) {
            throw $th;
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
        $data = Message::where('chat_id',$id)->orderBy('created_at','desc')->paginate();
        return response()->json($data, 200);

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
        //
    }
}
