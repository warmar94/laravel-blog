<?php

namespace App\Livewire\Blog;

use App\Models\Article;
use App\Models\ArticleComment;
use Livewire\Component;

class BlogShow extends Component
{
    public Article $article;
    public $comment = '';
    public $replyingTo = null;
    public $editingCommentId = null;
    public $editingCommentText = '';

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
        
        $isAdmin = in_array(auth()->user()->email, config('liveblog.admin.emails', []));
        
        if ($comment->user_id !== auth()->id() && !$isAdmin) {
            return;
        }

        $comment->delete();
        $this->article->refresh();
    }

    public function deleteArticle()
    {
        $isAdmin = in_array(auth()->user()->email, config('liveblog.admin.emails', []));
        
        if (!$isAdmin) {
            return;
        }

        $this->article->delete();
        return redirect()->route('blog.home');
    }

    public function render()
    {
        return view('liveblog::livewire.blog.blog-show')->layoutData([
            'title' => $this->article->metatitle,
            'description' => $this->article->metadesc,
            'keywords' => $this->article->metakeywords,
        ]);
    }

}
