<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'followers', 'followings']]);
    }

    // 用户个人界面
    public function show(User $user, ImageUploadHandler $uploader)
    {
        $qrcode = $user->qrcode;

        if (!$qrcode) {
            $qrcode = $user->qrcodeByPng();

            $fileInfo = $uploader->getPath('qrcodes/users', $user->id, 'png');

            $filename = $fileInfo['folder_name'] . '/' . $fileInfo['file_name'];

            // 创建目录
            Storage::disk('public')->makeDirectory($fileInfo['folder_name']);

            Image::make($qrcode)->save($filename);

            // 上传 qrcode
            $user->qrcode = Storage::disk('public')->url($filename);

            $user->save();
        }

        return view('users.show', compact('user'));
    }

    // 编辑个人资料界面
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    // 编辑个人资料
    public function update(UserRequest $request, User $user, ImageUploadHandler $uploader)
    {
        $this->authorize('update', $user);
        $data = $request->all();

        if ($request->avatar) {

            $result = $uploader->save($request->avatar, 'avatars', $user->id, 416);
            // 图片格式不正确
            if (! $result) {
                return redirect()->back()->with('errors', collect(['格式错啦, 头像必须是jpeg,png,jpg,gif格式的图片']));
            }
            $data['avatar'] = $result['path'];
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功! ');
    }

    // 关注的人
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(10);
        $title = $user->name . '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    // 粉丝
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(10);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }
}
