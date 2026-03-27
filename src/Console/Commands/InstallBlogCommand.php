<?php

namespace LaravelBlog\Console\Commands;

use Illuminate\Console\Command;

class InstallBlogCommand extends Command
{
    protected $signature = 'blog:install';
    protected $description = 'Install LaravelBlog package files';

    public function handle()
    {
        $this->info('📦 Installing Laravel Blog by Warmar...');

        $comments   = $this->confirm('Enable comments?', true);
        $categories = $this->confirm('Enable categories?', true);

        $this->call('vendor:publish', ['--tag' => 'laravel-blog-config', '--force' => true]);

        $configPath = config_path('blog.php');
        $config = file_get_contents($configPath);
        $config = str_replace(
            "'comments'   => true",
            "'comments'   => " . ($comments ? 'true' : 'false'),
            $config
        );
        $config = str_replace(
            "'categories' => true",
            "'categories' => " . ($categories ? 'true' : 'false'),
            $config
        );
        file_put_contents($configPath, $config);

        $this->call('vendor:publish', ['--tag' => 'laravel-blog-migrations', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'laravel-blog-views',      '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'laravel-blog-app',        '--force' => true]);

        $this->newLine();
        $this->info('✅ Laravel Blog installed successfully!');
        $this->newLine();
        $this->warn('📝 Next steps:');
        $this->line('  1. Run migrations:');
        $this->line('     php artisan migrate');
        $this->newLine();
        $this->line('  2. Add routes to routes/web.php:');
        $this->newLine();
        $this->line("     use App\\Livewire\\Blog\\BlogHome;");
        $this->line("     use App\\Livewire\\Blog\\BlogShow;");
        $this->line("     use App\\Livewire\\Blog\\BlogAdmin;");
        $this->newLine();
        $this->line("     Route::get('/blog',         BlogHome::class)->name('blog.home');");
        $this->line("     Route::get('/blog/{slug}',  BlogShow::class)->name('blog.show');");
        $this->line("     Route::get('/blog-admin',   BlogAdmin::class)->name('blog.admin');");
        $this->newLine();
        $this->line('  3. Add admin email(s) in config/blog.php under admin.emails');
        $this->line('  4. Add categories in config/blog.php under categories (optional)');
        $this->line('  5. Run php artisan storage:link if not done already');
        $this->newLine();

        return 0;
    }
}