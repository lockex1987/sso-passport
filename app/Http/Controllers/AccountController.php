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
     * Đổi ảnh đại diện.
     */
    public function updateInfo(Request $request)
    {
        $request->validate([
            'fullName' => 'required',
            'email' => 'required|email',
            'avatar' => 'mimes:png,jpg,jpeg,gif|max:2048'
        ]);

        $fullName = $request->fullName;
        $email = $request->email;
        $avatar = $request->avatar;

        $id = Auth::user()->id;
        $user = User::find($id);

        if (! empty($avatar)) {
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

        $user->full_name = $fullName;
        $user->email = $email;
        $user->save();

        return [
            'code' => 0,
            'message' => 'Lưu thông tin thành công',
            'avatar' => $user->avatar
        ];
    }
}
