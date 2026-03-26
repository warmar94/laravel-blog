@php
    $isAdmin = auth()->check() && in_array(auth()->user()->email, config('liveblog.admin.emails', []));
    $indent = $level * 32;
@endphp

<div class="bg-zinc-50 dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700" style="margin-left: {{ $indent }}px;">
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white font-bold mr-3">
                {{ substr($comment->user->name, 0, 1) }}
            </div>
            <div>
                <p class="font-semibold text-zinc-800 dark:text-white">{{ $comment->user->name }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $comment->created_at->diffForHumans() }}</p>
            </div>
        </div>

        @auth
            @if($comment->user_id === auth()->id())
                <div class="flex gap-2">
                    @if($editingCommentId === $comment->id)
                        <button wire:click="cancelEdit" class="text-zinc-600 dark:text-zinc-400 hover:underline text-sm">Cancel</button>
                    @else
                        <button wire:click="editComment({{ $comment->id }})" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm">Edit</button>
                        <button @click="showDeleteConfirm = true; deleteCommentId = {{ $comment->id }}" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                    @endif
                </div>
            @elseif($isAdmin)
                <button @click="showDeleteConfirm = true; deleteCommentId = {{ $comment->id }}" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
            @endif
        @endauth
    </div>

    @if($editingCommentId === $comment->id)
        <div>
            <textarea wire:model="editingCommentText" rows="4" maxlength="3000" class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white resize-none mb-3"></textarea>
            @error('editingCommentText') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            <button wire:click="updateComment" class="px-4 py-2 rounded-full bg-red-600 hover:bg-red-700 text-white text-sm font-semibold">Update</button>
        </div>
    @else
        <p class="text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap mb-3">{{ $comment->comment }}</p>
        @auth
            <button wire:click="replyTo({{ $comment->id }})" class="text-sm text-red-600 dark:text-red-400 hover:underline font-semibold">Reply</button>
        @endauth
    @endif

    @if($comment->replies->count() > 0 && $level < 2)
        <div class="mt-4 space-y-4">
            @foreach($comment->replies as $reply)
                @include('liveblog::partials.comment', ['comment' => $reply, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
