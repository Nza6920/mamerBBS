<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'             => 'required|email|unique:users,email',
            'name'              => 'required|between:2,12|regex:/^[A-Za-z0-9\x{4e00}-\x{9fa5}]+$/u|unique:users,name',
            'password'          => 'required|string|min:6|max:12',
            'verification_key'  => 'required|string',
            'verification_code' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'verification_key'  => '短信验证码 key',
            'verification_code' => '短信验证码',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => '该邮箱已被注册, 请直接登陆',
        ];
    }
}
