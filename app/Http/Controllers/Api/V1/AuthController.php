<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Address;
use App\Models\SocialAccount;
use \Hash;
use Carbon\Carbon;
use Image;
use Laravel\Socialite\Facades\Socialite;
use WaAPI\WaAPI\WaAPI;
use WaAPI\WaAPISdk\Resources\ExecutedAction;
use MatanYadaev\EloquentSpatial\Objects\Point;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'firebase_uid' => 'required',
        ]);
        $mobile = $request->mobile;
        if(substr($mobile, 0, 2) == '03'){
            $mobile = substr($mobile, 1);
            $mobile = '92'.$mobile;
        }
        $mobile = str_replace('+', '', $mobile);
        $user = User::where('mobile', $mobile)->first();
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
        $mobile = $request->mobile;
        $user = new User();
        $user->name = $request->name;
        $user->mobile = $mobile;
        $user->firebase_uid = $request->firebase_uid;
        $user->type = $request->type;
        $user->image = "https://ui-avatars.com/api/?name=".str_replace(' ', '+', $request->name);
        $user->mobile_verified_at = Carbon::now();
        if($request->has('cnic')){
            $user->cnic = $request->cnic;
        }if($request->has('email')){
            $user->email = $request->email;
        }
        if($request->has('image')){
            $file = $request->image;
            $ext = $file->getClientOriginalExtension();
            $fileName = time().'.'.$ext;
            $path = "profile/".$fileName;
            $image = Image::make($file->getRealPath());
            $image->save($path);
            $user->image = $path;
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
            // $request->request->add(['email' => $socialUser->getEmail()]);
            // $request->validate([
            //     'email' => 'required|unique:users|email',
            // ]);
            $user= User::where('email', $socialUser->getEmail())->first();
            if(!$user){
                $user = new User();
                $user->name = $socialUser->name;
                $user->email = $socialUser->getEmail();
                $user->image = $socialUser->getAvatar();
                $user->email_verified_at = Carbon::now();
                $user->save();
            }
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
        $mobile = $request->mobile;
        if(substr($mobile, 0, 2) == '03'){
            $mobile = substr($mobile, 1);
            $mobile = '92'.$mobile;
        }
        $mobile = str_replace('+', '', $mobile);
        $user = User::where('mobile', $mobile)->first();
        if($user){
            return response()->json(['register'=> true], 200);
        }
        return response()->json(['register'=> false], 200);
    }

    public function socialcallback(Request $request)
    {
        return $request->all();
    }

    public function whatsapp(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
        ]);
        $mobile = str_replace('+', '', $request->mobile);
        $code = rand(100000,999999);
        $waresponse = \App\Helper\WhatsApp::sendOtp($mobile,$code);
        // Log::info($waresponse);
        return response()->json(['code' => $code], 200);
    }
}
