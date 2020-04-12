<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicTransformer;
use Dingo\Api\Contract\Http\Request;

class TopicsController extends Controller
{
    // 新建话题
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic, new TopicTransformer())->setStatusCode(201);
    }

    // 编辑话题
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return $this->response->item($topic, new TopicTransformer());
    }

    // 删除话题
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        $topic->delete();

        $this->response->noContent();
    }

    // 话题列表
    public function index(Request $request, Topic $topic)
    {
        $query = $topic->query();
        if ($categoryId = $request->category_id) {
            $query->where('category_id', $categoryId);
        }

        switch ($request->order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }

        $topics = $query->paginate(15);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    // 某个用户的话题
    public function userIndex(User $user, Request $request)
    {
        $topics = $user->topics()->recent()->paginate(15);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    // 话题详情
    public function show(Topic $topic)
    {
        return $this->response->item($topic, new TopicTransformer());
    }

    // 点赞
    public function upVote(Topic $topic)
    {
        $this->user()->upVote($topic);
        return $this->response->noContent();
    }

    // 取消点赞
    public function cancelVote(Topic $topic)
    {
        $this->user()->cancelVote($topic);
        return $this->response->noContent();
    }
}
