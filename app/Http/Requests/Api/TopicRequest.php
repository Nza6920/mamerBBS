<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|string|min:2|max:50',
                    'body'  => 'required|string|min:3',
                    'category_id' => 'required|exists:categories,id',
                ];
                break;
            case 'PATCH':
                return [
                    'title' => 'required|string|min:2|max:50',
                    'body'  => 'required|string|min:3',
                    'category_id' => 'required|exists:categories,id',
                ];
                break;
        }

    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'body' => '话题内容',
            'category_id' => '分类',
        ];
    }
}
