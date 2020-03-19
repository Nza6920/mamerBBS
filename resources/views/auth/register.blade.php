@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        @if(session()->has('driver') && session()->has('id'))
                            @switch(session()->get('driver'))
                                @case('github')
                                    <input type="hidden" name="github_id" value="{{ session()->get('id') }}">
                                    <input type="hidden" name="avatar" value="{{ session()->get('avatar') }}">
                                @break
                                @case('qq')
                                    <input type="hidden" name="qq_id" value="{{ session()->get('id') }}">
                                    <input type="hidden" name="avatar" value="{{ session()->get('avatar') }}">
                                @break
                                @default
                                @break
                            @endswitch
                        @endif
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ isset($socialUser) ? (isset($socialUser->nickname) ? $socialUser->nickname : old('name')) : old('name') }}" name="name" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ isset($socialUser) ? (isset($socialUser->email) ? $socialUser->email : old('email')) : old('email') }}" placeholder="注册后无法更改, 请谨慎填写." required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                @if(session()->has('badEmail'))
                                    <span class="invalid-feedback-my" >
                                        <strong>此 {{session()->get('badEmail')}} 无效, 请使用有效的邮箱</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required onpaste="return false" oncopy="return false">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required onpaste="return false" oncopy="return false">
                            </div>
                        </div>
                        {{-- 验证码 --}}
                        <div class="form-group row">
                            <label for="captcha" class="col-md-4 col-form-label text-md-right">验证码</label>
                            <div class="col-md-6">
                                <input id="captcha" class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}" name="captcha" required>
                                <img class="thumbnail captcha mt-3 mb-2" src="{{ captcha_src('flat') }}" onclick="this.src='/captcha/flat?'+Math.random()" title="点击图片重新获取验证码">
                                @if ($errors->has('captcha'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('captcha') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.history.pushState(null, null, '/register?type=social')
</script>
@endsection
