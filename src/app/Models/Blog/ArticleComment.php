<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    protected $table = 'data_articles_comments';

    protected $fillable = [
        'post_id',
        'user_id',
        'reply_to',
        'comment',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function parent()
    {
        return $this->belongsTo(ArticleComment::class, 'reply_to');
    }

    public function replies()
    {
        return $this->hasMany(ArticleComment::class, 'reply_to')
            ->with('replies', 'user')
            ->latest();
    }
}
