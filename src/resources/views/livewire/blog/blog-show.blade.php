@php
    use App\Services\Blog\RichTextRenderer;
    // Adjust roles to match User model — same roles as BlogShow::isAdmin()
    $isAdmin = auth()->check()
        && in_array(auth()->user()->role, ['superadmin', 'admin', 'moderator']);
@endphp

<div class="min-h-screen bg-white" x-data="{ showDeleteConfirm: false, deleteCommentId: null }">

    {{-- Top bar --}}
    <div class="border-b border-zinc-200 bg-white sticky top-0 z-10 backdrop-blur-sm bg-white/90">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('blog.home') }}" wire:navigate
               class="inline-flex items-center gap-2 text-sm text-zinc-500 hover:text-red-600 transition-colors font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                </svg>
                {{ __('Back to Blog') }}
            </a>
            @auth
                @if($isAdmin)
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.blog.admin') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-semibold transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            {{ __('Edit') }}
                        </a>
                        <button wire:click="deleteArticle"
                                wire:confirm="{{ __('Are you sure you want to delete this article?') }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            {{ __('Delete') }}
                        </button>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 py-12">

        {{-- Article --}}
        <article class="mb-16">
            {{-- Meta --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-600 text-xs font-semibold border border-red-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                        {{ $article->published_at->format('M d, Y') }}
                    </span>
                    @if($article->category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-zinc-100 text-zinc-600 text-xs font-medium border border-zinc-200">
                            {{ $article->category }}
                        </span>
                    @endif
                </div>
                <h1 class="text-4xl font-bold text-zinc-900 tracking-tight leading-tight mb-4">
                    {{ __($article->title) }}
                </h1>
                <div class="h-px bg-zinc-100 mt-8"></div>
            </div>

            {{-- Content --}}
            <div class="blog-content text-zinc-700 leading-relaxed text-base" style="line-height:1.8">
                {!! RichTextRenderer::render($article->article, translate: true) !!}
            </div>
        </article>

        {{-- Comments --}}
        @if(config('blog.features.comments', true))
        <div class="border-t border-zinc-100 pt-12">
            <h2 class="text-2xl font-bold text-zinc-900 mb-8 flex items-center gap-3">
                {{ __('Comments') }}
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-zinc-100 text-zinc-500 text-sm font-semibold">
                    {{ $article->comments->count() }}
                </span>
            </h2>

            {{-- Write comment --}}
            @auth
                <div class="bg-zinc-50 rounded-2xl p-6 mb-8 border border-zinc-200">
                    <h3 class="text-sm font-semibold text-zinc-700 mb-4">
                        {{ $replyingTo ? __('Replying to comment') : __('Leave a Comment') }}
                    </h3>
                    @if($replyingTo)
                        <button wire:click="cancelReply" class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-700 font-medium mb-3">
                            × {{ __('Cancel Reply') }}
                        </button>
                    @endif
                    <form wire:submit.prevent="postComment">
                        <textarea wire:model="comment" rows="4" maxlength="3000"
                                  placeholder="{{ __('Share your thoughts...') }}"
                                  class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 placeholder-zinc-400 resize-none focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm transition mb-3"></textarea>
                        @error('comment') <span class="text-red-500 text-xs block mb-2">{{ $message }}</span> @enderror
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-zinc-400">{{ strlen($comment) }}/3000</span>
                            <button type="submit"
                                    class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition">
                                {{ $replyingTo ? __('Post Reply') : __('Post Comment') }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-zinc-50 rounded-2xl p-6 mb-8 border border-zinc-200 text-center">
                    <p class="text-zinc-500 text-sm">
                        {{ __('Please') }}
                        <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-semibold hover:underline">{{ __('sign in') }}</a>
                        {{ __('to leave a comment') }}
                    </p>
                </div>
            @endauth

            {{-- Comment list --}}
            <div class="space-y-4">
                @foreach($article->comments as $comment)
                <div class="border border-zinc-200 rounded-2xl p-6 bg-white">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-red-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                {{ substr($comment->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-zinc-900">{{ $comment->user->name }}</p>
                                <p class="text-xs text-zinc-400">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @auth
                            @if($comment->user_id === auth()->id())
                                <div class="flex items-center gap-3">
                                    @if($editingCommentId === $comment->id)
                                        <button wire:click="cancelEdit" class="text-xs text-zinc-400 hover:text-zinc-600">{{ __('Cancel') }}</button>
                                    @else
                                        <button wire:click="editComment({{ $comment->id }})" class="text-xs text-zinc-400 hover:text-zinc-700 font-medium">{{ __('Edit') }}</button>
                                        <button @click="showDeleteConfirm = true; deleteCommentId = {{ $comment->id }}" class="text-xs text-red-500 hover:text-red-700 font-medium">{{ __('Delete') }}</button>
                                    @endif
                                </div>
                            @elseif($isAdmin)
                                <button @click="showDeleteConfirm = true; deleteCommentId = {{ $comment->id }}" class="text-xs text-red-500 hover:text-red-700 font-medium">{{ __('Delete') }}</button>
                            @endif
                        @endauth
                    </div>

                    @if($editingCommentId === $comment->id)
                        <div>
                            <textarea wire:model="editingCommentText" rows="3" maxlength="3000"
                                      class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-900 resize-none text-sm focus:outline-none focus:ring-2 focus:ring-red-500 mb-3"></textarea>
                            @error('editingCommentText') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            <button wire:click="updateComment"
                                    class="px-4 py-1.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">{{ __('Update') }}</button>
                        </div>
                    @else
                        <p class="text-zinc-700 text-sm leading-relaxed whitespace-pre-wrap mb-3">{{ $comment->comment }}</p>
                        @auth
                            <button wire:click="replyTo({{ $comment->id }})"
                                    class="text-xs text-red-600 hover:text-red-700 font-semibold">{{ __('↩ Reply') }}</button>
                        @endauth
                    @endif

                    {{-- Replies --}}
                    @if($comment->replies->count() > 0)
                    <div class="mt-4 space-y-3 pl-6 border-l-2 border-zinc-100">
                        @foreach($comment->replies as $reply)
                        <div class="bg-zinc-50 rounded-xl p-4 border border-zinc-100">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-red-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ substr($reply->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-zinc-900">{{ $reply->user->name }}</p>
                                        <p class="text-xs text-zinc-400">{{ $reply->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @auth
                                    @if($reply->user_id === auth()->id())
                                        <div class="flex items-center gap-3">
                                            @if($editingCommentId === $reply->id)
                                                <button wire:click="cancelEdit" class="text-xs text-zinc-400 hover:text-zinc-600">{{ __('Cancel') }}</button>
                                            @else
                                                <button wire:click="editComment({{ $reply->id }})" class="text-xs text-zinc-400 hover:text-zinc-700 font-medium">{{ __('Edit') }}</button>
                                                <button @click="showDeleteConfirm = true; deleteCommentId = {{ $reply->id }}" class="text-xs text-red-500 hover:text-red-700 font-medium">{{ __('Delete') }}</button>
                                            @endif
                                        </div>
                                    @elseif($isAdmin)
                                        <button @click="showDeleteConfirm = true; deleteCommentId = {{ $reply->id }}" class="text-xs text-red-500 hover:text-red-700 font-medium">{{ __('Delete') }}</button>
                                    @endif
                                @endauth
                            </div>
                            @if($editingCommentId === $reply->id)
                                <div>
                                    <textarea wire:model="editingCommentText" rows="3" maxlength="3000"
                                              class="w-full px-3 py-2 rounded-lg border border-zinc-200 bg-white text-zinc-900 resize-none text-xs focus:outline-none focus:ring-2 focus:ring-red-500 mb-2"></textarea>
                                    @error('editingCommentText') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    <button wire:click="updateComment"
                                            class="px-3 py-1 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">{{ __('Update') }}</button>
                                </div>
                            @else
                                <p class="text-zinc-600 text-xs leading-relaxed whitespace-pre-wrap">{{ $reply->comment }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Delete confirm modal --}}
    <div x-show="showDeleteConfirm" x-cloak
         class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4"
         @click.self="showDeleteConfirm = false">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl border border-zinc-100">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-zinc-900 text-center mb-2">{{ __('Delete Comment?') }}</h3>
            <p class="text-zinc-500 text-sm text-center mb-6">{{ __('This action cannot be undone.') }}</p>
            <div class="flex gap-3">
                <button @click="showDeleteConfirm = false"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-zinc-200 text-zinc-700 hover:bg-zinc-50 font-semibold text-sm transition">
                    {{ __('Cancel') }}
                </button>
                <button @click="$wire.deleteComment(deleteCommentId); showDeleteConfirm = false"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>
</div>