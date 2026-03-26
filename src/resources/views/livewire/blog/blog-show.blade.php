@php
    use App\Services\RichTextRenderer;
    $isAdmin = auth()->check() && in_array(auth()->user()->email, config('liveblog.admin.emails', []));
@endphp

<div class="min-h-screen bg-gradient-to-b from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-950" x-data="{ showDeleteConfirm: false, deleteCommentId: null }">
    <div class="max-w-4xl mx-auto px-4 py-16">
        <a href="{{ route('blog.home') }}" wire:navigate class="inline-flex items-center text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 mb-8 font-semibold">
            ← Back to Blog
        </a>

        <article class="bg-white dark:bg-zinc-800 rounded-3xl shadow-2xl p-12 mb-12 border border-zinc-200 dark:border-zinc-700">
            <h1 class="text-5xl font-bold text-zinc-900 dark:text-white mb-4">{{ $article->title }}</h1>
            <div class="flex items-center gap-4 text-zinc-600 dark:text-zinc-400 mb-8 pb-8 border-b border-zinc-200 dark:border-zinc-700">
                <span>{{ $article->published_at->format('F d, Y') }}</span>
            </div>

            @auth
                @if($isAdmin)
                    <div class="mb-6 flex gap-3">
                        <button wire:click="deleteArticle" wire:confirm="Are you sure you want to delete this article?" class="px-4 py-2 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold">
                            Delete Article
                        </button>
                        <a href="{{ route('blog.admin') }}" class="px-4 py-2 rounded-full bg-zinc-600 hover:bg-zinc-700 text-white font-semibold">
                            Edit in Admin
                        </a>
                    </div>
                @endif
            @endauth

            <div class="prose prose-lg prose-zinc dark:prose-invert max-w-none">
                {!! RichTextRenderer::render($article->article) !!}
            </div>
        </article>

        @if(config('liveblog.features.comments', true))
            <div class="bg-white dark:bg-zinc-800 rounded-3xl shadow-2xl p-12 border border-zinc-200 dark:border-zinc-700">
                <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-8">
                    Comments ({{ $article->comments->count() }})
                </h2>

                @auth
                    <div class="bg-zinc-50 dark:bg-zinc-900 rounded-2xl p-8 mb-8 border border-zinc-200 dark:border-zinc-700">
                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-white mb-4">
                            {{ $replyingTo ? 'Replying to comment' : 'Leave a Comment' }}
                        </h3>
                        @if($replyingTo)
                            <button wire:click="cancelReply" class="text-sm text-red-600 dark:text-red-400 hover:underline mb-3">
                                Cancel Reply
                            </button>
                        @endif
                        <form wire:submit.prevent="postComment">
                            <textarea 
                                wire:model="comment" 
                                rows="4" 
                                maxlength="3000" 
                                placeholder="Share your thoughts..." 
                                class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white resize-none mb-3"
                            ></textarea>
                            @error('comment') <span class="text-red-500 text-sm block mb-2">{{ $message }}</span> @enderror
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ strlen($comment) }}/3000</span>
                                <button type="submit" class="px-6 py-2 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold">
                                    {{ $replyingTo ? 'Reply' : 'Comment' }}
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-zinc-50 dark:bg-zinc-900 rounded-2xl p-8 mb-8 border border-zinc-200 dark:border-zinc-700 text-center">
                        <p class="text-zinc-600 dark:text-zinc-400">
                            Please <a href="{{ route('login') }}" class="text-red-600 dark:text-red-400 hover:underline font-semibold">login</a> to comment
                        </p>
                    </div>
                @endauth

                <div class="space-y-6">
                    @foreach($article->comments as $comment)
                        @include('liveblog::partials.comment', ['comment' => $comment, 'level' => 0])
                    @endforeach
                </div>
            </div>

            <div x-show="showDeleteConfirm" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="showDeleteConfirm = false">
                <div class="bg-white dark:bg-zinc-800 rounded-2xl p-8 max-w-md shadow-2xl">
                    <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Delete Comment?</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 mb-6">This action cannot be undone.</p>
                    <div class="flex gap-3">
                        <button @click="showDeleteConfirm = false" class="flex-1 px-4 py-2 rounded-full bg-zinc-200 dark:bg-zinc-700 text-zinc-900 dark:text-white hover:bg-zinc-300 dark:hover:bg-zinc-600 font-semibold">
                            Cancel
                        </button>
                        <button @click="$wire.deleteComment(deleteCommentId); showDeleteConfirm = false" class="flex-1 px-4 py-2 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
