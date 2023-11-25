<?php

namespace App\Http\Controllers\Api\auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordForgotNotification;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request){
        $validate = Validator::make($request->all(),[
            'email' => 'required|email|exists:users',
       ]);

       if ($validate->fails()) {
           return response()->json([
               'errors' => $validate->errors()
           ],422);
       }

       $email = $request->email;
       $token = Str::random(65);

       DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now()->addHours(1)
       ]);

       $user = User::whereEmail($email)->first();

        // mail send 

        // Mail::send('mail.password_reset',['token' => $token], function($msg) use ($email){
        //     $msg->to($email);
        //     $msg->subject('Password Reset Email');
        // });

        Notification::send($user, new PasswordForgotNotification($token));

        return response()->json([
            'message' => 'A Mail Has Been Sent . Please Check Your Email'
        ]);
       
    }

    public function resetPassword(Request $request){
        $validate = Validator::make($request->all(),[
            'password' => 'required|min:8|confirmed',
            'token' => 'required|exists:password_reset_tokens',
       ]);

       if ($validate->fails()) {
           return response()->json([
               'errors' => $validate->errors()
           ],422);
       }

       $token = DB::table('password_reset_tokens')->where('token',$request->token)->first();
       $user = User::whereEmail($token->email)->first();
       $user->password = Hash::make($request->password);
       $user->save();
       $token = DB::table('password_reset_tokens')->where('token',$request->token)->delete();

       return response()->json([
        'message' => 'Yor Requested Password Has Been Changed !! :)'
       ]);
    }
}
