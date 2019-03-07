<?php

namespace App\Http\Requests;

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
            'name' => 'required|between:2,12|regex:/^[A-Za-z0-9\x{4e00}-\x{9fa5}]+$/u|unique:users,name,' . \Auth::id(),
            'introduction' => 'max:50',
        ];
    }

    public function messages()
    {
        return [
          'name.regex' => '用户名只能由字母, 数字, 中文组成',
          'name.unique' => '用户名已被占用',
          'introduction' => '个人简介最大不能超过50个字'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '用户名',
        ];
    }


}
