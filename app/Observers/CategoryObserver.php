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
    public function saved(Category $category)
    {
        Cache::forget('categories');
    }

    public function deleted(Category $category)
    {
        Cache::forget('categories');
    }
}
