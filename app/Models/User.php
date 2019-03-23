<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements MustVerifyEmailContract, JWTSubject
{
    use MustVerifyEmailTrait;

    use Notifiable {
        notify as protected laravelNotify;
    }

    // 重写 notify 方法
    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }

        // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    // 消除通知
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'introduction', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // 用户有很多话题
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    // 用户拥有很多回复
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // 头像访问器
//    public function getAvatarAttribute()
//    {
//        // 用户默认头像
////        if ($this->attributes['avatar'] == null ||
////            ! file_exists(str_replace(config('app.url'), public_path(), $this->attributes['avatar']))) {
////            return \Avatar::create($this->attributes['email'])->toBase64();
////        }
//
//        // 方便数据填充
//        if ($this->attributes['avatar'] == null) {
//            return \Avatar::create($this->attributes['email'])->toBase64();
//        }
//        return $this->attributes['avatar'];
//    }


    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }
}
