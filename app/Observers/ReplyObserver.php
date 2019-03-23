<?php

namespace App\Observers;

use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        // 文章的回复数加一
        $reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();
    }

    // xss 防护
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }
}
