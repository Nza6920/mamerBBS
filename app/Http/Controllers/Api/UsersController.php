<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;

class UsersController extends Controller
{
    public function store(UserRequest $request, User $user)
    {
        $verifyData = \Cache::get($request->verification_key);

        // key 不存在 or 失效
        if (!$verifyData) {
            return $this->response->error('验证码失效', 422);
        }

        // 验证码不正确
        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回 401
            return $this->response->errorUnauthorized('验证码错误');
        }

        // 创建用户
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $verifyData['phone'];
        $user->password = bcrypt($request->password);
        $user->save();

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return $this->response->created();
    }
}
