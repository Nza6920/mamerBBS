<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TopicReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public $reply;
    public $flag;

    public function __construct(Reply $reply, $flag = false)
    {
        // 注入回复实体, 方便 toDatabase 方法中使用
        $this->reply = $reply;
        $this->flag = $flag;
    }

    // 在那些频道上发送
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }


    // 数据库通知
    public function toDatabase($notification)
    {
        $topic = $this->reply->topic;

        $link = $topic->link(['#reply' . $this->reply->id]);

        // 存入数据库里
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
        ];
    }

    // 邮件通知
    public function toMail($notification)
    {
        $url = $this->reply->topic->link(['#reply' . $this->reply->id]);

        return (new MailMessage)
            ->line($this->flag ? '有人在回复中提及了你' : '你的话题有新回复!')
            ->action('查看回复', $url)
            ->line('感谢您使用 Mamer论坛 !');
    }
}
