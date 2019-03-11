<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;
use App\Models\User;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }

    // 用户个人界面
    public function show(User $user)
    {
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
}
