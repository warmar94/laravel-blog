<?php

namespace LiveBlog;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use LiveBlog\Console\Commands\InstallBlogCommand;

class LiveBlogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/liveblog.php', 'liveblog');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallBlogCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/config/liveblog.php' => config_path('liveblog.php'),
            ], 'liveblog-config');

            $this->publishes([
                __DIR__ . '/database/migrations' => database_path('migrations'),
            ], 'liveblog-migrations');

            $this->publishes([
                __DIR__ . '/resources/views' => resource_path('views/liveblog'),
            ], 'liveblog-views');

            $this->publishes([
                __DIR__ . '/app/Livewire' => app_path('Livewire'),
                __DIR__ . '/app/Models' => app_path('Models'),
                __DIR__ . '/app/Services' => app_path('Services'),
            ], 'liveblog-app');
        }

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'liveblog');
    }
}
