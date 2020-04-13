<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\Image;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicTransformer;
use App\Transformers\UserTransformer;
use Dingo\Api\Contract\Http\Request;

class UsersController extends Controller
{
    // 用户注册
    public function store(UserRequest $request, User $user)
    {
        $verifyData = \Cache::get($request->verification_key);

        // key 不存在 or 失效
        if (!$verifyData) {
            return $this->response->error('验证码失效', 422);
        }
        // 验证码不正确
        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回 401
            return $this->response->errorUnauthorized('验证码错误');
        }
        // 创建用户
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $verifyData['phone'];
        $user->avatar = null;
        $user->password = bcrypt($request->password);
        $user->save();
        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    // 当前登陆用户信息
    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }

    // 编辑用户信息
    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'introduction']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }

    // 活跃用户
    public function activedIndex(User $user)
    {
        return $this->response->collection($user->getActiveUsers(), new UserTransformer());
    }

    // 我的点赞
    public function myVotes()
    {
        $topics = $this->user()->votedItems(Topic::class)->paginate(15);
        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * 当前登陆用户的粉丝
     * @param User $user 用户
     * @return \Dingo\Api\Http\Response
     */
    public function followers(User $user)
    {
        // 获取粉丝列表
        $followers = $user->followers()->paginate(15);

        return $this->response->paginator($followers, new UserTransformer());
    }

    /**
     * 当前登陆用户的粉丝
     * @param User $user 用户
     * @return \Dingo\Api\Http\Response
     */
    public function followings(User $user)
    {
        // 获取关注列表
        $followings = $user->followings()->paginate(15);

        return $this->response->paginator($followings, new UserTransformer());
    }

    /**
     * 关注某人
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function follow(Request $request)
    {

        $user = $this->user();

        // 关注人id
        $followId = $request->id;

        // 判断用户是否关注了
        if (!$user->isFollowing($followId)) {
            $user->follow($request->id);
        }

        return $this->response->noContent();
    }

    /**
     * 取关某人
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function unFollow(Request $request)
    {

        $user = $this->user();

        // 关注人id
        $followId = $request->id;

        // 判断用户是否关注了
        if ($user->isFollowing($followId)) {
            $user->unFollow($request->id);
        }

        return $this->response->noContent();
    }

    /**
     * 判断当前登陆用户是否关注某用户
     * @param Request $request
     * @return array
     */
    public function isFollowing(Request $request)
    {
        return ['followed' => $this->user()->isFollowing($request->id)];
    }
}
