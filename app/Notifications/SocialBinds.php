<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class SocialBinds extends Notification implements ShouldQueue
{
    use Queueable;

    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('您在 Mamer论坛 发起了绑定请求, 链接有效时间为1小时, 请尽快处理, 如非本人操作, 请忽略.')
                    ->action('查看详情', $this->verificationUrl($notifiable->getKey()))
                    ->line('感谢您使用 Mamer论坛 !');
    }

    protected function verificationUrl($id)
    {
        return URL::temporarySignedRoute(
            'social.bind.confirm', Carbon::now()->addMinutes(60), ['id' => $id]
        );
    }
 }
