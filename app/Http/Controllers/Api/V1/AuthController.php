<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SocialAccount;
use \Hash;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('mobile', $request->mobile)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['email' => 'The provided credentials are incorrect.'], 204); 
        }
        $token = $user->createToken('login')->plainTextToken;
        $data['token'] = $token;
        $data['user'] = $user;
        return response()->json($data, 200);
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mobile' => 'required|unique:users',
            'password' => 'required',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->password = bcrypt($request->name);
        $user->save();
        $token = $user->createToken('login')->plainTextToken;
        $data['token'] = $token;
        $data['user'] = $user;
        return response()->json($data, 200);
    }

    public function loginFromSocail(Request $request)
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
            'ui' => $socialUser->getId(),
        ], [
            'username' => $socialUser->name,
        ]);
        if(!$social->user){
            $user = new User();
            $user->name = $socialUser->name;
            $user->email = $socialUser->getEmail();
            $user->image = $socialUser->getAvatar();
            // $user->password = bcrypt($request->password);
            $user->save();
        }else{
            $user = $social->user;
        }
        $data['token'] = $user->createToken('login')->plainTextToken;
        $data['user'] = $user;
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
}
