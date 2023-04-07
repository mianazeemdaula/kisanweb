<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CommissionShop;

class CommissionShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $request->validate([
            'name' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'address' => 'required',
            'image' => 'required',
        ]);
        $shop = new CommissionShop();
        $shop->user_id = $request->user()->id;
        $shop->name = $request->name;
        $shop->about = $request->about;
        $shop->image = "https://ui-avatars.com/api/?name=".str_replace(' ', '+', $request->name);
        $shop->address = $request->address;
        $shop->location = new Point($request->lat, $request->lng);
        $socials = [];
        if($request->facebook){
            $socials['facebook'] = $request->facebook;
        }if($request->email){
            $socials['email'] = $request->email;
        }if($request->web){
            $socials['web'] = $request->web;
        }
        $shop->social_links = $socials;
        $shop->save();
        return response()->json($data, 200);
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
        // $user = User::find(1);
        // $socialLinks = $user->social_links ?? [];
        // $socialLinks['linkedin'] = 'https://www.linkedin.com/in/user-profile/';
        // $user->social_links = $socialLinks;
        // $user->save();
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
