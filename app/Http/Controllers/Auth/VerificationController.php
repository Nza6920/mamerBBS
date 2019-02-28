<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('auth');      // 登录
        $this->middleware('signed')->only('verify');   // 签名
        $this->middleware('throttle:6,1')->only('verify', 'resend');  // 一分钟不超过6次
    }
}
