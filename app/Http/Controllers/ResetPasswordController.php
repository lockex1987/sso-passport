<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class ResetPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('customThrottle:10,1,7,reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'resetToken' => 'required',
            'newPassword' => 'required'
        ]);

        $resetToken = $request->resetToken;
        $newPassword = $request->newPassword;

        $passwordReset = PasswordReset::where('reset_token', $resetToken)
                ->first();

        if (!$passwordReset) {
            return [
                'code' => 1,
                'message' => 'Mã bí mật không hợp lệ'
            ];
        }

        if (Carbon::now() > $passwordReset->expired_at) {
            $passwordReset->delete();

            return [
                'code' => 1,
                'message' => 'Mã bí mật đã hết hiệu lực'
            ];
        }

        $user = User::find($passwordReset->user_id);
        $user->password = Hash::make($newPassword);
        $user->save();

        $passwordReset->delete();

        return [
            'code' => 0
        ];
    }
}
