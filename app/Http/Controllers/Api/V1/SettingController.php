<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserSetting;
use App\Models\Setting;
use App\Helper\FCM;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserSetting::where('user_id', auth()->id())->get();
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
        $setting = Setting::where('name', $request->name)->first();
        if($setting){
            $userSetting =  UserSetting::where('user_id', $user->id())->where('setting_id', $setting->id)
            ->first();
            if($userSetting){
                $userSetting->value = $request->value;
                $userSetting->save();
            }else{
                $userSetting = new UserSetting;
                $userSetting->user_id = $user->id;
            }
        }
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
        $setting = Setting::where('name', $request->name)->first();
        $data =  UserSetting::updateOrCreate([
            'user_id' => $request->user()->id,
            'setting_id' => $setting->id,
        ],[
            'value' => $request->value,
        ]);
        return $this->index();
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
