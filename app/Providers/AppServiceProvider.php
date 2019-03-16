<?php

namespace App\Providers;

use App\Models\Topic;
use App\Models\User;
use App\Observers\TopicObserver;
use App\Observers\UserObserver;
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
//        User::observe(UserObserver::class);
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
    }
}
