<?php

namespace LaravelBlog;

use Illuminate\Support\ServiceProvider;
use LaravelBlog\Console\Commands\InstallBlogCommand;

class LaravelBlogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/blog.php', 'blog');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallBlogCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/config/blog.php' => config_path('blog.php'),
            ], 'laravel-blog-config');

            $this->publishes([
                __DIR__ . '/database/migrations' => database_path('migrations'),
            ], 'laravel-blog-migrations');

            $this->publishes([
                __DIR__ . '/resources/views' => resource_path('views'),
            ], 'laravel-blog-views');

            $this->publishes([
                __DIR__ . '/app/Livewire' => app_path('Livewire'),
                __DIR__ . '/app/Models' => app_path('Models'),
                __DIR__ . '/app/Services' => app_path('Services'),
            ], 'laravel-blog-app');
        }

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'laravel-blog');
    }
}