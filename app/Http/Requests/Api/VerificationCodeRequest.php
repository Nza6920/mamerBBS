<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class VerificationCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    // 验证规则
    public function rules()
    {
        return [
            'captcha_key'  => 'required|string',
            'captcha_code' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'captcha_key'  => '图片验证码 key',
            'captcha_code' => '图片验证码'
        ];
    }
}
