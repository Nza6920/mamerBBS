@extends('layouts.app')

@section('title', $topic->title)
@section('description', $topic->excerpt)

@section('content')

    <div class="row">

        <div class="col-lg-3 col-md-3 hidden-sm hidden-xs author-info">
            <div class="card ">
                <div class="card-body">
                    <div class="text-center">
                        作者：{{ $topic->user->name }}
                    </div>
                    <hr>
                    <div class="media">
                        <div align="center">
                            <a href="{{ route('users.show', $topic->user->id) }}">
                                <img class="thumbnail img-fluid" src="{{ $topic->user->avatar }}" width="300px" height="300px">
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        扫码阅读
                    </div>
                    <br>
                    <div class="media">
                        <div align="center">
                            <img class="thumbnail img-fluid" src="{{ $topic->qrcode }}" width="300px" height="300px">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 topic-content">
            <div class="card">
                <div class="card-body">
                    <h1 class="text-center mt-3 mb-3">
                        {{ $topic->title }}
                    </h1>

                    <div class="article-meta text-center text-secondary">
                        {{ $topic->created_at->diffForHumans() }}
                        ⋅
                        <i class="far fa-comment"></i>
                        {{ $topic->reply_count }}
                    </div>

                    <div class="topic-body mt-4 mb-4">
                        {!! $topic->body !!}
                    </div>
                    @auth
                     <div class="operate">
                            @can('update', $topic)
                                    <hr>
                                    <a href="{{ route('topics.edit', $topic->id) }}" class="btn btn-outline-secondary btn-sm" role="button">
                                        <i class="far fa-edit"></i> 编辑
                                    </a>
                                    <form action="{{ route('topics.destroy', $topic->id) }}" method="post"
                                          style="display: inline-block;"
                                          onsubmit="return confirm('您确定要删除吗？');">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                            <i class="far fa-trash-alt"></i> 删除
                                        </button>
                                    </form>
                            @endcan

{{--                            @if(app()->isLocal())--}}
                                <a href="{{ route('topics.show.pdf', $topic->id) }}" class="btn btn-outline-secondary btn-sm" role="button" target="_blank">
                                    <i class="far fa-file-pdf"></i> 生成 PDF
                                </a>
                                <a href="{{ route('topics.show.image', $topic->id) }}" class="btn btn-outline-secondary btn-sm" role="button" target="_blank">
                                    <i class="far fa-file-image"></i> 生成图片
                                </a>
                            {{--@endIf--}}
                        </div>
                    @endauth

                </div>
            </div>

            {{-- 用户点赞列表 --}}
            <div class="card topic-vote mt-4">
                <div class="card-body">
                    @includeWhen(Auth::check(), 'topics._topic_vote', ['users' => $topic->voters->pluck('avatar', 'id')->all(), 'topic' => $topic])
                </div>
            </div>

            {{-- 用户回复列表 --}}
            <div class="card topic-reply mt-4">
                <div class="card-body">
                    @includeWhen(Auth::check(), 'topics._reply_box', ['topic' => $topic])
                    @include('topics._reply_list', ['replies' => $topic->replies()->with('user')->get()])
                </div>
            </div>
        </div>
    </div>
@stop
