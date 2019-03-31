<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use App\Observers\ReplyObserver;
use App\Observers\TopicObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        \Carbon\Carbon::setLocale('zh');
        Topic::observe(TopicObserver::class);
        Reply::observe(ReplyObserver::class);
        User::observe(UserObserver::class);
        Cache::forget('categories');
        if (!Cache::has('categories'))
            Cache::forever('categories', Category::all()->map(function ($category){
                return collect($category->only(['id','name']));
            }));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \API::error(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            abort(404, 'Not Found');
        });

        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(403, $exception->getMessage());
        });

        if (app()->isLocal()) {
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }
    }
}
