<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AuthorizationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|min:2|max:32',
            'password' => 'required|string|min:6|max:16|regex:/^[^\s\x{4e00}-\x{9fa5}]*$/u',
        ];
    }

    public function attributes()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => '密码不能包含空格和中文字符',
        ];
    }

}
