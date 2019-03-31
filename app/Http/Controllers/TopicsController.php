<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, Topic $topic, User $user)
    {
        $topics = $topic->withOrder($request->order)->paginate(20);
        $active_users = $user->getActiveUsers();
        return view('topics.index', compact('topics', 'active_users'));
    }

    public function show(Request $request, Topic $topic)
    {
        // URL 矫正
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
	    $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

        return redirect()->to($topic->link())->with('success', '成功创建话题！');
    }

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());
        return redirect()->to($topic->link())->with('success', '编辑成功！');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '刪除成功!');
	}

	// 上传图片
	public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据
        $data = [
            'success' => false,
            'msg' => '上传失败',
            'file_path' => ''
        ];
        // 判断是否有上传文件, 并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($file, 'topics', \Auth::id(), 1024 );
            // 图片保存到本地
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功";
                $data['success']   = true;
            }
        }

        return $data;
    }

    // 获取当前文章回复的人
    public function repliers(Topic $topic)
    {
        // 判断是否有回复
        if (!$topic->reply_count > 0) {
            return [];
        }

        $repliers = $topic->replies;

        // 拿到当前文章所有回复的用户(除了当前登陆用户和作者)
        $users = $repliers->filter(function ($reply, $key) {
            return $reply->user_id != Auth::user()->id && $reply->user_id != $reply->topic->user_id;
        })->map(function ($reply, $key) {
            return $reply->user_id;
        });

        return User::find($users)->pluck('name');
    }
}
