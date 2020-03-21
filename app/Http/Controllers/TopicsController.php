<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Barryvdh\Snappy\Facades\SnappyImage;
use Illuminate\Http\Request;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use PDF;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    // 排序话题
    public function index(Request $request, Topic $topic, User $user, Link $link)
    {
        $topics = $topic->withOrder($request->order)->paginate(20);
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();

        return view('topics.index', compact('topics', 'active_users', 'links'));
    }

    // 话题详情
    public function show(Request $request, Topic $topic, ImageUploadHandler $uploader)
    {
        // URL 矫正
        if (!empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }
        $qrcode = $topic->qrcode;

        if (!$qrcode) {
            $qrcode = $topic->qrcodeByPng();
            $fileInfo = $uploader->getPath('qrcodes/topics', $topic->id, 'png');
            $filename = $fileInfo['folder_name'] . '/' . $fileInfo['file_name'];

            // 创建目录
            Storage::disk('public')->makeDirectory($fileInfo['folder_name']);

            Image::make($qrcode)->save($filename);

            // 上传 qrcode
            $topic->qrcode = Storage::disk('public')->url($filename);
            $topic->save();
        }

        return view('topics.show', compact('topic'));
    }

    // 创建话题页面
    public function create(Topic $topic)
    {
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    // 创建话题
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

        return redirect()->to($topic->link())->with('success', '成功创建话题！');
    }

    // 编辑话题页面
    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    // 编辑话题逻辑
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());
        return redirect()->to($topic->link())->with('success', '编辑成功！');
    }

    // 删除话题
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
            $result = $uploader->save($file, 'topics', \Auth::id(), 1024);
            // 图片保存到本地
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg'] = "上传成功";
                $data['success'] = true;
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

    // 显示 pdf
    public function pdf(Topic $topic)
    {
        return PDF::loadFile(route('topics.show', $topic->id))
            ->inline('topic-' . $topic->id . '.pdf');
    }

    // 显示图片
    public function image(Topic $topic)
    {
        return SnappyImage::loadFile(route('topics.show', $topic->id))
            ->setOption('width', 595)
            ->setOption('format', 'png')
            ->inline('topics-' . $topic->id . '.png');
    }

    // 点赞
    public function upVote(Topic $topic)
    {
        Auth::user()->upVote($topic);
        return back();
    }

    // 点赞
    public function cancelVote(Topic $topic)
    {
        Auth::user()->cancelVote($topic);
        return back();
    }
}
