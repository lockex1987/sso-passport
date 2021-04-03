<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RegisterUser;
use App\Http\Controllers\Controller;
use App\Mail\Register;
use Mail;
use Str;
use Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
			'username' => 'required|unique:user,username',
			'fullName' => 'required',
            'email' => 'required|email|unique:user,email',
            'password' => 'required'
        ]);
        
        // Tạo token ngẫu nhiên
        $verifyToken = Str::uuid() . Str::random(100);
        
        // Lưu DB
        $registerUser = new RegisterUser();
        $registerUser->password = Hash::make($request->password);
        $registerUser->username = $request->username;
        $registerUser->full_name = $request->fullName;
        $registerUser->email = $request->email;
        $registerUser->verify_token = $verifyToken;
        $registerUser->expired_at = Carbon::now()->addMinutes(5);
        $registerUser->save();

        // Gửi mail
        $registerEmail = new Register($request->fullName, $verifyToken);
        Mail::to($request->email)->send($registerEmail);
   
        return [
            'code' => 0
        ];
    }
}
