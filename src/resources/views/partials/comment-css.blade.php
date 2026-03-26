@php
    $isAdmin = auth()->check() && in_array(auth()->user()->email, config('liveblog.admin.emails', []));
    $indent = $level * 32;
@endphp

<div class="comment" style="margin-left: {{ $indent }}px;">
    <div class="comment-header">
        <div class="comment-user">
            <div class="comment-avatar">{{ substr($comment->user->name, 0, 1) }}</div>
            <div class="comment-user-info">
                <p>{{ $comment->user->name }}</p>
                <p>{{ $comment->created_at->diffForHumans() }}</p>
            </div>
        </div>

        @auth
            @if($comment->user_id === auth()->id())
                <div class="comment-actions">
                    @if($editingCommentId === $comment->id)
                        <button wire:click="cancelEdit">Cancel</button>
                    @else
                        <button wire:click="editComment({{ $comment->id }})" class="comment-edit">Edit</button>
                        <button onclick="showDeleteModal({{ $comment->id }})" class="comment-delete">Delete</button>
                    @endif
                </div>
            @elseif($isAdmin)
                <button onclick="showDeleteModal({{ $comment->id }})" class="comment-delete">Delete</button>
            @endif
        @endauth
    </div>

    @if($editingCommentId === $comment->id)
        <div>
            <textarea wire:model="editingCommentText" rows="4" maxlength="3000" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; resize: none; margin-bottom: 0.75rem; font-family: inherit;"></textarea>
            @error('editingCommentText') <span style="color: #dc2626; font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">{{ $message }}</span> @enderror
            <button wire:click="updateComment" style="padding: 0.5rem 1rem; border-radius: 1.5rem; background: #dc2626; color: white; border: none; font-weight: 600; cursor: pointer;">Update</button>
        </div>
    @else
        <p class="comment-text">{{ $comment->comment }}</p>
        @auth
            <button wire:click="replyTo({{ $comment->id }})" class="comment-reply-btn">Reply</button>
        @endauth
    @endif

    @if($comment->replies->count() > 0 && $level < 2)
        <div class="comment-replies">
            @foreach($comment->replies as $reply)
                @include('liveblog::partials.comment-css', ['comment' => $reply, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
