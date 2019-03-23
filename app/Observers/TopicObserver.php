<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;
use App\Models\Topic;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        // xss 过滤
        $topic->body = clean($topic->body, 'user_topic_body');

        // 生成话题摘录
        $topic->excerpt = make_excerpt($topic->body);
    }

    public function saved(Topic $topic)
    {
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if (! $topic->slug) {
            // 推送任务到队列
            dispatch(new TranslateSlug($topic));
        }
    }

    public function deleted(Topic $topic)
    {
        // 当话题删除是, 所有回复数据删除
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}
