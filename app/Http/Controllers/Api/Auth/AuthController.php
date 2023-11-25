<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        $validate = Validator::make($request->all(),[
             'email' => 'required|email|exists:users',
             'password' => 'required'
        ]);
 
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ],422);
        }
 
        $reqData = request()->only('email','password');
        if (Auth::attempt($reqData)) {
            $user = Auth::user();
             $data['token_type'] = 'Bearer';
             $data['access_token'] = $user->createToken('userToken')->accessToken;
             $data['user'] = $user;
 
            return response()->json($data,200);
        }else {
             $data['loginError'] = "Email or Password Incorrect";
             return response()->json([
                 'errors' => $data
             ],401);
        }
 
     }

     public function signup(Request $request){
        $validate = Validator::make($request->all(),[
            'name' => 'required|min:4',
            'phone' => 'required|max:14|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ],422);
        }

        $reqData = request()->only('name','phone','email','password');
        $reqData['password'] = Hash::make($request->password);
            $user = User::create($reqData);
            Auth::login($user);
             $data['token_type'] = 'Bearer';
             $data['access_token'] = $user->createToken('userToken')->accessToken;
             $data['user'] = $user;
            return response()->json($data,200);
     }



    public function logout(){
        Auth::user()->token()->revoke();
       return response()->json([
           'message' => 'User Logout Success'
       ]);
   }

}
