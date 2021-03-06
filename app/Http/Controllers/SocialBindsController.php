<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialBindsRequest;
use App\Models\User;
use App\Notifications\SocialBinds;

class SocialBindsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['guest']);
        $this->middleware('signed')->only('confirmEmail');              // 签名
        $this->middleware('throttle:6,1')->only('sendEmail');           // 一分钟不超过6次
    }

    public function show()
    {
        if (session()->has('driver') && session()->has('id')) {
            return view('binds.show');
        } else {
            return redirect()->route('login');
        }
    }

    // 发送邮件
    public function sendEmail(SocialBindsRequest $request)
    {
        $email = $request->email;
        $user = User::where('email', '=', $email)->first();

        if (session()->get('driver') === 'qq' && isset($user->qq_id)) {
            return back()->with('info', '该账号已绑定过qq, 请直接登陆.');
        } else if (isset($user->github_id)) {
            return back()->with('info', '该账号已绑定过github, 请直接登陆.');
        }

        $user->notify(new SocialBinds($user->id));
        session()->flash('success', '绑定邮件已发送至您的邮箱, 请用当前浏览器打开链接.');
        return back();
    }

    public function confirmEmail($userId)
    {
        // 判断参数
        if (! (($driver = session()->get('driver')) && ($id = session()->get('id')) && ($user = User::find($userId)))) {
            return redirect()->route('login')->with('danger', '无效的链接, 请重试.');
        }

        switch ($driver) {
            case 'qq':
                $user->qq_id = $id;
                break;
            case 'github':
                $user->github_id = $id;
                break;
            default:
                return redirect()->route('login')->with('danger', '错误的参数, 请重试');
        }

        $user->save();
        forgetSocialInfoFromSession();

        return redirect()->route('login')->with('success', '绑定成功, 请重新登陆');
    }
}
