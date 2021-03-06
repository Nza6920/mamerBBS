@extends('layouts.app')

@section('title', $user->name . ' 的个人中心')
@section('content')

    <div class="row">

        <div class="col-lg-3 col-md-3 hidden-sm hidden-xs user-info">
            <div class="card ">
                <img class="card-img-top" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                <div class="card-body">
                    @if (Auth::check())
                        @include('users._follow_form')
                    @endif
                    <div class="stats mt-2">
                        <div class="stats-wrap">
                            @include('shared._stats', ['user' => $user])
                        </div>
                    </div>
                    <hr>
                    <h5><strong>个人简介</strong></h5>
                    <p>{{ $user->introduction }}</p>
                    <hr>
                    <h5><strong>注册于</strong></h5>
                    <p>{{ $user->created_at->diffForHumans() }}<p>
                    <har>
                    <h5><strong>最后活跃</strong></h5>
                    <p title="{{  $user->last_actived_at }}">{{ $user->last_actived_at->diffForHumans() }}</p>
                    <hr>
                    <h5><strong>个人名片</strong></h5>
                    <div class="card-img-bottom">
                        <div class="media">
                            <div align="center">
                                 <img class="card-img-bottom" src=" {{ $user->qrcode  }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="card ">
                <div class="card-body">
                    <h1 class="mb-0" style="font-size:22px;">{{ $user->name }} <small>{{ $user->email }}</small></h1>
                </div>
            </div>


            <hr>
            {{-- 用户发布的内容 --}}
            <div class="card ">
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link bg-transparent {{ active_class(if_query('tab', null)) }}" href="{{ route('users.show', $user->id) }}">
                                Ta 的话题
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bg-transparent {{ active_class(if_query('tab', 'replies')) }}" href="{{ route('users.show', [$user->id, 'tab' => 'replies']) }}">
                                Ta 的回复
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bg-transparent {{ active_class(if_query('tab', 'votes')) }}" href="{{ route('users.show', [$user->id, 'tab' => 'votes']) }}">
                                Ta 的点赞
                            </a>
                        </li>
                    </ul>
                    @if (if_query('tab', 'replies'))
                        @include('users._replies', ['replies' => $user->replies()->with('topic')->recent()->paginate(6)])
                    @elseif (if_query('tab', 'votes'))
                        @include('users._votes', ['topics' => $user->votedItems(App\Models\Topic::class)->paginate(6)])
                    @else
                        @include('users._topics', ['topics' => $user->topics()->recent()->paginate(6)])
                    @endif
                </div>
            </div>

        </div>
    </div>
@stop
