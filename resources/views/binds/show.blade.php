@extends('layouts.app')

@section('title', '账号绑定')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">账号绑定</div>
                    <div class="card-body">
                        <form class="form-inline" method="POST" action="{{ route('social.bind.verify') }}">
                            @csrf
                            <div style="margin-right: 27%"></div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email" value="{{ old('email') }}" placeholder="Email" required>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                    @if(session()->has('badEmail'))
                                        <span class="invalid-feedback-my">
                                            <strong>此 {{session()->get('badEmail')}} 无效, 请使用有效的邮箱</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">验证邮箱</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection()
