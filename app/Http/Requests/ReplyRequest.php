<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules()
    {
        return [
          'content1' => 'required|min:2|max:80'
        ];
    }

    public function messages()
    {
        return [
            'content1.required' => '回复内容不能为空',
            'content1.min' => '回复内容必须不少于两个字',
            'content1.max' => '回复内容必须少于80个字'
        ];
    }
}
