<?php

namespace App\Http\Controllers;

use App\Cache\Auth;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class LoginLogController extends Controller
{
    /**
     * Lấy danh sách log đăng nhập của người dùng.
     */
    public function getLoginLogsOfUser(Request $request)
    {
        $id = Auth::user()->id;
        $size = $request->size;

        // Log::info('Test log info');
        // Log::error('Test log lỗi');

        $pagi = SystemLog::where('user_id', $id)
            ->where('type', 'login')
            ->orderBy('created_at', 'desc')
            ->paginate($size);
        return $pagi;
    }
}
