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

        // Cần kiểm tra ở bảng user đã có người dùng có username, email như thế hay chưa
        // Có thể có trường hợp thực hiện thao tác đăng ký nhiều lần, gửi mail nhiều lần với cùng email
        // Nên để ràng buộc unique ở DB
        $user = User::where('username', $registerUser->username)
            ->orWhere('email', $registerUser->email)
            ->first();
        if ($user) {
            return [
                'code' => 2,
                'message' => 'Người dùng đã tồn tại'
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
