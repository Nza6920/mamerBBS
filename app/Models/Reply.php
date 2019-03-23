<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    // 回复属于话题
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    // 回复属于用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
