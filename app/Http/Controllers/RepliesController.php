<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Http\Requests\ReplyRequest;
use App\Models\User;
use App\Notifications\TopicReplied;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(ReplyRequest $request, Reply $reply)
    {

        $reply->content = $request->content1;
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;

        // 找出被@的人
        $at = $reply->matchAt();
        $reply->content = $at['content'];
        $reply->save();

        // 给每个人发通知
        if (!empty($at['reply_user_id'])) {
            $users = User::find($at['reply_user_id']);    // find 方法会自动去除重复的查询
            $users->each(function ($user, $key) use ($reply){
                if ($user->id != Auth::user()->id && $user->id != $reply->topic->user->id)
                    $user->notify(new TopicReplied($reply, true));
            });
        }

        return redirect()->to($reply->topic->link())->with('success', '评论创建成功！');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);
        $reply->delete();

        return redirect()->to($reply->topic->link())->with('success', '评论删除成功！');
    }
}
