<?php

namespace App\Http\Requests\Api;

class ReplyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'content1' => 'required|min:2',
        ];
    }
}
