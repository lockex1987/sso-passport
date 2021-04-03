<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Cache\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ChangePassword;
use Mail;
use Hash;


class ChangePasswordController extends Controller
{
    public function __construct()
    {
        // $this->middleware('jwt.auth', []);
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required'
        ]);

        // Các tham số người dùng truyền
        $oldPassword = $request->oldPassword;
        $newPassword = $request->newPassword;

        // Mật khẩu mới không được giống mật khẩu cũ?

        $userId = Auth::user()->id;
        $user = User::find($userId);

        if (!Hash::check($oldPassword, $user->password)) {
            return [
                'code' => 1,
                'message' => 'Mật khẩu cũ không chính xác'
            ];
        }

        // Lưu thông tin mật khẩu mới
        $user->password = Hash::make($newPassword);
        $user->save();

        $this->sendEmail($user);

        return [
            'code' => 0,
            'message' => 'Đổi mật khẩu thành công'
        ];
    }

    /**
     * Gửi mail.
     */
    private function sendEmail($user)
    {
        $changePasswordEmail = new ChangePassword($user->full_name);
        Mail::to($user->email)->send($changePasswordEmail);
    }
}
