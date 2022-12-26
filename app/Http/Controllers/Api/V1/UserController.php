<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use MatanYadaev\EloquentSpatial\Objects\Point;
use Image;
// Models
use App\Models\User;
use App\Models\Address;

class UserController extends Controller
{
    public function profile()
    {
        $user = User::find(auth()->id());
        $data['user'] = $user;
        $data['addresses'] = $user->addresses;
        return response()->json($data, 200);
    }

    public function updateUser(Request $request)
    {
        try {
            $user= $request->user();
            if($request->has('fcm_token')){
                $user->fcm_token = $request->fcm_token;
            }
            if($request->has('email')){
                $user->email = $request->email;
            }
            if($request->has('lat') && $request->has('lng')){
                if(count($user->addresses) == 0){
                    $address = new Address();
                    $address->user_id = $user->id;
                    $address->name = 'Default';
                    $address->address = $request->address;
                    $address->location = new Point($request->lat, $request->lng);
                    $address->save();
                }
            }
            if($request->has('cnic')){
                $user->cnic = $request->cnic;
            }
            if($request->has('name')){
                $user->name = $request->name;
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
            $data['user'] = $user;
            $data['addresses'] = $user->addresses;
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
