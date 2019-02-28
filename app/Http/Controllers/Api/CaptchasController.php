<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.str_random(15);
        $phone = $request->phone;

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);

        // 将验证码与手机号存入缓存, 过期时间2分钟
        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key'           => $key,
            'expired_at'            => $expiredAt,
            'captcha_image_content' => $captcha->inline()   // base64图片验证码
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
