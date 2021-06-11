<?php

namespace App\Http\Controllers;

use App\Cache\Auth;
use App\Mail\ChangePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class ChangePasswordController extends Controller
{
    public function __construct()
    {
        // $this->middleware('jwt.auth', []);
    }

    /**
     * Đổi mật khẩu.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required|min:8|different:oldPassword'
        ]);

        // Các tham số người dùng truyền
        $oldPassword = $request->oldPassword;
        $newPassword = $request->newPassword;

        // Mật khẩu mới không được giống mật khẩu cũ?

        $userId = Auth::user()->id;
        $user = User::find($userId);

        if (! Hash::check($oldPassword, $user->password)) {
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
    private function sendEmail(User $user): void
    {
        $changePasswordEmail = new ChangePassword($user->full_name);
        Mail::to($user->email)->send($changePasswordEmail);
    }
}
