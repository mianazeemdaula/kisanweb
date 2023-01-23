<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Models\SocialAccount;
use \Hash;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;


use MatanYadaev\EloquentSpatial\Objects\Point;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'firebase_uid' => 'required',
        ]);
        $user = User::where('mobile', $request->mobile)->where('firebase_uid', $request->firebase_uid)->first();
        // if (! $user || ! Hash::check($request->password, $user->password)) {
        if (! $user) {
            return response()->json(['email' => 'The provided credentials are incorrect.'], 204); 
        }
        if($request->has('fcm_token')){
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }
        $token = $user->createToken('login')->plainTextToken;
        $data['token'] = $token;
        $data['user'] = $user;
        $data['addresses'] = $user->addresses;
        return response()->json($data, 200);
    }

    public function phoneSignup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mobile' => 'required|unique:users',
            'cnic' => 'sometimes|unique:users|min:13',
            'email' => 'sometimes|unique:users|email',
            'lat' => 'required',
            'lng' => 'required',
            'address' => 'required',
            'firebase_uid' => 'required',
            'type' => 'required',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->firebase_uid = $request->firebase_uid;
        $user->type = $request->type;
        $user->image = "https://ui-avatars.com/api/?name=".str_replace(' ', '+', $request->name);
        $user->mobile_verified_at = Carbon::now();
        if($request->has('cnic')){
            $user->cnic = $request->cnic;
        }if($request->has('email')){
            $user->email = $request->email;
        }
        $user->save();
        if($request->has('lat') && $request->has('lng')){
            $address = new Address();
            $address->user_id = $user->id;
            $address->name = 'Default';
            $address->address = $request->address;
            $address->location = new Point($request->lat, $request->lng);
            $address->save();
        }
        $token = $user->createToken('login')->plainTextToken;
        $data['token'] = $token;
        $data['user'] = $user;
        $data['addresses'] = $user->addresses;
        return response()->json($data, 200);
    }

    public function loginFromSocial(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'provider' => 'required',
        ]);
        $provider = $request->provider;
        $token = $request->token;
        $socialUser = Socialite::driver($provider)->userFromToken($token);
        $social = SocialAccount::updateOrCreate([
            'provider' => $provider,
            'uid' => $socialUser->getId(),
        ]);
        if(!$social->user){
            $request->request->add(['email' => $socialUser->getEmail()]);
            $request->validate([
                'email' => 'required|unique:users|email',
            ]);
            $user = new User();
            $user->name = $socialUser->name;
            $user->email = $socialUser->getEmail();
            $user->image = $socialUser->getAvatar();
            $user->email_verified_at = Carbon::now();
            $user->save();
        }else{
            $user = $social->user;
        }
        $social->user_id = $user->id;
        $social->save();
        $data['token'] = $user->createToken('login')->plainTextToken;
        $data['user'] = $user;
        $data['addresses'] = $user->addresses;
        return response()->json($data, 200);
    }

    public function mobileRegister(Request $request)
    {
        $user = User::where('mobile', $request->mobile)->first();
        if($user){
            return response()->json(['register'=> true], 200);
        }
        return response()->json(['register'=> false], 200);
    }

    public function socialcallback(Request $request)
    {
        return $request->all();
    }
}
