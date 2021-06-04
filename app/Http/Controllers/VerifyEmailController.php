<?php

namespace App\Http\Controllers;

use App\Models\RegisterUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class VerifyEmailController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'verifyToken' => 'required'
        ]);

        $registerUser = RegisterUser::where('verify_token', $request->verifyToken)
            ->first();

        if (! $registerUser) {
            return [
                'code' => 2,
                'message' => 'Mã xác nhận không hợp lệ'
            ];
        }

        if (Carbon::now() > $registerUser->expired_at) {
            $registerUser->delete();
            return [
                'code' => 2,
                'message' => 'Mã xác nhận đã hết hiệu lực. Vui lòng đăng ký lại.'
            ];
        }

        $this->createNewUser($registerUser);
        $registerUser->delete();

        return [
            'code' => 0
        ];
    }

    private function createNewUser(RegisterUser $registerUser): void
    {
        $user = new User();
        $user->password = $registerUser->password;
        $user->username = $registerUser->username;
        $user->full_name = $registerUser->full_name;
        $user->email = $registerUser->email;
        $user->phone = $registerUser->phone;
        $user->is_active = 1;
        $user->save();
    }
}
