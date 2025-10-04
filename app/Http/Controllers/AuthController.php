<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {   
        $request->validate([
            'name' => 'required | string | max:255',
            'username' =>'required | string | max:50 | unique:users,username',
            'email'=> 'required | email | unique:users',
            'password' => 'required | string | min:6 | confirmed',
           
        ]);

        $user = User::create([
            'name' =>$request->name,
            'username' =>$request->username,
            'email' =>$request->email,
            'password' => Hash::make($request->password),
            
        ]);
        return response()->json([
            'message' => 'Account Created Successfully',
            'user' => $user,
        ],201);
    }

    public function login(Request $request){
        $request->validate([
            'email'=> 'required | string',
            'password'=> 'required | string',
        ]);
        $user = User::where('email',$request->email)->first();
        if(!$user || Hash::check($request->password,$user->password))
        {
            throw ValidationException::withMessages([
                'message' => 'Invalid Email or password',
            ]);
        }
        $token = $user->createToken('Auth_token')->plainTextToken;
        return response()->json([
            'message' => 'login successfully',
            'access_token'=>$token,
            'token_type' => 'bearer',
            'user' => $user,
        ],200);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'messsage' => 'Logout Successfully'
        ]);
    }
}
