<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Gửi mail quên mật khẩu.
 */
class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('customThrottle:10,1,7,send-reset-password-token');
    }

    public function sendResetToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $user = User::where('email', '=', $email)->first();

        if (! $user) {
            return response()
                    ->json([
                        'code' => 1,
                        'message' => 'Email không tồn tại'
                    ])
                    ->header('throttleIt', true);
        }

        // Tạo token ngẫu nhiên
        $resetToken = Str::uuid() . Str::random(100);

        // Lưu vào DB
        $passwordReset = new PasswordReset();
        $passwordReset->email = $email;
        $passwordReset->user_id = $user->id;
        $passwordReset->reset_token = $resetToken;
        $passwordReset->expired_at = Carbon::now()->addMinutes(5);
        $passwordReset->save();

        // Gửi mail
        $resetPasswordEmail = new ResetPassword($user->full_name, $resetToken);
        Mail::to($email)->send($resetPasswordEmail);

        /*
        if ($sendingResponse !== Password::RESET_LINK_SENT) {
            return response()
                    ->json([
                        'code' => 1,
                        'message' => 'Gửi mail thất bại'
                    ])
                    ->header('throttleIt', true);
        }
        */

        return [
            'code' => 0
        ];
    }
}
