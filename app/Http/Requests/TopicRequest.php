<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class TopicRequest extends Request
{
    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                return [
                    'title'       => 'required|string|min:2|max:50',
                    'body'        => 'required|string|min:14',
                    'category_id' => 'required|numeric',
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title'       => 'required|string|min:2|max:50',
                    'body'        => 'required|string|min:14',
                    'category_id' => 'required|numeric',
                ];
            }
            case 'GET':
            case 'DELETE':
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            'title.min' => '标题必须至少两个字符',
            'title.max' => '标题最大50个字符',
            'body.min' => '文章内容必须至少三个字符',
        ];
    }
}
