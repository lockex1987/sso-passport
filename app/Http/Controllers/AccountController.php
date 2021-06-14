<?php

namespace App\Http\Controllers;

use App\Cache\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Đổi thông tin (tên, email, số điện thoại, ảnh đại diện).
     * TODO: Có nên cho đổi tên và email (email đang có ràng buộc unique).
     */
    public function updateInfo(Request $request)
    {
        $request->validate([
            'fullName' => 'required',
            'email' => 'required|email',
            'phone' => 'max:20', // phone
            // TODO: Validate định dạng bằng mimes vẫn bị tấn công
            // Không up được file PHP nhưng vẫn up được file HTML
            'avatar' => 'mimes:png,jpg,jpeg,gif|max:2048'
        ]);

        $id = Auth::user()->id;
        $user = User::find($id);

        $avatar = $request->avatar;
        if (! empty($avatar)) {
            $this->updateAvatar($user, $avatar);
        }

        $user->full_name = $request->fullName;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return [
            'code' => 0,
            'message' => 'Lưu thông tin thành công',
            'avatar' => $user->avatar
        ];
    }

    /**
     * Đổi ảnh đại diện.
     */
    private function updateAvatar(User $user, $avatar): void
    {
        $appUrl = config('app.url');

        // Xóa ảnh cũ
        if ($user->avatar) {
            if (str_starts_with($user->avatar, $appUrl)) {
                $relativePath = str_replace($appUrl . '/storage/avatars/', 'avatars/', $user->avatar);
                Storage::disk('public')->delete($relativePath);
            }
        }

        $avatarName = $user->id . '_avatar_' . time() . '.' . $avatar->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('avatars', $avatar, $avatarName);

        $user->avatar = $appUrl . '/storage/avatars/' . $avatarName;
    }
}
