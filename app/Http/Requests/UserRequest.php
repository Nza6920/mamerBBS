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
            'avatar' => 'mimes:jpeg,bmp,png,gif,jpg|dimensions:min_width=208,min_height=208'
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimes' => '头像必须是 jpeg, bmp, png, gif 格式的图片',
            'avatar.dimensions' => '图片的清晰度不够，宽和高需要 208px 以上',
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
