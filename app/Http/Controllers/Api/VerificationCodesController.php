<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $captchaData = \Cache::get($request->captcha_key);

        // 验证码已失效或不存在
        if (! $captchaData) {
            return $this->response->error('图片验证码已失效', 422);
        }

        // 验证码错误
        if (! hash_equals($captchaData['code'], $request->captcha_code)) {
            // 验证错误, 清除缓存
            \Cache::forget($request->captcha_key);
            return $this->response->errorUnauthorized('验证码错误');
        }

        $phone = $captchaData['phone'];

        // 生成四位随机数, 左侧补零
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

        // 发送短信验证码
        try {
            $result = $easySms->send($phone, [
                'content' => "【mamer社区】您的验证码是{$code}。如非本人操作，请忽略本短信"
            ]);
        } catch (Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('yunpian')->getMessage();
                return $this->response->errorInternal($message ?: '短信发送异常');
        }

        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);

        // 缓存验证码十分钟后过期
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key'        => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
