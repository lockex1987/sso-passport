<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cache\Auth;
use App\Models\User;


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
