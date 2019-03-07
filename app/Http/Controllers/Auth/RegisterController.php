<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'min:2', 'max:12', 'regex:/^[A-Za-z0-9\x{4e00}-\x{9fa5}]+$/u', 'unique:users,name'],
            'email'    => ['required', 'string', 'email', 'min:2', 'max:32', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:16', 'confirmed', 'regex:/^[^\s]*$/'],
            'captcha'  => ['required', 'captcha'],
        ], [
            'captcha.required' => '验证码不能为空',
            'captcha.captcha'  => '请输入正确的验证码',
            'email.unique'     => '该邮箱已被注册, 请直接登陆',
            'name.regex'       => '用户名只能由数字, 字母, 中文字符组成',
            'name.unique'      => '用户名已被占用',
            'password.regex'   => '密码不能包含空格'
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
