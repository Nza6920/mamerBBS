<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    // 跳转地址
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    // 重写 trait 方法
    protected function sendResetResponse(Request $request, $response)
    {
        session()->flash('success', '密码更新成功, 您已成功登录! ');
        return redirect($this->redirectPath());
    }

    // 重写 rules
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email|min:2|max:32',
            'password' => 'required|confirmed|string|min:6|max:16|regex:/^[^\s]*$/',
        ];
    }

    // 重写 validationErrorMessages
    protected function validationErrorMessages()
    {
        return [
            'password.regex' => '密码不能含有空格.',
            'password.min' => '密码最小为6位',
            'password.max' => '密码最大为16位',
        ];
    }

}
