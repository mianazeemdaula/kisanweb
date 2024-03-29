<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Reaction;
use App\Models\Deal;
use App\Events\DealUpdateEvent;

use App\Helper\FCM;

class ReactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return auth()->user()->reactions->pluck('id');
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
        $user = $request->user();
        $reaction = Reaction::where('user_id', $user->id)->where('deal_id', $request->deal_id)->first();
        if($reaction){
            $reaction->delete();
        }else{
            $reaction = new Reaction;
            $reaction->user_id = $user->id;
            $reaction->deal_id = $request->deal_id;
            $reaction->save();
            $fcmToken = Deal::find($request->deal_id)->seller->fcm_token;    
            $data =  [
                'type' => 'deal',
                'deal_id' => $request->deal_id,
            ];
            FCM::send([$fcmToken],"Reaction", "$user->name react to your deal", $data);
        }
        DealUpdateEvent::dispatch($request->deal_id);
        return response()->json($reaction, 200,);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
