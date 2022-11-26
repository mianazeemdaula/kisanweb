<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use \Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['email' => 'The provided credentials are incorrect.'], 204); 
        }
        $token = $user->createToken('login')->plainTextToken;
        $data['token'] = $token;
        $data['user'] = $user;
        return response()->json($data, 200);
    }
}
