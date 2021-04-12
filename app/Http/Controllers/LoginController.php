<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Cache\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\App;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Hash;

/**
 * Controller này quản lý việc đăng nhập của người dùng.
 */
class LoginController extends Controller
{
    /**
     * Xử lý đăng nhập.
     */
    public function login(Request $request)
    {
        // Kiểm tra đầu vào
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = strtolower($request->input('username'));
        $password = $request->input('password');

        // Kiểm tra tên đăng nhập và mật khẩu
        $user = $this->attemptLogin($username, $password);

        // Nếu không khớp thì báo lỗi và dừng lại
        if (!$user) {
            return response()
                    ->json([
                        'code' => 1,
                        'message' => 'Đăng nhập thất bại'
                    ])
                    ->header('throttleIt', true) // tăng số lần đăng nhập thất bại
                    ;
        }

        // Không trả về trường password cho người dùng
        // Thiết lập thuộc tính hidden
        // $user->password = null;

        // Lưu thông tin người dùng
        $token = Auth::saveUser($user);
        
        // Lưu log
        $this->insertLoginLog($user->id);
        
        // Nếu không nhập mã ứng dụng khác thì trở về trang chủ luôn
        $app = $request->input('app');
        if (empty($app)) {
            return [
                'code' => 0,
                'token' => $token,
                'user' => $user
            ];
        }

        // Redirect về trang khác
        return $this->checkRedirectApp($app, $token, $user, true);
    }

    public function checkLoginTicket(Request $request)
    {
        // Kiểm tra mã truyền vào
        $ticket = $request->input('ticket');
        // dd($ticket);
        $user = Auth::getUserFromLoginTicket($ticket);

        if (empty($user)) {
            return [
                'code' => 1,
                'error' => 'Invalid ticket'
            ];
        }

        return $user;
    }

    /**
     * Lấy thông tin người dùng từ token truyền vào.
     */
    public function getUserInfo(Request $request)
    {
        $redisUser = Auth::user();

        if ($redisUser == null) {
            return [
                'code' => 2
            ];
        }

        $id = $redisUser->id;
        $user = User::find($id);
        $user->password = null;

        if (! $user) {
            return [
                'code' => 1
            ];
        }

        $app = $request->input('app');

        if (empty($app)) {
            return [
                'code' => 0,
                'user' => $user
            ];
        }

        //$token = Auth::getToken();

        return $this->checkRedirectApp($app, null/*$token*/, $user, true);
    }

    /**
     * Đăng xuất.
     */
    public function logout(Request $request)
    {
        Auth::removeUser();

        $app = $request->input('app');

        if (empty($app)) {
            return [
                'code' => 0
            ];
        }

        return $this->checkRedirectApp($app, null, null, false);
    }

    /**
     * Thử login sử dụng các thông tin của người dùng nhập vào.
     */
    private function attemptLogin($username, $password)
    {
        // Nếu sử dụng App\User thì không lấy ra được trường password
        $user = User::where('username', $username)->first();

        if (is_null($user)) {
            return null;
        }

        if (! Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * Thêm log.
     */
    private function insertLoginLog($userId)
    {
        $request = request();

        $loginLog = new SystemLog();
        $loginLog->user_id = $userId;
        $loginLog->ip = $request->ip();
        $loginLog->user_agent = $request->header('User-Agent');
        // TODO: Parse user agent thành browser (Firefox, Chrome, Edge, Chromium,...) và os (Windows, Ubuntu, Linux,...)
        $loginLog->created_at = now();
        $loginLog->type = 'login';
        $loginLog->save();
    }

    /**
     * Kiểm tra redirect khi đăng nhập và đăng xuất.
     */
    private function checkRedirectApp($app, $token, $user, $isLogin)
    {
        // Lấy ứng dụng khác ở DB
        $appRecord = App::where('code', $app)->first();
        if (empty($appRecord)) {
            return [
                'code' => 2,
                'message' => 'Ứng dụng không tồn tại'
            ];
        }

        if ($isLogin) {
            // Người dùng cần được gán quyền truy cập vào ứng dụng
            $isGranted = false;
            foreach ($user->apps as $a) {
                if ($a->id == $appRecord->id) {
                    $isGranted = true;
                    break;
                }
            }

            if (! $isGranted) {
                return [
                    'code' => 3,
                    'message' => 'Người dùng không được gán cho ứng dụng'
                ];
            }
        }

        if ($isLogin) {
            // Sinh ticket và lưu ở Redis trong 1 phút
            // Không truyền token, vì thông tin này hiển thị trên URL
            // có thể bị người ở giữa nhìn thấy
            $ticket = Auth::generateLoginTicket($user);
        } else {
            $ticket = null;
        }

        // Redirect về ứng dụng khác
        // TODO: ký số ticket
        $redirectUrl = $isLogin ?
                ($appRecord->login_redirect . '?ssoTicket=' . $ticket) :
                $appRecord->logout_redirect;

        return [
            'code' => 0,
            'token' => $token,
            'user' => $user,
            'redirectUrl' => $redirectUrl
        ];
    }
}
