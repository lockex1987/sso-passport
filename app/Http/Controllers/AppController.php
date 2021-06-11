<?php

namespace App\Http\Controllers;

use App\Cache\Auth;
use App\Models\User;

class AppController extends Controller
{
    /**
     * Lấy danh sách ứng dụng của người dùng.
     */
    public function getAppsOfUser()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        return $user->apps;
    }
}
