<?php

namespace App\Http\Controllers;

use App\Mail\Register;
use App\Models\RegisterUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


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

        $verifyToken = $this->generateVerifyToken();
        $this->saveToDatabase($request, $verifyToken);
        // $this->sendRegisterEmail($request, $verifyToken);

        return [
            'code' => 0,
            'message' => 'Vui lòng kiểm tra email'
        ];
    }

    private function generateVerifyToken(): string
    {
        return Str::uuid() . Str::random(100);
    }

    private function saveToDatabase(Request $request, string $verifyToken): void
    {
        $registerUser = new RegisterUser();
        $registerUser->password = Hash::make($request->password);
        $registerUser->username = $request->username;
        $registerUser->full_name = $request->fullName;
        $registerUser->email = $request->email;
        $registerUser->phone = $request->phone;
        $registerUser->verify_token = $verifyToken;
        $registerUser->expired_at = Carbon::now()->addMinutes(5);
        $registerUser->save();
    }

    private function sendRegisterEmail(Request $request, string $verifyToken): void
    {
        $registerEmail = new Register($request->fullName, $verifyToken);
        Mail::to($request->email)->send($registerEmail);
    }
}
