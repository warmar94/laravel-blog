<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

#[Layout('layouts.blog.blog')]
class BlogAdmin extends Component
{
    use WithPagination, WithFileUploads;

    // ─── Editor state ────────────────────────────────────────────────────────
    public bool   $showEditor       = false;
    public ?int   $editingArticleId = null;
    public string $metatitle        = '';
    public string $metadesc         = '';
    public string $metakeywords     = '';
    public string $title            = '';
    public string $slug             = '';
    public string $article          = '';
    public ?string $published_at    = null;
    public string  $category         = '';
    public string $editorContent    = '';

    // ─── Image manager state ─────────────────────────────────────────────────
    public bool   $showImageManager  = false;
    public string $currentFolder     = '';          // relative path inside blog-media/
    public string $newFolderName     = '';
    public string $renamingItem      = '';          // path being renamed
    public string $renameValue       = '';
    public array  $uploadedImages    = [];          // Livewire temp uploads
    public array  $pendingImages     = [];          // [{path, alt, name}] for pre-confirm panel
    public string $movingItem        = '';          // path being moved
    public string $moveTarget        = '';

    // ─── Validation ──────────────────────────────────────────────────────────
    protected $rules = [
        'metatitle'    => 'required|string|max:500',
        'metadesc'     => 'required|string|max:1000',
        'metakeywords' => 'required|string|max:500',
        'title'        => 'required|string|max:255',
        'article'      => 'required|string',
    ];

    // ─── Base storage disk & root folder ─────────────────────────────────────
    private const DISK   = 'public';
    private const ROOT   = 'blog-media';

    // =========================================================================
    // Lifecycle
    // =========================================================================

    public function mount(): void
    {
        $this->editorContent = json_encode(['type' => 'doc', 'content' => []]);
        // Ensure base folder exists
        Storage::disk(self::DISK)->makeDirectory(self::ROOT);
    }

    // =========================================================================
    // Article CRUD
    // =========================================================================

    public function updatedTitle(): void
    {
        $this->slug = Str::slug($this->title);
    }

    public function createNew(): void
    {
        $this->resetArticleFields();
        $this->editorContent = json_encode(['type' => 'doc', 'content' => []]);
        $this->showEditor    = true;
    }

    public function editArticle(int $id): void
    {
        $article = Article::findOrFail($id);

        $this->editingArticleId = $article->id;
        $this->metatitle        = $article->metatitle;
        $this->metadesc         = $article->metadesc;
        $this->metakeywords     = $article->metakeywords;
        $this->title            = $article->title;
        $this->slug             = $article->slug;
        $this->article          = $article->article;
        $this->category        = $article->category ?? '';
        $this->published_at     = $article->published_at?->format('Y-m-d\TH:i');
        $this->editorContent    = $article->article;
        $this->showEditor       = true;
        $this->dispatch('editorContentUpdated', $article->article);
    }

    public function saveArticle(): void
    {
        $this->validate();

        $data = [
            'metatitle'    => $this->metatitle,
            'metadesc'     => $this->metadesc,
            'metakeywords' => $this->metakeywords,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'article'      => $this->article,
            'category'     => $this->category ?: null,
            'published_at' => $this->published_at
                ? date('Y-m-d H:i:s', strtotime($this->published_at))
                : null,
        ];

        if ($this->editingArticleId) {
            Article::findOrFail($this->editingArticleId)->update($data);
        } else {
            Article::create($data);
        }

        $this->showEditor = false;
        $this->resetArticleFields();
        $this->redirect(route('blog.admin'), navigate: true);
    }

    /**
     * Called by JS save button — receives the parsed article JSON directly
     * so editingArticleId is never lost between a set() round-trip and saveArticle().
     */
    public function saveWithContent(string $articleJson): void
    {
        $this->article = $articleJson;
        $this->saveArticle();
    }

    public function deleteArticle(int $id): void
    {
        Article::findOrFail($id)->delete();
    }

    public function cancel(): void
    {
        $this->showEditor = false;
        $this->resetArticleFields();
    }

    // =========================================================================
    // Image Manager — navigation
    // =========================================================================

    public function openImageManager(): void
    {
        $this->showImageManager = true;
        $this->currentFolder    = '';
    }

