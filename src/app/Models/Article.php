<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $table = 'data_articles';

    protected $fillable = [
        'metatitle',
        'metadesc',
        'metakeywords',
        'title',
        'slug',
        'article',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(ArticleComment::class, 'post_id')
            ->whereNull('reply_to')
            ->with('replies')
            ->latest();
    }

    public function allComments()
    {
        return $this->hasMany(ArticleComment::class, 'post_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc');
    }
}
