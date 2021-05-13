<?php

namespace App\Http\Controllers;

use App\Cache\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * Lấy danh sách ứng dụng của người dùng.
     */
    public function getAppsOfUser(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        return $user->apps;
    }
}
