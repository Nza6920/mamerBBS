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
            session()->flash('info', '此账号还未注册, 请先注册(*^▽^*)');
            return view('auth.register', compact('socialUser', 'driver'));
        }
    }
}
