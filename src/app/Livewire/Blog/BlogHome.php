<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.blog.blog')]
class BlogHome extends Component
{
    use WithPagination;

    public string $search   = '';
    public string $category = '';

    public function updatingSearch():   void { $this->resetPage(); }
    public function updatingCategory(): void { $this->resetPage(); }

    public function render()
    {
        $articles = Article::published()
            ->when($this->search, fn($q) => $q->where(function($q) {
                $q->where('title',    'like', '%' . $this->search . '%')
                  ->orWhere('metadesc', 'like', '%' . $this->search . '%');
            }))
            ->when($this->category, fn($q) => $q->inCategory($this->category))
            ->paginate(config('blog.pagination.per_page', 10));

        $categories = config('blog.features.categories', true)
            ? collect(config('blog.categories', []))->filter()->values()
            : collect();

        return view('livewire.blog.blog-home', [
            'articles'   => $articles,
            'categories' => $categories,
        ])->layoutData([
            'title'       => 'Blog',
            'description' => '',
            'keywords'    => '',
        ]);
    }
}