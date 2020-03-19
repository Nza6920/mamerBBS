<?php

namespace App\Http\Requests;

class SocialBindsRequest extends Request
{
    public function rules()
    {
        return [
            'email' => 'required|string|email|min:2|max:32|exists:users,email'
        ];
    }

    public function messages()
    {
        return [
            'email.string' => '邮箱格式不正确',
            'email.email' => '邮箱格式不正确',
            'email.min' => '邮箱格式不正确',
            'email.max' => '邮箱格式不正确',
            'email.exists' => '邮箱不存在',
        ];
    }
}
