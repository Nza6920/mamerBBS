<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
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

    // 注册
    public function register(Request $request)
    {
        $data = $request->all();
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($data)));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
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
        $userArr = [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ];

        // 添加 github_id
        if (isset($data['github_id'])) {
            $userArr['github_id'] = $data['github_id'];
        }
        // 添加 qq_id
        if (isset($data['qq_id'])) {
            $userArr['qq_id'] = $data['qq_id'];
        }
        // 添加 头像
        if (isset($data['avatar'])) {
            $userArr['avatar'] = $data['avatar'];
        }

        $this->forgetFromSession();

        return User::create($userArr);
    }

    protected function forgetFromSession() {
        if (session()->has('driver')) {
            session()->forget('driver');
        }
        if (session()->has('id')) {
            session()->forget('id');
        }
        if (session()->has('avatar')) {
            session()->forget('avatar');
        }
    }
}
