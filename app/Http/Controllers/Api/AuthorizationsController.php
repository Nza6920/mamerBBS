<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use Dingo\Api\Contract\Http\Request;

class AuthorizationsController extends Controller
{
    // 登陆
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

        return $this->respondWithToken($token)->setStatusCode(201);
    }

    // 短信登陆
    public function msgStore(Request $request)
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
        // 验证码不正确
        if (!hash_equals($verifyData['phone'], $request->phone)) {
            // 返回 401
            return $this->response->errorUnauthorized('手机号非法');
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user == null) {
            return $this->response->errorUnauthorized('用户不存在， 请先注册');
        }

        $token = \Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token)->setStatusCode(201);
    }

    // 三方登陆
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        if (!in_array($type, ['weixin'])) {
            return $this->response->errorBadRequest();
        }

        $driver = \Socialite::driver($type);

        try {
            if ($code = $request->code) {
                $response = $driver->getAccessTokenResponse($code);
                $token = array_get($response, 'access_token');
            } else {
                $token = $request->access_token;

                if ($type == 'weixin') {
                    $driver->setOpenId($request->openid);
                }
            }

            $oauthUser = $driver->userFromToken($token);
        } catch (\Exception $e) {
            return $this->response->errorUnauthorized('参数错误，未获取用户信息');
        }

        switch ($type) {
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                // 没有用户，默认创建一个用户
                $name = $oauthUser->getNickname() . '_' . str_random(8);
                while (User::where('name', '=', $name)->first() != null) {
                    $name = $oauthUser->getNickname() . '_' . str_random(8);
                }
                if (!$user) {
                    $user = User::create([
                        'name' => $name,
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $oauthUser->offsetGet('unionid'),
                    ]);
                }

                break;
        }

        $token = \Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token)->setStatusCode(201);
    }

    // 更新 token
    public function update()
    {
        $token = \Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    // 删除 token
    public function destroy()
    {
        \Auth::guard('api')->logout();
        return $this->response->noContent();
    }

    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
}
