<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;
use App\Models\Category;
use App\Models\Topic;
use App\Notifications\TopicReplied;
use Illuminate\Support\Facades\Cache;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class CategoryObserver
{
    // 再保存时清空 cache_key 对应的缓存
    public function saved(Category $category)
    {
        Cache::forget($category->cache_key);
    }

    // 再删除时清空 cache_key 对应的缓存
    public function deleted(Category $category)
    {
        Cache::forget($category->cache_key);
    }
}
