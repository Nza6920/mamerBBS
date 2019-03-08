@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="col-md-8 offset-md-2">

            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="glyphicon glyphicon-edit"></i> 编辑个人资料
                    </h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        @include('shared._error')

                        <div class="form-group">
                            <label for="name-field">用户名</label>
                            <input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $user->name) }}"  required/>
                        </div>
                        <div class="form-group">
                            <label for="email-field">邮 箱</label>
                            <input class="form-control" type="text" name="email" id="email-field" value="{{ old('email', $user->email) }}" readonly onfocus="this.blur()" />
                        </div>
                        <div class="form-group">
                            <label for="introduction-field">个人简介</label>
                            <textarea name="introduction" id="introduction-field" class="form-control" rows="3">{{ old('introduction', $user->introduction) }}</textarea>
                        </div>

                        <div class="form-group mb-4">
                            {{--<label for="" class="avatar-label">用户头像</label>--}}
                            {{--<input type="file" name="avatar" class="form-control-file">--}}
                            {{--<label for="file" class="btn btn-info">更换头像</label>--}}
                            {{--<input id="file" type="file" name="avatar" style="display:none" class="form-control-file">--}}
                            <div class="file-container" style="display:inline-block;position:relative;overflow: hidden;vertical-align:middle">
                                <button class="btn btn-info fileinput-button" type="button"><span style="color: #e9ecef">浏览</span></button>
                                <input type="file" id="jobData" name="avatar" onchange="loadFile(this.files[0])" style="position:absolute;top:0;left:0;font-size:34px; opacity:0">
                            </div>
                            <span id="filename" style="vertical-align: middle;font-size: 14px; color: #E6A23C">未选择文件</span>
                            @if($user->avatar)
                                <br>
                                <br>
                                <img class="thumbnail img-responsive" src="{{ $user->avatar }}" width="200" />
                            @endif
                        </div>

                        <div class="well well-sm">
                            <button type="submit" class="btn btn-primary">保存</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadFile(file){
            $("#filename").html(file.name);
        }
    </script>
@endsection

