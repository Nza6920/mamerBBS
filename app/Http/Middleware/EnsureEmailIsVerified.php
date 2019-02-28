<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
{
    public function handle($request, Closure $next)
    {
        // 如果用户已经登录
        // 并且还没有验证 Email
        // 并且访问的不是 email 验证相关的 URL 或者退出登录的 URL
        if ($request->user() && !$request->user()->hasVerifiedEmail() && ! $request->is('email/*', 'logout')) {
            // 根据客户端返回对应的内容
            return $request->expectsJson() ? abort(403, 'Your email address is not verified.')
                                           : redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
