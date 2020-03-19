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
        // 清除多余空格(只保留一个)
        if ($this->request->has('introduction')) {
            $this->request->set('introduction', preg_replace('/\s+/u',' ', $this->introduction));
        }

        return [
            'name' => 'required|between:2,12|regex:/^[A-Za-z0-9\x{4e00}-\x{9fa5}]+$/u|unique:users,name,' . \Auth::id(),
            'introduction' => 'max:50',
            'avatar' => 'mimetypes:image/jpeg,image/png,image/gif|dimensions:min_width=208,min_height=208|between:0,500'
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimetypes' => '必须是jpeg,png,jpg,gif格式的图片',
            'avatar.dimensions' => '清晰度不够，宽和高需要 208px 以上',
            'avatar.between' => '图片要小于500kb',
            'name.regex' => '用户名只能由字母, 数字, 中文组成',
            'name.unique' => '用户名已被占用',
            'introduction.max' => '个人简介最大不能超过50个字'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '用户名',
        ];
    }


}
