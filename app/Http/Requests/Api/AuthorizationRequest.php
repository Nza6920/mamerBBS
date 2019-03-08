<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AuthorizationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|min:2|max:32',
            'password' => 'required|string|min:6|max:16|regex:/^[^\s]*$/',
        ];
    }
}