    public function closeImageManager(): void
    {
        $this->showImageManager  = false;
        $this->pendingImages     = [];
        $this->newFolderName     = '';
        $this->renamingItem      = '';
        $this->renameValue       = '';
    }

    public function navigateFolder(string $folder): void
    {
        // Sanitize: must stay within ROOT
        $this->currentFolder = $this->sanitizePath($folder);
    }

    public function navigateUp(): void
    {
        $parts = explode('/', $this->currentFolder);
        array_pop($parts);
        $this->currentFolder = implode('/', array_filter($parts));
    }

    // =========================================================================
    // Image Manager — folder operations
    // =========================================================================

    public function createFolder(): void
    {
        $name = Str::slug($this->newFolderName);
        if (!$name) return;

        $path = $this->fullPath($name);
        Storage::disk(self::DISK)->makeDirectory($path);
        $this->newFolderName = '';
    }

    public function startRename(string $itemPath): void
    {
        $this->renamingItem = $itemPath;
        $this->renameValue  = basename($itemPath);
    }

    public function confirmRename(): void
    {
        if (!$this->renamingItem || !$this->renameValue) return;

        $oldPath = self::ROOT . '/' . $this->renamingItem;
        $dir     = dirname($this->renamingItem);
        $newRel  = ($dir === '.' ? '' : $dir . '/') . $this->renameValue;
        $newPath = self::ROOT . '/' . $newRel;

        if (Storage::disk(self::DISK)->exists($oldPath)) {
            // Laravel's Storage doesn't have a direct rename, use move
            $this->moveStorageItem($oldPath, $newPath);
        }

        $this->renamingItem = '';
        $this->renameValue  = '';
    }

    public function deleteItem(string $itemPath): void
    {
        $full = self::ROOT . '/' . $itemPath;
        $disk = Storage::disk(self::DISK);

        if ($disk->directoryExists($full)) {
            $disk->deleteDirectory($full);
        } elseif ($disk->exists($full)) {
            $disk->delete($full);
        }
    }

    public function startMove(string $itemPath): void
    {
        $this->movingItem = $itemPath;
        $this->moveTarget = '';
    }

    public function confirmMove(): void
    {
        if (!$this->movingItem) return;

        $oldPath   = self::ROOT . '/' . $this->movingItem;
        $targetDir = $this->moveTarget
            ? self::ROOT . '/' . $this->moveTarget
            : self::ROOT;
        $newPath   = $targetDir . '/' . basename($this->movingItem);

        $this->moveStorageItem($oldPath, $newPath);
        $this->movingItem = '';
        $this->moveTarget = '';
    }

    public function copyImage(string $imagePath): void
    {
        $source = self::ROOT . '/' . $imagePath;
        $disk   = Storage::disk(self::DISK);

        if (!$disk->exists($source)) return;

        $info    = pathinfo($imagePath);
        $dir     = $info['dirname'] === '.' ? '' : $info['dirname'] . '/';
        $newName = $info['filename'] . '_copy.' . ($info['extension'] ?? 'jpg');
        $dest    = self::ROOT . '/' . $dir . $newName;

        $disk->copy($source, $dest);
    }

    // =========================================================================
    // Image Manager — uploads
    // =========================================================================

