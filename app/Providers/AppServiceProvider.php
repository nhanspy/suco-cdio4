<?php

namespace App\Providers;

use App\Entities\Comment;
use App\Entities\Like;
use App\Entities\Project;
use App\Entities\Translation;
use App\Observers\CommentObserver;
use App\Observers\LikeObserver;
use App\Observers\TranslationObserver;
use App\Services\Auth\AuthService;
use App\Observers\ProjectObserver;
use App\Services\DeviceService;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DeviceService::class);
        $this->app->singleton(AuthService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @param UrlGenerator $url
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        Schema::defaultStringLength(191);

        if (env('APP_ENV') === 'production') {
            $url->forceScheme('https');
        }

        // observer
        Comment::observe(CommentObserver::class);
        Like::observe(LikeObserver::class);
        Project::observe(ProjectObserver::class);
        Translation::observe(TranslationObserver::class);
    }
}
