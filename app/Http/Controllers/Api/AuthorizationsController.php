<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\AuthorizationRequest;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        // 判断输入的是 手机号 还是 email
        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        // 验证登陆
        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorUnauthorized('用户名或密码错误');
        }

        return $this->response->array([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => \Auth::guard('api')->factory()->getTTL() * 60,
        ])->setStatusCode(201);
    }

    // 更新 token
    public function update()
    {
        $token = \Auth::guard('api')->refresh();
        return $this->response->array([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => \Auth::guard('api')->factory()->getTTL() * 60,
        ])->setStatusCode(201);
    }

    // 删除 token
    public function destroy()
    {
        \Auth::guard('api')->logout();
        return $this->response->noContent();
    }
}
