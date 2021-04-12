<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RegisterUser;
use App\Http\Controllers\Controller;
use App\Mail\Register;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;


class VerifyEmailController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $request->validate([
			'verifyToken' => 'required'
        ]);

        $registerUser = RegisterUser::where('verify_token', $request->verifyToken)->first();

        if (!$registerUser) {
            return [
                'code' => 1,
                'message' => 'Mã xác nhận không hợp lệ'
            ];
        }

        if (Carbon::now() > $registerUser->expired_at) {
            $registerUser->delete();

            return [
                'code' => 1,
                'message' => 'Mã xác nhận đã hết hiệu lực'
            ];
        }

        $user = new User();
        $user->password = $registerUser->password;
        $user->username = $registerUser->username;
        $user->full_name = $registerUser->full_name;
        $user->email = $registerUser->email;
        $user->is_active = 1;
        $user->save();

        $registerUser->delete();
   
        return [
            'code' => 0
        ];
    }
}
