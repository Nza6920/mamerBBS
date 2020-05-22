<?php

namespace App\Models;

use Jcc\LaravelVote\CanBeVoted;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Topic extends Model
{
    use CanBeVoted;

    protected $vote = User::class;

    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug', 'qrcode'
    ];

    // 文章属于话题
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 文章属于用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 话题拥有回复
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }
        // 预加载防止 N+1 问题
        return $query->with('user', 'category');
    }

    public function scopeRecentReplied($query)
    {
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    // 更新文章回复数
    public function updateReplyCount()
    {
        $this->reply_count = $this->replies->count();
        $this->save();
    }

    // 个人二维码
    public function qrcodeByPng()
    {
        return QrCode::format('png')
            ->size(300)
            ->margin(0)
            ->errorCorrection('H')
            ->merge(public_path('uploads/images/system/logo.png'), 0.3, true)
            ->generate(route('topics.show', $this));
    }
}
