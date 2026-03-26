<?php

namespace LaravelBlog\Console\Commands;

use Illuminate\Console\Command;

class InstallBlogCommand extends Command
{
    protected $signature = 'blog:install';
    protected $description = 'Install LaravelBlog package files';

    public function handle()
    {
        $this->info('📦 Installing LaravelBlog...');

        $comments = $this->confirm('Do you want to enable comments?', true);

        $this->call('vendor:publish', ['--tag' => 'laravel-blog-config', '--force' => true]);

        $configPath = config_path('blog.php');
        $config = file_get_contents($configPath);
        $config = str_replace("'comments' => true", "'comments' => " . ($comments ? 'true' : 'false'), $config);
        file_put_contents($configPath, $config);

        $this->call('vendor:publish', ['--tag' => 'laravel-blog-migrations', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'laravel-blog-views', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'laravel-blog-app', '--force' => true]);

        $this->newLine();
        $this->info('✅ LaravelBlog installed successfully!');
        $this->newLine();
        $this->warn('📝 Next steps:');
        $this->line('1. Run migrations: php artisan migrate');
        $this->line('2. Add routes to routes/web.php:');
        $this->newLine();
        $this->line("   Route::get('/blog', \\App\\Livewire\\Blog\\BlogHome::class)->name('blog.home');");
        $this->line("   Route::get('/blog/{slug}', \\App\\Livewire\\Blog\\BlogShow::class)->name('blog.show');");
        $this->line("   Route::get('/blog-admin', \\App\\Livewire\\Blog\\BlogAdmin::class)->name('blog.admin');");
        $this->newLine();
        $this->line('3. Configure admin emails in config/blog.php');
        $this->newLine();

        return 0;
    }
}