<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class ImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'type' => 'required|string|in:avatar,topic',
        ];

        if ($this->type == 'avatar') {
            $rules['image'] = 'required|mimetypes:image/jpeg,image/png,image/gif|between:0,500|dimensions:min_width=200,min_height=200';
        } else {
            $rules['image'] = 'required|mimetypes:image/jpeg,image/png,image/gif|between:0,500';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'image.dimensions' => '太模糊啦, 图片的清晰度不够，宽和高需要 200px 以上',
            'avatar.mimetypes' => '格式错啦, 必须是jpeg,png,jpg,gif格式的图片',
            'avatar.between' => '太大啦, 图片要小于500kb',
        ];
    }
}
