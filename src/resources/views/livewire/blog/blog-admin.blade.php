<div class="min-h-screen bg-gradient-to-b from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-950">
    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="flex justify-between items-center mb-12">
            <h1 class="text-4xl font-bold text-zinc-900 dark:text-white">Blog Admin</h1>
            <div class="flex gap-4">
                <a href="{{ route('blog.home') }}" wire:navigate class="px-6 py-3 rounded-full bg-zinc-600 hover:bg-zinc-700 text-white font-semibold">
                    View Blog
                </a>
                <button wire:click="createNew" class="px-6 py-3 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold">
                    + New Article
                </button>
            </div>
        </div>

        @if($showEditor)
            <div class="bg-white dark:bg-zinc-800 rounded-3xl shadow-2xl p-8 mb-12 border border-zinc-200 dark:border-zinc-700">
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-6">
                    {{ $editingArticleId ? 'Edit Article' : 'Create New Article' }}
                </h2>

                <form wire:submit.prevent="saveArticle" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Meta Title</label>
                        <input type="text" wire:model="metatitle" class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white">
                        @error('metatitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Meta Description</label>
                        <textarea wire:model="metadesc" rows="2" class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"></textarea>
                        @error('metadesc') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Meta Keywords</label>
                        <input type="text" wire:model="metakeywords" class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white">
                        @error('metakeywords') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Article Title</label>
                        <input type="text" wire:model.live="title" class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Slug</label>
                        <input type="text" wire:model="slug" class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Publish Date</label>
                        <input type="datetime-local" wire:model="published_at" class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Article Content</label>
                        <div id="richTextEditor" class="border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 min-h-[400px]"></div>
                        @error('article') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="px-8 py-3 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold">
                            {{ $editingArticleId ? 'Update' : 'Publish' }}
                        </button>
                        <button type="button" wire:click="cancel" class="px-8 py-3 rounded-full bg-zinc-300 dark:bg-zinc-600 hover:bg-zinc-400 dark:hover:bg-zinc-500 text-zinc-900 dark:text-white font-semibold">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-800 rounded-3xl shadow-2xl overflow-hidden border border-zinc-200 dark:border-zinc-700">
                <table class="w-full">
                    <thead class="bg-zinc-100 dark:bg-zinc-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-zinc-900 dark:text-white">Title</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-zinc-900 dark:text-white">Slug</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-zinc-900 dark:text-white">Published</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-zinc-900 dark:text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($articles as $article)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                <td class="px-6 py-4 text-zinc-900 dark:text-white">{{ $article->title }}</td>
                                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400 text-sm">{{ $article->slug }}</td>
                                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400 text-sm">
                                    {{ $article->published_at ? $article->published_at->format('Y-m-d H:i') : 'Draft' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('blog.show', $article->slug) }}" target="_blank" class="px-4 py-2 rounded-full bg-zinc-200 dark:bg-zinc-600 hover:bg-zinc-300 dark:hover:bg-zinc-500 text-zinc-900 dark:text-white text-sm font-semibold">
                                            View
                                        </a>
                                        <button wire:click="editArticle({{ $article->id }})" class="px-4 py-2 rounded-full bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold">
                                            Edit
                                        </button>
                                        <button wire:click="deleteArticle({{ $article->id }})" wire:confirm="Delete this article?" class="px-4 py-2 rounded-full bg-red-600 hover:bg-red-700 text-white text-sm font-semibold">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="p-6">
                    {{ $articles->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
let editor;
let editorContent = @json($editorContent);

function initRichTextEditor() {
    const editorEl = document.getElementById('richTextEditor');
    if (!editorEl) return;

    editorEl.innerHTML = `
        <div class="editor-toolbar" style="display: flex; gap: 0.5rem; padding: 0.75rem; border-bottom: 1px solid #e5e7eb; flex-wrap: wrap; background: #f9fafb;">
            <button type="button" onclick="formatText('bold')" class="px-3 py-1 rounded bg-white border border-zinc-300 hover:bg-zinc-100 font-bold">B</button>
            <button type="button" onclick="formatText('italic')" class="px-3 py-1 rounded bg-white border border-zinc-300 hover:bg-zinc-100 italic">I</button>
            <button type="button" onclick="formatText('underline')" class="px-3 py-1 rounded bg-white border border-zinc-300 hover:bg-zinc-100 underline">U</button>
            <button type="button" onclick="formatText('strikethrough')" class="px-3 py-1 rounded bg-white border border-zinc-300 hover:bg-zinc-100 line-through">S</button>
            <button type="button" onclick="insertHeading(1)" class="px-3 py-1 rounded bg-white border border-zinc-300 hover:bg-zinc-100">H1</button>
            <button type="button" onclick="insertHeading(2)" class="px-3 py-1 rounded bg-white border border-zinc-300 hover:bg-zinc-100">H2</button>
            <button type="button" onclick="insertHeading(3)" class="px-3 py-1 rounded bg-white border border-zinc-300 hover:bg-zinc-100">H3</button>
            <button type="button" onclick="formatText('insertUnorderedList')" class="px-3 py-1 rounded bg-white border border-zinc-300 hover:bg-zinc-100">• List</button>
            <button type="button" onclick="formatText('insertOrderedList')" class="px-3 py-1 rounded bg-white border border-zinc-300 hover:bg-zinc-100">1. List</button>
            <button type="button" onclick="insertLink()" class="px-3 py-1 rounded bg-white border border-zinc-300 hover:bg-zinc-100">🔗 Link</button>
            <input type="color" onchange="formatText('foreColor', this.value)" class="w-10 h-8 rounded border border-zinc-300 cursor-pointer" title="Text Color">
            <input type="color" onchange="formatText('backColor', this.value)" class="w-10 h-8 rounded border border-zinc-300 cursor-pointer" title="Highlight">
        </div>
        <div id="editorContent" contenteditable="true" style="min-height: 300px; padding: 1.5rem; outline: none; line-height: 1.6;"></div>
    `;

    loadContent();

    document.getElementById('editorContent').addEventListener('input', saveContent);
}

function loadContent() {
    const content = document.getElementById('editorContent');
    try {
        const json = typeof editorContent === 'string' ? JSON.parse(editorContent) : editorContent;
        content.innerHTML = renderJSON(json);
    } catch (e) {
        content.innerHTML = '';
    }
}

function renderJSON(json) {
    if (!json || !json.content) return '';
    return json.content.map(block => renderBlock(block)).join('');
}

function renderBlock(block) {
    switch (block.type) {
        case 'paragraph':
            return '<p>' + renderContent(block.content || []) + '</p>';
        case 'heading':
            const level = block.attrs?.level || 2;
            return `<h${level}>` + renderContent(block.content || []) + `</h${level}>`;
        case 'bulletList':
            return '<ul>' + renderContent(block.content || []) + '</ul>';
        case 'orderedList':
            return '<ol>' + renderContent(block.content || []) + '</ol>';
        case 'listItem':
            return '<li>' + renderContent(block.content || []) + '</li>';
        default:
            return '';
    }
}

function renderContent(content) {
    return content.map(item => {
        if (item.type === 'text') {
            let text = item.text || '';
            if (item.marks) {
                item.marks.forEach(mark => {
                    text = applyMark(text, mark);
                });
            }
            return text;
        } else {
            return renderBlock(item);
        }
    }).join('');
}

function applyMark(text, mark) {
    switch (mark.type) {
        case 'bold': return `<strong>${text}</strong>`;
        case 'italic': return `<em>${text}</em>`;
        case 'underline': return `<u>${text}</u>`;
        case 'strike': return `<s>${text}</s>`;
        case 'link': return `<a href="${mark.attrs?.href || '#'}">${text}</a>`;
        case 'textColor': return `<span style="color: ${mark.attrs?.color}">${text}</span>`;
        case 'highlight': return `<mark style="background-color: ${mark.attrs?.color}">${text}</mark>`;
        default: return text;
    }
}

function saveContent() {
    const content = document.getElementById('editorContent');
    const json = htmlToJSON(content.innerHTML);
    @this.set('article', JSON.stringify(json));
}

function htmlToJSON(html) {
    const temp = document.createElement('div');
    temp.innerHTML = html;
    
    const blocks = [];
    temp.childNodes.forEach(node => {
        const block = nodeToBlock(node);
        if (block) blocks.push(block);
    });
    
    return { type: 'doc', content: blocks };
}

function nodeToBlock(node) {
    if (node.nodeType === 3) {
        if (node.textContent.trim()) {
            return { type: 'paragraph', content: [{ type: 'text', text: node.textContent }] };
        }
        return null;
    }
    
    const tag = node.nodeName.toLowerCase();
    
    switch (tag) {
        case 'p':
            return { type: 'paragraph', content: nodeToContent(node) };
        case 'h1':
        case 'h2':
        case 'h3':
        case 'h4':
        case 'h5':
        case 'h6':
            return { type: 'heading', attrs: { level: parseInt(tag[1]) }, content: nodeToContent(node) };
        case 'ul':
            return { type: 'bulletList', content: Array.from(node.children).map(li => ({ type: 'listItem', content: nodeToContent(li) })) };
        case 'ol':
            return { type: 'orderedList', content: Array.from(node.children).map(li => ({ type: 'listItem', content: nodeToContent(li) })) };
        case 'br':
            return { type: 'hardBreak' };
        default:
            if (node.childNodes.length > 0) {
                return { type: 'paragraph', content: nodeToContent(node) };
            }
            return null;
    }
}

function nodeToContent(node) {
    const content = [];
    node.childNodes.forEach(child => {
        if (child.nodeType === 3) {
            if (child.textContent) {
                content.push({ type: 'text', text: child.textContent });
            }
        } else {
            const item = nodeToTextItem(child);
            if (item) content.push(item);
        }
    });
    return content.length > 0 ? content : [{ type: 'text', text: '' }];
}

function nodeToTextItem(node) {
    const marks = [];
    let text = node.textContent;
    
    const tag = node.nodeName.toLowerCase();
    
    if (tag === 'strong' || tag === 'b') marks.push({ type: 'bold' });
    if (tag === 'em' || tag === 'i') marks.push({ type: 'italic' });
    if (tag === 'u') marks.push({ type: 'underline' });
    if (tag === 's') marks.push({ type: 'strike' });
    if (tag === 'a') marks.push({ type: 'link', attrs: { href: node.href } });
    if (tag === 'span' && node.style.color) marks.push({ type: 'textColor', attrs: { color: node.style.color } });
    if (tag === 'mark') marks.push({ type: 'highlight', attrs: { color: node.style.backgroundColor || '#fef08a' } });
    
    if (node.childNodes.length === 1 && node.childNodes[0].nodeType === 3) {
        return { type: 'text', text: text, marks: marks.length > 0 ? marks : undefined };
    }
    
    const nested = nodeToContent(node);
    if (marks.length > 0 && nested.length > 0) {
        nested.forEach(item => {
            if (item.marks) {
                item.marks = [...marks, ...item.marks];
            } else {
                item.marks = marks;
            }
        });
    }
    return nested[0];
}

function formatText(command, value) {
    document.execCommand(command, false, value);
    setTimeout(saveContent, 10);
}

function insertHeading(level) {
    document.execCommand('formatBlock', false, `<h${level}>`);
    setTimeout(saveContent, 10);
}

function insertLink() {
    const url = prompt('Enter URL:');
    if (url) {
        document.execCommand('createLink', false, url);
        setTimeout(saveContent, 10);
    }
}

document.addEventListener('DOMContentLoaded', initRichTextEditor);
document.addEventListener('livewire:navigated', initRichTextEditor);

Livewire.on('editorContentUpdated', (content) => {
    editorContent = content;
    loadContent();
});
</script>
@endpush
