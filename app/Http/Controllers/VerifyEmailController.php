<?php

namespace App\Http\Controllers;

use App\Models\RegisterUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


class VerifyEmailController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'verifyToken' => 'required'
        ]);
        $token = Crypt::encryptString($request->verifyToken);

        $registerUser = RegisterUser::where('verify_token', $token)
            ->first();

        if (! $registerUser) {
            return [
                'code' => 2,
                'message' => 'Mã xác nhận không hợp lệ'
            ];
        }

        $isExpired = Carbon::now() > $registerUser->expired_at;
        if ($isExpired) {
            $registerUser->delete();
            return [
                'code' => 2,
                'message' => 'Mã xác nhận đã hết hiệu lực. Vui lòng đăng ký lại.'
            ];
        }

        $this->createNewUser($registerUser);
        $registerUser->delete();

        return [
            'code' => 0,
            'message' => 'Đăng ký thành công'
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
