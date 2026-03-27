<div class="min-h-screen bg-white">

    {{-- Hero --}}
    <div class="border-b border-zinc-200">
        <div class="max-w-6xl mx-auto px-6 py-20">
            <div class="flex items-center gap-2 mb-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-600 text-xs font-semibold tracking-wide border border-red-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                    Laravel Blog
                </span>
            </div>
            <h1 class="text-5xl font-bold text-zinc-900 tracking-tight mb-4 leading-tight">
                Our Blog
            </h1>
            <p class="text-xl text-zinc-500 max-w-2xl leading-relaxed">
                Stay updated with our latest articles
            </p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-10">
        <div class="flex flex-col md:flex-row gap-8">

            {{-- Sidebar --}}
            <aside class="md:w-52 flex-shrink-0 space-y-6">
                {{-- Search --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search…"
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent focus:bg-white transition text-sm">
                </div>

                {{-- Categories --}}
                @if($categories->count() > 0)
                <div>
                    <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-3">Categories</p>
                    <div class="space-y-1">
                        <button wire:click="$set('category', '')"
                                class="w-full text-left px-3 py-2 rounded-xl text-sm transition font-medium {{ $category === '' ? 'bg-red-50 text-red-600' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">
                            All Articles
                        </button>
                        @foreach($categories as $cat)
                        <button wire:click="$set('category', '{{ $cat }}')"
                                class="w-full text-left px-3 py-2 rounded-xl text-sm transition {{ $category === $cat ? 'bg-red-50 text-red-600 font-medium' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">
                            {{ $cat }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>

            {{-- Main content --}}
            <div class="flex-1 min-w-0">
        @if($articles->isEmpty())
            <div class="text-center py-24 border border-dashed border-zinc-200 rounded-2xl">
                <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-zinc-500 font-medium">No articles found</p>
                <p class="text-zinc-400 text-sm mt-1">Try adjusting your search</p>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($articles as $article)
                    <a href="{{ route('blog.show', $article->slug) }}" wire:navigate class="group block">
                        <article class="h-full bg-white rounded-2xl border border-zinc-200 p-6 hover:border-red-200 hover:shadow-lg hover:shadow-red-50 transition-all duration-200">
                            <div class="flex items-center gap-2 mb-4 flex-wrap">
                                <span class="text-xs text-zinc-400 font-medium">{{ $article->published_at->format('M d, Y') }}</span>
                                @if($article->category)
                                    <span class="text-zinc-300">·</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-50 text-red-600 text-xs font-medium border border-red-100">{{ $article->category }}</span>
                                @endif
                            </div>
                            <h2 class="text-lg font-bold text-zinc-900 mb-3 leading-snug group-hover:text-red-600 transition-colors line-clamp-2">
                                {{ $article->title }}
                            </h2>
                            <p class="text-zinc-500 text-sm leading-relaxed line-clamp-3 mb-5">
                                {{ $article->metadesc }}
                            </p>
                            <div class="flex items-center text-red-600 text-sm font-semibold">
                                Read article
                                <svg class="w-4 h-4 ml-1.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @endif
            </div>{{-- /main --}}
        </div>{{-- /flex --}}
    </div>
</div>