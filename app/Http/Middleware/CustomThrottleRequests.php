<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Cache\CustomRateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CustomThrottleRequests
{
    /**
     * Đối tượng rate limiter.
     *
     * @var \App\Cache\CustomRateLimiter
     */
    protected $limiter;

    /**
     * Khởi tạo, dependency injection đối tượng rate limiter.
     *
     * @param  \App\Cache\CustomRateLimiter  $limiter
     * @return void
     */
    public function __construct(CustomRateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Xử lý các request đến.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $maxAttempts    Số lần thất bại tối đa
     * @param  int  $decayMinutes    Số phút giới hạn
     * @param  int  $blockedMinutes    Số phút khóa
     * @param  String $keyPrefix Xâu prefix để các API khác nhau sẽ không bị xung đột
     * @return mixed
     */
    public function handle($request, Closure $next, $maxAttempts, $decayMinutes, $blockedMinutes, $keyPrefix)
    {
        Log::info("$maxAttempts, $decayMinutes, $blockedMinutes, $keyPrefix");

        // Khóa để kiểm tra, lưu vào cache
        // \Log::info($keyPrefix);
        $key = $keyPrefix . $request->fingerprint();

        // Nếu có nhiều request quá thì response lỗi
        // Mã vẫn là 200
        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = $this->limiter->availableIn($key);
            $content = json_encode([
                'code' => 429,
			    'message' => 'Bạn thực hiện sai quá nhiều lần, vui lòng thử lại sau ' . $retryAfter . ' giây',
                'retryAfter' => $retryAfter
            ]);
            $response = new Response($content, 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        // Thực hiện nghiệp vụ chính
        $response = $next($request);

        // Nếu request được đánh dấu là lỗi thì tăng số lần
        // Ở Controller cần trả về thiết lập $response->header('throttleIt', true) và trả về $response
        if ($response->headers->has('throttleIt')) {
            $this->limiter->hit($key, $decayMinutes * 60, $blockedMinutes * 60);
        }

        // Hình như không cần (và không thể vào chỗ này)
        if ($response->headers->has('clearThrottle')) {
            $this->limiter->resetAttempts($key);
        }

        return $response;
    }
}
