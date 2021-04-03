<?php

namespace App\Cache;

use Illuminate\Support\Facades\Redis;
use Str;

class Auth
{
    public static function user()
    {
        $request = request();
        $token = self::getToken($request);
        $redisKey = self::getTokenRedisKey($token);

        $redisValue = Redis::get($redisKey);
        if (! $redisValue) {
            return null;
        }

        $user = json_decode($redisValue);
        return $user;
    }

    public static function saveUser($user)
    {
        $token = Str::uuid() . Str::random(100);

        // Sinh token và lưu ở Redis trong 10 ngày
        // Tính toán sao cho nó xảy ra ở nửa đêm,
        // để giảm xác xuất người dùng đang thao tác thì bị hết hạn token
        $expiredTime = 10 * 24 * 60 * 60;
        $redisKey = self::getTokenRedisKey($token);
        $redisValue = json_encode([
            'id' => $user->id,
            'username' => $user->username
        ]);
        Redis::set($redisKey, $redisValue, 'EX', $expiredTime);
        return $token;
    }

    public static function removeUser()
    {
        $request = request();
        $token = self::getToken($request);
        $redisKey = self::getTokenRedisKey($token);
        Redis::del($redisKey);
    }

    public static function generateLoginTicket($user)
    {
        $ticket = Str::uuid() . Str::random(100);

        $expiredTime = 60;
        $redisKey = self::getTicketRedisKey($ticket);
        $redisValue = json_encode([
            'username' => $user->username,
            'email' => $user->email
        ]);
        Redis::set($redisKey, $redisValue, 'EX', $expiredTime);
        return $ticket;
    }

    public static function getUserFromLoginTicket($ticket)
    {
        $redisKey = self::getTicketRedisKey($ticket);
        $redisValue = Redis::get($redisKey);

        if (empty($redisValue)) {
            return null;
        }

        // Xóa luôn key ở Redis
        Redis::del($redisKey);

        $user = json_decode($redisValue, true);
		return $user;
    }

    public static function getToken()
    {
        $request = request();
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        return $token;
    }

    /**
     * Đặt tên Redis key dạng xxx:yyy để
     * ở Redis Desktop Manager hiển thị dạng thư mục.
     */
    private static function getTokenRedisKey($token)
    {
        return 'sso_token:' . $token;
    }

    private static function getTicketRedisKey($ticket)
    {
        return 'sso_ticket:' . $ticket;
    }
}
