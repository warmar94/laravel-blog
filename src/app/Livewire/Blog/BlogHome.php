<?php

namespace App\Livewire\Blog;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

class BlogHome extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $articles = Article::published()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('metadesc', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);

        return view('liveblog::livewire.blog.blog-home', [
            'articles' => $articles,
        ])->layoutData([
            'title' => 'Blog',
            'description' => '',
            'keywords' => '',
        ]);
    }

}