    public function updatedUploadedImages(): void
    {
        \Log::info('updatedUploadedImages fired', ['count' => count($this->uploadedImages)]);

        foreach ($this->uploadedImages as $upload) {
            $ext      = strtolower($upload->getClientOriginalExtension());
            $name     = Str::slug(pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $ext;
            $dir      = self::ROOT . ($this->currentFolder ? '/' . $this->currentFolder : '');
            $stored   = $upload->storeAs($dir, $name, self::DISK);
            // Use relative URL — avoids APP_URL mismatch (localhost vs 127.0.0.1 etc)
            $url      = '/storage/' . $stored;
            $relative = $this->currentFolder ? $this->currentFolder . '/' . $name : $name;

            \Log::info('Image stored', ['stored' => $stored, 'url' => $url]);

            $this->pendingImages[] = [
                'path' => $relative,
                'url'  => $url,
                'name' => $name,
                'alt'  => '',
            ];
        }

        \Log::info('pendingImages after upload', ['pending' => $this->pendingImages]);
        $this->uploadedImages = [];
    }

    public function confirmInsertImages(): void
    {
        \Log::info('confirmInsertImages fired', ['pending' => $this->pendingImages]);
        $images = $this->pendingImages;
        $this->pendingImages    = [];
        $this->showImageManager = false;
        $this->dispatch('insertImages', $images);
        \Log::info('insertImages dispatched', ['images' => $images]);
    }

    public function updatePendingAlt(int $index, string $alt): void
    {
        if (isset($this->pendingImages[$index])) {
            $this->pendingImages[$index]['alt'] = $alt;
        }
    }

    public function reorderPending(array $order): void
    {
        // $order = new ordered list of indices
        $reordered = [];
        foreach ($order as $i) {
            if (isset($this->pendingImages[$i])) {
                $reordered[] = $this->pendingImages[$i];
            }
        }
        $this->pendingImages = $reordered;
    }

    public function removePending(int $index): void
    {
        array_splice($this->pendingImages, $index, 1);
        $this->pendingImages = array_values($this->pendingImages);
    }

    /**
     * Add an existing image to the pending list (click-to-select in manager).
     */
    public function addToPending(string $url, string $rel, string $name): void
    {
        \Log::info('addToPending called', ['url' => $url, 'rel' => $rel, 'name' => $name]);
        $this->pendingImages[] = ['url' => $url, 'path' => $rel, 'name' => $name, 'alt' => ''];
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * Get directory listing for current folder.
     */
    public function getFolderContents(): array
    {
        $disk    = Storage::disk(self::DISK);
        $absPath = self::ROOT . ($this->currentFolder ? '/' . $this->currentFolder : '');

        $folders = collect($disk->directories($absPath))
            ->map(fn($f) => [
                'type'     => 'folder',
                'name'     => basename($f),
                'rel'      => $this->currentFolder
                    ? $this->currentFolder . '/' . basename($f)
                    : basename($f),
            ])
            ->values()
            ->toArray();

        $images = collect($disk->files($absPath))
            ->filter(fn($f) => in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']))
            ->map(fn($f) => [
                'type' => 'image',
                'name' => basename($f),
                'url'  => '/storage/' . $f,
                'rel'  => $this->currentFolder
                    ? $this->currentFolder . '/' . basename($f)
                    : basename($f),
            ])
            ->values()
            ->toArray();

        return ['folders' => $folders, 'images' => $images];
    }

    public function getAllFolders(): array
    {
        $disk = Storage::disk(self::DISK);
        return collect($disk->allDirectories(self::ROOT))
            ->map(fn($d) => Str::after($d, self::ROOT . '/'))
            ->values()
            ->toArray();
    }

    // =========================================================================
    // Private helpers
    // =========================================================================

    private function fullPath(string $relative): string
    {
        return self::ROOT . ($this->currentFolder ? '/' . $this->currentFolder : '') . '/' . $relative;
    }

    private function sanitizePath(string $path): string
    {
        // Strip any ../ traversal attempts
        $parts = array_filter(explode('/', $path), fn($p) => $p !== '..' && $p !== '.');
        return implode('/', $parts);
    }

    private function moveStorageItem(string $from, string $to): void
    {
        $disk = Storage::disk(self::DISK);
        if ($disk->directoryExists($from)) {
            // Move all files recursively
            foreach ($disk->allFiles($from) as $file) {
                $rel  = Str::after($file, $from . '/');
                $disk->copy($file, $to . '/' . $rel);
                $disk->delete($file);
            }
            $disk->deleteDirectory($from);
        } elseif ($disk->exists($from)) {
            $disk->move($from, $to);
        }
    }

    private function resetArticleFields(): void
    {
        $this->reset([
            'editingArticleId', 'metatitle', 'metadesc', 'metakeywords',
            'title', 'slug', 'article', 'published_at', 'category',
        ]);
    }

    // =========================================================================
    // Render
    // =========================================================================

    public function render()
    {
        $articles = Article::latest()->paginate(20);
        $folderContents = $this->showEditor ? $this->getFolderContents() : ['folders' => [], 'images' => []];
        $allFolders     = $this->showEditor ? $this->getAllFolders() : [];

        return view('livewire.blog.blog-admin', [
            'articles'       => $articles,
            'folderContents' => $folderContents,
            'allFolders'     => $allFolders,
        ])->layoutData([
            'title'       => 'Blog Admin',
            'description' => '',
            'keywords'    => '',
        ]);
    }
}