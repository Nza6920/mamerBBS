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

    public function getAvatarAttribute()
    {
        // 用户默认头像
        if ($this->attributes['avatar'] == null ||
            ! file_exists(str_replace(config('app.url'), public_path(), $this->attributes['avatar']))) {
            return \Avatar::create($this->name)->toBase64();
        }
        return $this->attributes['avatar'];
    }
}
