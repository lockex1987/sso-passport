<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cache\Auth;
use App\Models\User;

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
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $fullName = $request->fullName;
        $email = $request->email;
        $avatar = $request->avatar;

        $id = Auth::user()->id;
        $user = User::find($id);

        if (!empty($avatar)) {
            // Xóa ảnh cũ
            if ($user->avatar) {
                Storage::delete('avatars/' . $user->avatar);
            }

            $avatarName = $user->id . '_avatar_' . time() . '.' . $request->avatar->getClientOriginalExtension();
            $request->avatar->storeAs('avatars', $avatarName);

            $user->avatar = $avatarName;
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
