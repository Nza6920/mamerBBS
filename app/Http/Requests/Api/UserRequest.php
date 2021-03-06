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
        switch($this->method()) {
            case 'POST':
                return [
                    'email'             => 'required|email|min:2|max:32|unique:users,email',
                    'name'              => 'required|between:2,12|regex:/^[A-Za-z0-9\x{4e00}-\x{9fa5}]+$/u|unique:users,name',
                    'password'          => 'required|string|min:6|max:16|regex:/^[^\s\x{4e00}-\x{9fa5}]*$/u',
                    'verification_key'  => 'required|string',
                    'verification_code' => 'required|string',
                ];
                break;
            case 'PATCH':
                $userId = \Auth::guard('api')->id();
                return [
                    'name' => 'required|between:2,12|regex:/^[A-Za-z0-9\x{4e00}-\x{9fa5}]+$/u|unique:users,name,' .$userId,
                    'introduction' => 'max:80',
                    'avatar_image_id' => 'exists:images,id,type,avatar,user_id,'.$userId,
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'verification_key'  => '短信验证码 key',
            'verification_code' => '短信验证码',
            'name'              => '用户名',
            'introduction'      => '个人简介',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => '该邮箱已注册, 请直接登陆',
            'name.regex'   => '用户名只能由数字, 字母, 中文字符组成',
            'name.unique'  => '用户名已被占用',
            'password.regex' => '密码不能包含空格, 和中文字符',
        ];
    }
}
