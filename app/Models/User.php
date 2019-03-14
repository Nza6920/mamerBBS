<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements MustVerifyEmailContract, JWTSubject
{
    use Notifiable, MustVerifyEmailTrait;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'introduction', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // 头像访问器
    public function getAvatarAttribute()
    {
        // 用户默认头像
//        if ($this->attributes['avatar'] == null ||
//            ! file_exists(str_replace(config('app.url'), public_path(), $this->attributes['avatar']))) {
//            return \Avatar::create($this->attributes['email'])->toBase64();
//        }
        // 方便数据填充
        if ($this->attributes['avatar'] == null) {
            return \Avatar::create($this->attributes['email'])->toBase64();
        }
        return $this->attributes['avatar'];
    }

    // 头像修改器
    public function setAvatarAttribute($avatar)
    {
        $this->attributes['avatar'] = $avatar;
    }
}
