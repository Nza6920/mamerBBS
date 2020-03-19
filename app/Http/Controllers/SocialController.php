<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    // github 登陆
    public function githubLogin()
    {
        return Socialite::with('github')->redirect();
    }

    // github 回调
    public function githubCallback()
    {
        $socialUser = Socialite::driver('github')->user();

        // 判断用户是否已经注册, 未注册跳转到注册页面
        if ($user = User::where('github_id', '=', $socialUser->id)->first()) {
            Auth::login($user);
            return redirect()->to('/');
        } else {
            $driver = 'github';

            $val = '<span>此账号还未注册, 请先注册(*^▽^*)</span><a href=' . route('social.bind.show') . 'style="margin-left: 1%">已有账号? 前去绑定.</a>';
            session()->flash('info', $val);
            $this->saveToSession('github', $socialUser->id, $socialUser->avatar);
            return view('auth.register', compact('socialUser', 'driver'));
        }
    }

    // qq 登陆
    public function qqLogin()
    {
        return Socialite::with('qq')->redirect();
    }

    // qq 回调
    public function qqCallback()
    {
        $socialUser = Socialite::driver('qq')->user();

        // 判断用户是否已经注册, 未注册跳转到注册页面
        if ($user = User::where('qq_id', '=', $socialUser->id)->first()) {
            Auth::login($user);
            return redirect()->to('/');
        } else {
            $driver = 'qq';
            $val = '<span>此账号还未注册, 请先注册(*^▽^*)</span><a href="' . route('social.bind.show') . '" style="margin-left: 1%">已有账号? 前去绑定.</a>';
            session()->flash('info', $val);
            $this->saveToSession('qq', $socialUser->id, $socialUser->avatar);
            return view('auth.register', compact('socialUser', 'driver'));
        }
    }

    protected function saveToSession($driver, $id, $avatar = null) {
        session()->put('driver', $driver);
        session()->put('id', $id);
        if ($avatar) {
            session()->put('avatar', $avatar);
        }
    }
}
