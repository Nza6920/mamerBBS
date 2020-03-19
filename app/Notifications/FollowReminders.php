<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FollowReminders extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // 在那些频道上发送
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    // 数据库通知
    public function toDatabase($notification)
    {
        $link = $this->user->link();

        // 存入数据库里
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_avatar' => $this->user->avatar,
            'user_link' => $link,
        ];
    }

    // 邮件通知
    public function toMail($notification)
    {
        return (new MailMessage)
            ->line('用户 '. $this->user->name .' 关注了您!')
            ->action('查看详情', $this->user->link())
            ->line('感谢您使用 Mamer论坛 !');
    }
}
