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
        'name', 'email', 'phone', 'password', 'introduction'
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
}
