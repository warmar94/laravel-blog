<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Article;
use App\Models\Blog\ArticleComment;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.blog.blog')]
class BlogShow extends Component
{
    public Article $article;
    public $comment = '';
    public $replyingTo = null;
    public $editingCommentId = null;
    public $editingCommentText = '';

    /*
    |--------------------------------------------------------------------------
    | Admin check
    |--------------------------------------------------------------------------
    | Adjust the roles below to match your User model's role values.
    | Any user whose role matches one of these is treated as an admin
    | and can delete any comment or article from the public blog view.
    */
    private function isAdmin(): bool
    {
        return auth()->check()
            && in_array(auth()->user()->role, ['superadmin', 'admin', 'moderator']);
    }

    public function mount($slug)
    {
        $this->article = Article::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();
    }

    public function postComment()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'comment' => 'required|string|max:3000',
        ]);

        ArticleComment::create([
            'post_id' => $this->article->id,
            'user_id' => auth()->id(),
            'reply_to' => $this->replyingTo,
            'comment' => $this->comment,
        ]);

        $this->comment = '';
        $this->replyingTo = null;
        $this->article->refresh();
    }

    public function replyTo($commentId)
    {
        $this->replyingTo = $commentId;
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
    }

    public function editComment($commentId)
    {
        $comment = ArticleComment::findOrFail($commentId);

        if ($comment->user_id !== auth()->id()) {
            return;
        }

        $this->editingCommentId = $commentId;
        $this->editingCommentText = $comment->comment;
    }

    public function updateComment()
    {
        $comment = ArticleComment::findOrFail($this->editingCommentId);

        if ($comment->user_id !== auth()->id()) {
            return;
        }

        $this->validate([
            'editingCommentText' => 'required|string|max:3000',
        ]);

        $comment->update(['comment' => $this->editingCommentText]);

        $this->editingCommentId = null;
        $this->editingCommentText = '';
        $this->article->refresh();
    }

    public function cancelEdit()
    {
        $this->editingCommentId = null;
        $this->editingCommentText = '';
    }

    public function deleteComment($commentId)
    {
        $comment = ArticleComment::findOrFail($commentId);

        if ($comment->user_id !== auth()->id() && !$this->isAdmin()) {
            return;
        }

        $comment->delete();
        $this->article->refresh();
    }

    public function deleteArticle()
    {
        if (!$this->isAdmin()) {
            return;
        }

        $this->article->delete();
        return redirect()->route('blog.home');
    }

    public function render()
    {
        return view('livewire.blog.blog-show')->layoutData([
            'title'       => $this->article->metatitle ?: $this->article->title,
            'description' => $this->article->metadesc,
            'keywords'    => $this->article->metakeywords,
        ]);
    }
}