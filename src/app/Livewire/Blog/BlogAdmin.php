<?php

namespace App\Livewire\Blog;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class BlogAdmin extends Component
{
    use WithPagination;

    public $showEditor = false;
    public $editingArticleId = null;
    public $metatitle = '';
    public $metadesc = '';
    public $metakeywords = '';
    public $title = '';
    public $slug = '';
    public $article = '';
    public $published_at = null;
    public $editorContent = '';

    protected $rules = [
        'metatitle' => 'required|string|max:500',
        'metadesc' => 'required|string|max:1000',
        'metakeywords' => 'required|string|max:500',
        'title' => 'required|string|max:255',
        'article' => 'required|string',
    ];

    public function mount()
    {
        $this->editorContent = json_encode([
            'type' => 'doc',
            'content' => []
        ]);
    }

    public function updatedTitle()
    {
        $this->slug = Str::slug($this->title);
    }

    public function createNew()
    {
        $this->reset(['editingArticleId', 'metatitle', 'metadesc', 'metakeywords', 'title', 'slug', 'article', 'published_at']);
        $this->editorContent = json_encode([
            'type' => 'doc',
            'content' => []
        ]);
        $this->showEditor = true;
    }

    public function editArticle($id)
    {
        $article = Article::findOrFail($id);
        
        $this->editingArticleId = $article->id;
        $this->metatitle = $article->metatitle;
        $this->metadesc = $article->metadesc;
        $this->metakeywords = $article->metakeywords;
        $this->title = $article->title;
        $this->slug = $article->slug;
        $this->article = $article->article;
        $this->published_at = $article->published_at?->format('Y-m-d\TH:i');
        $this->editorContent = $article->article;
        $this->showEditor = true;
    }

    public function saveArticle()
    {
        $this->validate();

        $data = [
            'metatitle' => $this->metatitle,
            'metadesc' => $this->metadesc,
            'metakeywords' => $this->metakeywords,
            'title' => $this->title,
            'slug' => $this->slug,
            'article' => $this->article,
            'published_at' => $this->published_at ? date('Y-m-d H:i:s', strtotime($this->published_at)) : null,
        ];

        if ($this->editingArticleId) {
            Article::findOrFail($this->editingArticleId)->update($data);
        } else {
            Article::create($data);
        }

        $this->showEditor = false;
        $this->reset(['editingArticleId', 'metatitle', 'metadesc', 'metakeywords', 'title', 'slug', 'article', 'published_at']);
    }

    public function deleteArticle($id)
    {
        Article::findOrFail($id)->delete();
    }

    public function cancel()
    {
        $this->showEditor = false;
        $this->reset(['editingArticleId', 'metatitle', 'metadesc', 'metakeywords', 'title', 'slug', 'article', 'published_at']);
    }

    public function render()
    {
        $articles = Article::latest()->paginate(20);

        return view('liveblog::livewire.blog.blog-admin', [
            'articles' => $articles,
        ]);
    }

}
