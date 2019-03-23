<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        // 更新文章回复数
        $reply->topic->updateReplyCount();

        // 通知话题作者有新的评论
        $reply->topic->user->notify(new TopicReplied($reply));
    }

    public function creating(Reply $reply)
    {
        // xss 防护
        $reply->content = clean($reply->content, 'user_topic_body');
    }

   public function deleted(Reply $reply)
   {
       // 更新文章回复数
       $reply->topic->updateReplyCount();
   }
}
