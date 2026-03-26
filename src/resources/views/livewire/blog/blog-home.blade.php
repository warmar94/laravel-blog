<div class="min-h-screen bg-gradient-to-b from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-950">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-zinc-900 dark:text-white mb-4">{{ config('liveblog.site.hero_title', 'Our Blog') }}</h1>
            <p class="text-xl text-zinc-600 dark:text-zinc-400">{{ config('liveblog.site.hero_description', 'Stay updated with our latest articles') }}</p>
        </div>

        <div class="mb-8">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search articles..." 
                class="w-full px-6 py-4 rounded-2xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-600"
            >
        </div>

        @if($articles->isEmpty())
            <div class="text-center py-20">
                <p class="text-2xl text-zinc-600 dark:text-zinc-400">No articles found</p>
            </div>
        @else
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($articles as $article)
                    <a href="{{ route('blog.show', $article->slug) }}" wire:navigate class="group">
                        <div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-zinc-200 dark:border-zinc-700 hover:scale-105">
                            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-3 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                                {{ $article->title }}
                            </h2>
                            <p class="text-zinc-600 dark:text-zinc-400 mb-4 line-clamp-3">{{ $article->metadesc }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-zinc-500 dark:text-zinc-500">{{ $article->published_at->format('F d, Y') }}</span>
                                <span class="text-red-600 dark:text-red-400 font-semibold group-hover:translate-x-2 transition-transform">Read more →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>
