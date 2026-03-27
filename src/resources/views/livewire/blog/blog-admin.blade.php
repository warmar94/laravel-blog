{{-- blog-admin.blade.php --}}
<div class="min-h-screen bg-white">

    {{-- Header --}}
    <div class="border-b border-zinc-200 bg-white sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-xs font-semibold border border-red-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                    Admin
                </span>
                <h1 class="text-lg font-bold text-zinc-900 tracking-tight">Laravel Blog</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('blog.home') }}" wire:navigate
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-zinc-200 text-zinc-600 hover:bg-zinc-50 text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View Blog
                </a>
                <button wire:click="createNew"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Article
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">

        @if($showEditor)
        <div x-data="blogEditor({{ Js::from($editorContent) }})"
             @insert-images.window="console.log('insert-images event fired', $event.detail); insertImages($event.detail)"
             class="space-y-5">

            {{-- Meta fields --}}
            <div class="bg-white rounded-2xl border border-zinc-200 p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-3 flex items-center gap-2 pb-4 border-b border-zinc-100 mb-1">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-sm font-semibold text-zinc-700">Article Details</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Meta Title</label>
                    <input type="text" wire:model.lazy="metatitle" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent focus:bg-white transition">
                    @error('metatitle') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Meta Description</label>
                    <input type="text" wire:model.lazy="metadesc" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent focus:bg-white transition">
                    @error('metadesc') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Meta Keywords</label>
                    <input type="text" wire:model.lazy="metakeywords" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent focus:bg-white transition">
                    @error('metakeywords') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Article Title</label>
                    <input type="text" wire:model.live="title" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent focus:bg-white transition">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Slug</label>
                    <input type="text" wire:model.lazy="slug" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Publish Date</label>
                    <input type="datetime-local" wire:model.lazy="published_at" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent focus:bg-white transition">
                </div>
                @if(config('blog.features.categories', true) && count(config('blog.categories', [])) > 0)
                <div>
                    <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">Category</label>
                    <select wire:model="category" class="w-full px-3 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-900 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent focus:bg-white transition">
                        <option value="">— No Category —</option>
                        @foreach(config('blog.categories', []) as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            {{-- 3-column editor --}}
            <div class="flex gap-4 items-start">

                {{-- LEFT: Tools --}}
                <div class="w-14 flex-shrink-0">
                    <div class="sticky top-20 bg-white rounded-2xl border border-zinc-200 p-2 flex flex-col gap-1.5 shadow-sm">

                        {{-- Font size --}}
                        <div class="relative">
                            <button @mousedown.prevent="openDropdown = openDropdown === 'fontSize' ? null : 'fontSize'"
                                    title="Font Size"
                                    class="w-full flex items-center justify-center h-9 rounded-xl hover:bg-zinc-50 text-zinc-500 text-xs font-bold transition border border-transparent hover:border-zinc-200">
                                <span x-text="currentFontSize === 'md' ? 'Aa' : currentFontSize.toUpperCase()"></span>
                            </button>
                            <div x-show="openDropdown === 'fontSize'" x-cloak @mousedown.away="openDropdown = null"
                                 class="absolute left-full ml-2 top-0 bg-white border border-zinc-200 rounded-xl shadow-lg p-2 z-50 flex flex-col gap-1 min-w-[140px]">
                                <button @mousedown.prevent="applyFontSize('sm')" class="text-left px-3 py-1.5 rounded-lg hover:bg-red-50 hover:text-red-600 text-zinc-600 transition" style="font-size:0.75em">S — Small</button>
                                <button @mousedown.prevent="applyFontSize('md')" class="text-left px-3 py-1.5 rounded-lg hover:bg-red-50 hover:text-red-600 text-zinc-600 transition" style="font-size:1em">M — Medium</button>
                                <button @mousedown.prevent="applyFontSize('lg')" class="text-left px-3 py-1.5 rounded-lg hover:bg-red-50 hover:text-red-600 text-zinc-600 transition" style="font-size:1.375em">L — Large</button>
                                <button @mousedown.prevent="applyFontSize('xl')" class="text-left px-3 py-1.5 rounded-lg hover:bg-red-50 hover:text-red-600 text-zinc-600 transition" style="font-size:2em">XL</button>
                            </div>
                        </div>

                        {{-- Bold --}}
                        <button @mousedown.prevent="applyMark('bold')" title="Bold"
                                :class="activeMarks.bold ? 'bg-red-50 text-red-600 border-red-200' : 'text-zinc-500 hover:bg-zinc-50 border-transparent hover:border-zinc-200'"
                                class="w-full h-9 rounded-xl font-bold text-sm transition border">B</button>

                        {{-- Italic --}}
                        <button @mousedown.prevent="applyMark('italic')" title="Italic"
                                :class="activeMarks.italic ? 'bg-red-50 text-red-600 border-red-200' : 'text-zinc-500 hover:bg-zinc-50 border-transparent hover:border-zinc-200'"
                                class="w-full h-9 rounded-xl italic text-sm transition border">I</button>

                        {{-- Underline --}}
                        <button @mousedown.prevent="applyMark('underline')" title="Underline"
                                :class="activeMarks.underline ? 'bg-red-50 text-red-600 border-red-200' : 'text-zinc-500 hover:bg-zinc-50 border-transparent hover:border-zinc-200'"
                                class="w-full h-9 rounded-xl underline text-sm transition border">U</button>

                        {{-- Strike --}}
                        <button @mousedown.prevent="applyMark('strike')" title="Strikethrough"
                                :class="activeMarks.strike ? 'bg-red-50 text-red-600 border-red-200' : 'text-zinc-500 hover:bg-zinc-50 border-transparent hover:border-zinc-200'"
                                class="w-full h-9 rounded-xl line-through text-sm transition border">S</button>

                        {{-- Highlight --}}
                        <div class="relative">
                            <button @mousedown.prevent="applyMark('highlight')" title="Apply Highlight"
                                    class="w-full h-7 rounded-t-xl text-xs font-bold text-zinc-800 transition hover:opacity-80 border border-b-0 border-zinc-200"
                                    :style="`background-color: ${highlightColor}`">HL</button>
                            <button @mousedown.prevent="openDropdown = openDropdown === 'hlColor' ? null : 'hlColor'"
                                    title="Pick Color"
                                    class="w-full h-2 rounded-b-xl transition hover:opacity-70 border border-t-0 border-zinc-200"
                                    :style="`background-color: ${highlightColor}`"></button>
                            <div x-show="openDropdown === 'hlColor'" x-cloak @mousedown.away="openDropdown = null"
                                 class="absolute left-full ml-2 top-0 bg-white border border-zinc-200 rounded-xl shadow-lg p-3 z-50 flex flex-col gap-2 w-48">
                                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Highlight Color</p>
                                <input type="color" :value="highlightColor" @input="highlightColor = $event.target.value"
                                       class="w-full h-10 rounded-lg cursor-pointer border border-zinc-200 bg-transparent p-0.5">
                                <input type="text" :value="highlightColor"
                                       @input="if (/^#[0-9a-fA-F]{3,6}$/.test($event.target.value)) highlightColor = $event.target.value"
                                       @keydown.enter.prevent="openDropdown = null"
                                       placeholder="#fef08a" maxlength="7"
                                       class="w-full px-2 py-1.5 text-xs rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-800 font-mono focus:outline-none focus:ring-2 focus:ring-red-500">
                                <button @mousedown.prevent="openDropdown = null"
                                        class="w-full py-1.5 rounded-lg text-xs font-semibold text-white transition"
                                        :style="`background-color: ${highlightColor}`">Use this color</button>
                            </div>
                        </div>

                        <div class="h-px bg-zinc-100 my-0.5"></div>

                        {{-- Lists --}}
                        <button @mousedown.prevent="insertBlock('bulletList')" title="Bullet List"
                                class="w-full h-9 rounded-xl hover:bg-zinc-50 text-zinc-500 text-base transition border border-transparent hover:border-zinc-200">•≡</button>
                        <button @mousedown.prevent="insertBlock('orderedList')" title="Ordered List"
                                class="w-full h-9 rounded-xl hover:bg-zinc-50 text-zinc-500 text-xs transition border border-transparent hover:border-zinc-200">1≡</button>

                        <div class="h-px bg-zinc-100 my-0.5"></div>

                        {{-- Image --}}
                        <button @mousedown.prevent="$wire.openImageManager()" title="Insert Image"
                                class="w-full h-9 rounded-xl hover:bg-zinc-50 text-zinc-500 text-base transition border border-transparent hover:border-zinc-200">🖼</button>

                        {{-- New block --}}
                        <button @mousedown.prevent="insertBlock('paragraph')" title="New Block"
                                class="w-full h-9 rounded-xl hover:bg-zinc-50 text-zinc-500 text-xs transition border border-transparent hover:border-zinc-200">+¶</button>

                        {{-- Link --}}
                        <button @mousedown.prevent="insertLink()" title="Link"
                                class="w-full h-9 rounded-xl hover:bg-zinc-50 text-zinc-500 text-sm transition border border-transparent hover:border-zinc-200">🔗</button>
                    </div>
                </div>

                {{-- CENTER: Canvas --}}
                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-sm" wire:ignore>
                        <div class="px-4 py-2.5 border-b border-zinc-100 flex items-center gap-2 text-xs text-zinc-400 bg-zinc-50 select-none">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Live Editor — click to edit
                        </div>
                        <div id="blogEditorCanvas"
                             x-ref="canvas"
                             @mouseup="updateActiveMarks()"
                             @keyup="updateActiveMarks()"
                             @keydown.enter.prevent="handleEnter($event)"
                             @keydown.shift.enter.prevent="handleEnter($event)"
                             @input="handleInput($event)"
                             @click="handleCanvasClick($event)"
                             class="min-h-[500px] p-8 cursor-text text-zinc-800"
                             style="outline:none; line-height:1.8; font-size:15px">
                        </div>
                    </div>

                    @error('article')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror

                    <div class="flex gap-3 mt-4">
                        <button @mousedown.prevent="saveToLivewire()"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition shadow-sm shadow-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $editingArticleId ? 'Update Article' : 'Publish Article' }}
                        </button>
                        <button wire:click="cancel"
                                class="px-6 py-2.5 rounded-xl border border-zinc-200 text-zinc-600 hover:bg-zinc-50 font-semibold text-sm transition">
                            Cancel
                        </button>
                    </div>
                </div>

                {{-- RIGHT: Inserted images --}}
                <div class="w-56 flex-shrink-0">
                    <div class="sticky top-20 bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-sm">
                        <div class="px-3 py-2.5 border-b border-zinc-100 bg-zinc-50 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Images</p>
                        </div>
                        <div class="p-2 space-y-3 max-h-[70vh] overflow-y-auto">
                            <template x-if="insertedImages.length === 0">
                                <div class="text-center py-6">
                                    <div class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-4 h-4 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <p class="text-xs text-zinc-400">No images yet</p>
                                </div>
                            </template>
                            <template x-for="(img, idx) in insertedImages" :key="idx">
                                <div class="border border-zinc-100 rounded-xl overflow-hidden bg-zinc-50">
                                    <img :src="img.src" :alt="img.alt" class="w-full h-20 object-cover bg-zinc-200">
                                    <div class="p-2 space-y-2">
                                        <input :value="img.alt" @input="updateImageAltInDoc(idx, $event.target.value)"
                                               placeholder="Alt text…"
                                               class="w-full text-xs px-2 py-1.5 rounded-lg border border-zinc-200 bg-white text-zinc-700 focus:outline-none focus:ring-1 focus:ring-red-500">

                                        {{-- Alignment --}}
                                        <div class="flex gap-1">
                                            <button @mousedown.prevent="updateImageAlign(idx, 'left')"
                                                    :class="img.align === 'left' ? 'bg-red-500 text-white border-red-500' : 'bg-white text-zinc-400 border-zinc-200 hover:border-zinc-300'"
                                                    class="flex-1 text-xs py-1 rounded-lg border transition" title="Left">⬛◻◻</button>
                                            <button @mousedown.prevent="updateImageAlign(idx, 'center')"
                                                    :class="(img.align === 'center' || !img.align) ? 'bg-red-500 text-white border-red-500' : 'bg-white text-zinc-400 border-zinc-200 hover:border-zinc-300'"
                                                    class="flex-1 text-xs py-1 rounded-lg border transition" title="Center">◻⬛◻</button>
                                            <button @mousedown.prevent="updateImageAlign(idx, 'right')"
                                                    :class="img.align === 'right' ? 'bg-red-500 text-white border-red-500' : 'bg-white text-zinc-400 border-zinc-200 hover:border-zinc-300'"
                                                    class="flex-1 text-xs py-1 rounded-lg border transition" title="Right">◻◻⬛</button>
                                        </div>

                                        {{-- Size --}}
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <span class="text-xs text-zinc-400">Size</span>
                                                <span class="text-xs font-mono text-zinc-600" x-text="(img.width ?? 100) + '%'"></span>
                                            </div>
                                            <input type="range" min="10" max="100" step="5"
                                                   :value="img.width ?? 100"
                                                   @input="updateImageWidth(idx, parseInt($event.target.value))"
                                                   class="w-full h-1.5 rounded-full appearance-none bg-zinc-200 accent-red-500 cursor-pointer">
                                        </div>

                                        <button @mousedown.prevent="removeImageFromDoc(idx)"
                                                class="w-full text-xs text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg py-1 transition font-medium">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>{{-- /3-col --}}
        </div>{{-- /x-data --}}

        {{-- IMAGE MANAGER MODAL --}}
        @if($showImageManager)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4"
             @keydown.escape.window="$wire.closeImageManager()">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col border border-zinc-200">

                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 flex-shrink-0">
                    <div>
                        <h2 class="text-base font-bold text-zinc-900">Image Manager</h2>
                        <p class="text-xs text-zinc-400 mt-0.5 font-mono">/blog-media{{ $currentFolder ? '/'.$currentFolder : '' }}</p>
                    </div>
                    <button wire:click="closeImageManager" class="w-8 h-8 rounded-xl hover:bg-zinc-100 flex items-center justify-center text-zinc-400 hover:text-zinc-600 transition text-lg font-light">×</button>
                </div>

                <div class="flex flex-1 overflow-hidden">
                    {{-- Folder sidebar --}}
                    <div class="w-48 border-r border-zinc-100 flex-shrink-0 overflow-y-auto p-3 space-y-1 bg-zinc-50">
                        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2 px-2">Folders</p>
                        <button wire:click="navigateFolder('')"
                                class="w-full text-left px-2.5 py-1.5 rounded-lg text-sm truncate transition font-medium {{ $currentFolder === '' ? 'bg-red-50 text-red-600' : 'text-zinc-600 hover:bg-white hover:text-zinc-900' }}">
                            / root
                        </button>
                        @foreach($allFolders as $folder)
                        <button wire:click="navigateFolder('{{ $folder }}')"
                                class="w-full text-left px-2.5 py-1.5 rounded-lg text-sm truncate transition {{ $currentFolder === $folder ? 'bg-red-50 text-red-600 font-medium' : 'text-zinc-600 hover:bg-white hover:text-zinc-900' }}">
                            📁 {{ $folder }}
                        </button>
                        @endforeach
                        <div class="pt-3 border-t border-zinc-200 mt-2 space-y-1.5">
                            <input wire:model.lazy="newFolderName" placeholder="New folder name"
                                   class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-zinc-200 bg-white text-zinc-800 focus:outline-none focus:ring-1 focus:ring-red-500">
                            <button wire:click="createFolder"
                                    class="w-full px-2.5 py-1.5 text-xs bg-zinc-100 hover:bg-zinc-200 rounded-lg text-zinc-600 transition font-medium">
                                + Create Folder
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 flex flex-col overflow-hidden">
                        <div class="flex items-center gap-3 px-4 py-3 border-b border-zinc-100 bg-white flex-shrink-0">
                            @if($currentFolder)
                            <button wire:click="navigateUp" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs border border-zinc-200 rounded-lg text-zinc-600 hover:bg-zinc-50 transition">
                                ← Back
                            </button>
                            @endif
                            <label class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs bg-red-600 hover:bg-red-700 text-white rounded-lg cursor-pointer transition font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload Images
                                <input type="file" wire:model="uploadedImages" multiple accept="image/*" class="hidden">
                            </label>
                            <div wire:loading wire:target="uploadedImages" class="text-xs text-zinc-400 flex items-center gap-1.5">
                                <span class="w-3 h-3 border-2 border-zinc-300 border-t-red-500 rounded-full animate-spin inline-block"></span>
                                Uploading…
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto p-4">
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                @foreach($folderContents['folders'] as $folder)
                                <div class="group relative">
                                    <button wire:click="navigateFolder('{{ $folder['rel'] }}')"
                                            class="w-full aspect-square rounded-xl bg-zinc-50 hover:bg-zinc-100 border border-zinc-200 hover:border-zinc-300 flex flex-col items-center justify-center transition">
                                        <span class="text-3xl mb-1">📁</span>
                                        <span class="text-xs text-zinc-500 truncate max-w-full px-2">{{ $folder['name'] }}</span>
                                    </button>
                                    <div class="absolute top-1.5 right-1.5 hidden group-hover:flex gap-1">
                                        <button wire:click="startRename('{{ $folder['rel'] }}')" class="w-6 h-6 rounded-lg bg-yellow-400 text-white text-xs flex items-center justify-center shadow-sm">✎</button>
                                        <button wire:click="deleteItem('{{ $folder['rel'] }}')" wire:confirm="Delete folder?" class="w-6 h-6 rounded-lg bg-red-500 text-white text-xs flex items-center justify-center shadow-sm">×</button>
                                    </div>
                                </div>
                                @endforeach

                                @foreach($folderContents['images'] as $image)
                                <div class="group relative">
                                    <button wire:click="addToPending('{{ $image['url'] }}', '{{ $image['rel'] }}', '{{ $image['name'] }}')"
                                            class="w-full aspect-square rounded-xl overflow-hidden border-2 border-transparent hover:border-red-500 transition shadow-sm">
                                        <img src="{{ $image['url'] }}" alt="{{ $image['name'] }}" class="w-full h-full object-cover bg-zinc-100">
                                    </button>
                                    <div class="absolute top-1.5 right-1.5 hidden group-hover:flex gap-1">
                                        <button wire:click="startRename('{{ $image['rel'] }}')" class="w-6 h-6 rounded-lg bg-yellow-400 text-white text-xs flex items-center justify-center shadow-sm">✎</button>
                                        <button wire:click="copyImage('{{ $image['rel'] }}')" class="w-6 h-6 rounded-lg bg-blue-400 text-white text-xs flex items-center justify-center shadow-sm">⧉</button>
                                        <button wire:click="deleteItem('{{ $image['rel'] }}')" wire:confirm="Delete image?" class="w-6 h-6 rounded-lg bg-red-500 text-white text-xs flex items-center justify-center shadow-sm">×</button>
                                    </div>
                                    <p class="text-xs text-zinc-400 truncate text-center mt-1.5 px-1">{{ $image['name'] }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Pending --}}
                        @if(count($pendingImages) > 0)
                        <div class="border-t border-zinc-100 bg-zinc-50 p-4 flex-shrink-0">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-sm font-semibold text-zinc-700">
                                    Ready to Insert
                                    <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-100 text-red-600 text-xs font-bold">{{ count($pendingImages) }}</span>
                                </p>
                                <span class="text-xs text-zinc-400">Add alt text, then confirm</span>
                            </div>
                            <div class="flex gap-3 overflow-x-auto pb-2">
                                @foreach($pendingImages as $i => $pending)
                                <div class="flex-shrink-0 w-36 border border-zinc-200 rounded-xl overflow-hidden bg-white shadow-sm">
                                    <img src="{{ $pending['url'] }}" alt="" class="w-full h-24 object-cover bg-zinc-100">
                                    <div class="p-2 space-y-1">
                                        <input type="text" value="{{ $pending['alt'] }}"
                                               wire:change="updatePendingAlt({{ $i }}, $event.target.value)"
                                               placeholder="Alt text…"
                                               class="w-full text-xs px-2 py-1 rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-700 focus:outline-none focus:ring-1 focus:ring-red-500">
                                        <button wire:click="removePending({{ $i }})" class="w-full text-xs text-red-500 hover:text-red-700 transition font-medium py-0.5">Remove</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="flex gap-3 mt-3">
                                <button wire:click="confirmInsertImages"
                                        class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition shadow-sm shadow-red-200">
                                    Insert {{ count($pendingImages) > 1 ? 'All Images' : 'Image' }}
                                </button>
                                <button wire:click="$set('pendingImages', [])"
                                        class="px-5 py-2 rounded-xl border border-zinc-200 text-zinc-600 hover:bg-zinc-50 text-sm font-semibold transition">
                                    Clear
                                </button>
                            </div>
                        </div>
                        @endif

                        {{-- Rename --}}
                        @if($renamingItem)
                        <div class="border-t border-zinc-100 bg-yellow-50 p-4 flex-shrink-0 flex items-center gap-3">
                            <span class="text-sm text-zinc-600 font-medium">Rename:</span>
                            <input wire:model.lazy="renameValue" class="flex-1 px-3 py-1.5 text-sm rounded-xl border border-zinc-200 bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <button wire:click="confirmRename" class="px-4 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-xl font-semibold transition">Save</button>
                            <button wire:click="$set('renamingItem', '')" class="px-4 py-1.5 border border-zinc-200 text-zinc-600 text-sm rounded-xl transition hover:bg-zinc-50">Cancel</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @else
        {{-- Articles list --}}
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-zinc-900">Articles</h2>
                    <p class="text-sm text-zinc-400 mt-0.5">{{ $articles->total() }} total</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-sm">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-zinc-100 bg-zinc-50">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider hidden lg:table-cell">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider hidden md:table-cell">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-zinc-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($articles as $article)
                        <tr class="hover:bg-zinc-50 transition group">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-zinc-900 group-hover:text-red-600 transition">{{ $article->title }}</p>
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                @if($article->category)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-50 text-red-600 text-xs font-medium border border-red-100">{{ $article->category }}</span>
                                @else
                                    <span class="text-xs text-zinc-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <span class="text-xs font-mono text-zinc-400">{{ $article->slug }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($article->published_at)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                    {{ $article->published_at->format('M d, Y') }}
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-zinc-100 text-zinc-400 text-xs font-semibold">
                                    Draft
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('blog.show', $article->slug) }}" target="_blank"
                                       class="px-3 py-1.5 rounded-lg border border-zinc-200 hover:bg-zinc-50 text-zinc-600 text-xs font-semibold transition">View</a>
                                    <button wire:click="editArticle({{ $article->id }})"
                                            class="px-3 py-1.5 rounded-lg bg-zinc-900 hover:bg-zinc-700 text-white text-xs font-semibold transition">Edit</button>
                                    <button wire:click="deleteArticle({{ $article->id }})" wire:confirm="Delete this article?"
                                            class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold transition border border-red-100">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4 border-t border-zinc-100 bg-zinc-50">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<script>
window.blogEditor = function(initialContent) {
    return {
        doc: { type: 'doc', content: [] },
        insertedImages: [],
        activeMarks: { bold: false, italic: false, underline: false, strike: false, highlight: false },
        currentFontSize: 'md',
        highlightColor: '#fef08a',
        openDropdown: null,
        focusedBlockIndex: 0,

        init() {
            try {
                this.doc = typeof initialContent === 'string' ? JSON.parse(initialContent) : initialContent;
            } catch(e) {
                this.doc = { type: 'doc', content: [] };
            }
            if (!this.doc.content?.length) this.doc.content = [this.newParagraph()];
            this.$nextTick(() => this.renderAll());

            Livewire.on('editorContentUpdated', (data) => {
                const val = data[0] ?? data;
                try {
                    const parsed = typeof val === 'string' ? JSON.parse(val) : val;
                    this.doc = parsed;
                    this.$nextTick(() => this.renderAll());
                } catch(_) {}
            });

            // Listen for insertImages via both Livewire event and raw window event
            Livewire.on('insertImages', (data) => {
                console.log('Livewire.on insertImages:', data);
                const images = data[0] ?? data;
                this.insertImages(images);
            });
        },

        // ── Render doc → DOM ──────────────────────────────────────────────────
        renderAll() {
            const canvas = this.$refs.canvas;
            if (!canvas) return;
            canvas.innerHTML = '';
            this.doc.content.forEach((block, idx) => {
                canvas.appendChild(this.createBlockEl(block, idx));
            });
            this.syncInsertedImages();
        },

        createBlockEl(block, idx) {
            let el = document.createElement('div');
            el.dataset.blockIndex = String(idx);
            el.dataset.blockType = block.type;

            switch (block.type) {
                case 'image': {
                    el.contentEditable = 'false';
                    const align = block.attrs?.align ?? 'center';
                    const width = block.attrs?.width ?? 100;
                    el.style.cssText = `display:flex;margin:1rem 0;justify-content:${align === 'left' ? 'flex-start' : align === 'right' ? 'flex-end' : 'center'}`;
                    const img = document.createElement('img');
                    img.src = block.attrs?.src ?? '';
                    img.alt = block.attrs?.alt ?? '';
                    img.style.cssText = `width:${width}%;max-width:100%;border-radius:0.75rem;box-shadow:0 2px 8px rgba(0,0,0,.15);display:block`;
                    el.appendChild(img);
                    el.addEventListener('mousedown', () => this.focusedBlockIndex = idx);
                    break;
                }
                case 'spacer': {
                    el.style.height = '1.5em';
                    el.contentEditable = 'false';
                    break;
                }
                case 'bulletList': {
                    el.className = 'my-2 pl-4';
                    el.contentEditable = 'true';
                    el.style.listStyleType = 'none';
                    (block.content ?? []).forEach(item => {
                        const row = document.createElement('div');
                        row.dataset.listItem = '1';
                        row.style.cssText = 'padding-left:1.2em;position:relative;min-height:1.4em';
                        // Bullet via pseudo — inject as ::before via a non-editable span
                        const bullet = document.createElement('span');
                        bullet.contentEditable = 'false';
                        bullet.style.cssText = 'position:absolute;left:0;user-select:none;pointer-events:none';
                        bullet.textContent = '•';
                        row.appendChild(bullet);
                        const content = document.createElement('span');
                        content.dataset.itemContent = '1';
                        content.innerHTML = this.renderInline(item.content ?? []) || '<br>';
                        row.appendChild(content);
                        el.appendChild(row);
                    });
                    break;
                }
                case 'orderedList': {
                    el.className = 'my-2 pl-4';
                    el.contentEditable = 'true';
                    (block.content ?? []).forEach((item, i) => {
                        const row = document.createElement('div');
                        row.dataset.listItem = '1';
                        row.style.cssText = 'padding-left:1.8em;position:relative;min-height:1.4em';
                        const num = document.createElement('span');
                        num.contentEditable = 'false';
                        num.style.cssText = 'position:absolute;left:0;user-select:none;pointer-events:none';
                        num.textContent = `${i + 1}.`;
                        row.appendChild(num);
                        const content = document.createElement('span');
                        content.dataset.itemContent = '1';
                        content.innerHTML = this.renderInline(item.content ?? []) || '<br>';
                        row.appendChild(content);
                        el.appendChild(row);
                    });
                    break;
                }
                default: {
                    // paragraph — plain div, no heading semantics, no browser bold context
                    el.className = 'min-h-[1.5em]';
                    el.contentEditable = 'true';
                    const inner = this.renderInline(block.content ?? []);
                    el.innerHTML = inner || '<br>';
                    break;
                }
            }

            if (el.contentEditable === 'true') {
                el.addEventListener('focus', () => {
                    this.focusedBlockIndex = idx;
                    this.updateActiveMarks();
                });
            }

            return el;
        },

        // ── Render inline nodes → HTML ────────────────────────────────────────
        renderInline(nodes) {
            return (nodes ?? []).map(node => {
                if (node.type === 'hardBreak') return '<br>';
                if (node.type !== 'text') return '';
                let text = this.esc(node.text ?? '');
                text = text.replace(/  /g, '&nbsp; ');
                for (const mark of (node.marks ?? [])) text = this.applyMarkHtml(text, mark);
                return text;
            }).join('');
        },

        applyMarkHtml(text, mark) {
            switch (mark.type) {
                case 'bold':      return `<strong>${text}</strong>`;
                case 'italic':    return `<em>${text}</em>`;
                case 'underline': return `<u>${text}</u>`;
                case 'strike':    return `<s>${text}</s>`;
                case 'highlight': {
                    const c = mark.attrs?.color ?? '#fef08a';
                    return `<span style="background-color:${c}">${text}</span>`;
                }
                case 'link':      return `<a href="${this.esc(mark.attrs?.href ?? '#')}" class="text-red-600 underline">${text}</a>`;
                case 'fontSize': {
                    const sizes = { sm: '0.75em', md: '1em', lg: '1.375em', xl: '2em' };
                    const s = sizes[mark.attrs?.size] ?? '1em';
                    return `<span data-fontsize="${mark.attrs?.size}" style="font-size:${s}">${text}</span>`;
                }
                default: return text;
            }
        },

        esc(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        },

        // ── Parse DOM → doc ───────────────────────────────────────────────────
        parseAndSync() {
            const canvas = this.$refs.canvas;
            if (!canvas) return;
            const blocks = [];
            canvas.childNodes.forEach(el => {
                const b = this.parseEl(el);
                if (b) blocks.push(b);
            });
            this.doc = { type: 'doc', content: blocks.length ? blocks : [this.newParagraph()] };
            this.syncInsertedImages();
        },

        parseEl(el) {
            if (!el || el.nodeType !== 1) return null;
            const type = el.dataset?.blockType;
            const idx  = parseInt(el.dataset?.blockIndex ?? '-1');

            if (type === 'image')  return this.doc.content[idx] ?? null;
            if (type === 'spacer') return { type: 'spacer', attrs: { lines: 1 } };
            if (type === 'bulletList')  return { type: 'bulletList',  content: Array.from(el.querySelectorAll('[data-list-item]')).map(row => ({ type: 'listItem', content: this.parseInline(row.querySelector('[data-item-content]') ?? row) })) };
            if (type === 'orderedList') return { type: 'orderedList', content: Array.from(el.querySelectorAll('[data-list-item]')).map(row => ({ type: 'listItem', content: this.parseInline(row.querySelector('[data-item-content]') ?? row) })) };

            // default: paragraph div
            return { type: 'paragraph', content: this.parseInline(el) };
        },

        parseInline(el) {
            const nodes = [];
            el.childNodes.forEach(node => {
                if (node.nodeType === 3) {
                    if (node.textContent) nodes.push({ type: 'text', text: node.textContent });
                } else if (node.nodeType === 1) {
                    const tag = node.tagName.toLowerCase();
                    if (tag === 'br') { nodes.push({ type: 'hardBreak' }); return; }
                    const children = this.parseInline(node);
                    const mark = this.tagToMark(node);
                    if (mark) children.forEach(cn => {
                        if (cn.type === 'text') cn.marks = cn.marks ? [mark, ...cn.marks] : [mark];
                    });
                    nodes.push(...children);
                }
            });
            return nodes;
        },

        tagToMark(el) {
            const tag = el.tagName.toLowerCase();
            if (tag === 'strong' || tag === 'b') return { type: 'bold' };
            if (tag === 'em'     || tag === 'i') return { type: 'italic' };
            if (tag === 'u')                     return { type: 'underline' };
            if (tag === 's')                     return { type: 'strike' };
            if (tag === 'mark')                  return { type: 'highlight', attrs: { color: el.style.backgroundColor || '#fef08a' } };
            if (tag === 'a')                     return { type: 'link', attrs: { href: el.getAttribute('href') } };
            if (tag === 'span') {
                if (el.dataset.fontsize) return { type: 'fontSize', attrs: { size: el.dataset.fontsize } };
                // hiliteColor span — store the actual color
                const bg = el.style.backgroundColor;
                if (bg && bg !== 'transparent' && bg !== '') return { type: 'highlight', attrs: { color: bg } };
            }
            return null;
        },

        // ── Formatting ────────────────────────────────────────────────────────
        applyMark(type) {
            if (type === 'highlight') {
                document.execCommand('hiliteColor', false, this.highlightColor);
                return;
            }
            const cmd = { bold: 'bold', italic: 'italic', underline: 'underline', strike: 'strikethrough' }[type];
            if (cmd) document.execCommand(cmd, false, null);
            this.updateActiveMarks();
        },

        wrapSelection(tag, className = '') {
            const sel = window.getSelection();
            if (!sel || sel.rangeCount === 0 || sel.isCollapsed) return;
            const range = sel.getRangeAt(0);
            try {
                const el = document.createElement(tag);
                if (className) el.className = className;
                range.surroundContents(el);
            } catch(e) {
                const frag = range.extractContents();
                const el = document.createElement(tag);
                if (className) el.className = className;
                el.appendChild(frag);
                range.insertNode(el);
            }
        },

        applyFontSize(size) {
            const sel = window.getSelection();
            if (!sel || sel.rangeCount === 0 || sel.isCollapsed) return;
            const range = sel.getRangeAt(0);
            const sizes = { sm: '0.75em', md: '1em', lg: '1.375em', xl: '2em' };
            try {
                const span = document.createElement('span');
                span.dataset.fontsize = size;
                span.style.fontSize = sizes[size] ?? '1em';
                range.surroundContents(span);
            } catch(e) {
                const frag = range.extractContents();
                const span = document.createElement('span');
                span.dataset.fontsize = size;
                span.style.fontSize = sizes[size] ?? '1em';
                span.appendChild(frag);
                range.insertNode(span);
            }
            this.currentFontSize = size;
            this.openDropdown = null;
        },

        insertLink() {
            const url = prompt('Enter URL:');
            if (url) document.execCommand('createLink', false, url);
        },

        // ── Insert new block after current ────────────────────────────────────
        insertBlock(type) {
            let newBlock;
            switch(type) {
                case 'bulletList':  newBlock = { type: 'bulletList',  content: [{ type: 'listItem', content: [{ type: 'text', text: '' }] }] }; break;
                case 'orderedList': newBlock = { type: 'orderedList', content: [{ type: 'listItem', content: [{ type: 'text', text: '' }] }] }; break;
                case 'spacer':      newBlock = { type: 'spacer', attrs: { lines: 1 } }; break;
                default:            newBlock = this.newParagraph();
            }
            const insertAt = this.focusedBlockIndex + 1;
            this.doc.content.splice(insertAt, 0, newBlock);
            this.$nextTick(() => {
                this.renderAll();
                const newEl = this.$refs.canvas?.querySelector(`[data-block-index="${insertAt}"]`);
                if (newEl?.contentEditable === 'true') {
                    newEl.focus();
                    const r = document.createRange();
                    r.selectNodeContents(newEl);
                    r.collapse(false);
                    window.getSelection().removeAllRanges();
                    window.getSelection().addRange(r);
                }
            });
        },

        // ── Images ────────────────────────────────────────────────────────────
        insertImages(images) {
            console.log('insertImages called with:', images);
            // Livewire 4 wraps dispatch params — unwrap if needed
            const list = Array.isArray(images) ? images : (images[0] ?? []);
            console.log('list after unwrap:', list);
            if (!list.length) { console.warn('insertImages: empty list, bailing'); return; }

            const insertAt = this.focusedBlockIndex + 1;
            const newBlocks = list.map(img => ({
                type: 'image',
                attrs: { src: img.url, alt: img.alt ?? '', align: 'center' }
            }));
            this.doc.content.splice(insertAt, 0, ...newBlocks);
            const afterIdx = insertAt + newBlocks.length;
            if (!this.doc.content[afterIdx] || this.doc.content[afterIdx].type === 'image') {
                this.doc.content.splice(afterIdx, 0, this.newParagraph());
            }
            this.focusedBlockIndex = afterIdx;
            this.$nextTick(() => this.renderAll());
        },

        syncInsertedImages() {
            this.insertedImages = this.doc.content
                .map((b, idx) => b.type === 'image' ? {
                    src:    b.attrs.src,
                    alt:    b.attrs.alt ?? '',
                    align:  b.attrs.align ?? 'center',
                    width:  b.attrs.width ?? 100,
                    docIdx: idx,
                } : null)
                .filter(Boolean);
        },

        updateImageAltInDoc(listIdx, alt) {
            const img = this.insertedImages[listIdx];
            if (!img) return;
            this.doc.content[img.docIdx].attrs.alt = alt;
            this.refreshImageInCanvas(img.docIdx);
        },

        updateImageAlign(listIdx, align) {
            const img = this.insertedImages[listIdx];
            if (!img) return;
            this.doc.content[img.docIdx].attrs.align = align;
            this.insertedImages[listIdx].align = align;
            this.refreshImageInCanvas(img.docIdx);
        },

        updateImageWidth(listIdx, width) {
            const img = this.insertedImages[listIdx];
            if (!img) return;
            this.doc.content[img.docIdx].attrs.width = width;
            this.insertedImages[listIdx].width = width;
            this.refreshImageInCanvas(img.docIdx);
        },

        refreshImageInCanvas(docIdx) {
            const canvas = this.$refs.canvas;
            const el = canvas?.querySelector(`[data-block-index="${docIdx}"]`);
            if (!el) return;
            const block = this.doc.content[docIdx];
            const newEl = this.createBlockEl(block, docIdx);
            canvas.replaceChild(newEl, el);
        },

        removeImageFromDoc(listIdx) {
            const img = this.insertedImages[listIdx];
            if (!img) return;
            this.doc.content.splice(img.docIdx, 1);
            this.syncInsertedImages();
            this.$nextTick(() => this.renderAll());
        },

        // ── Keyboard ──────────────────────────────────────────────────────────
        handleEnter(e) {
            e.preventDefault();

            const canvas = this.$refs.canvas;
            const focusedBlock = canvas?.querySelector(`[data-block-index="${this.focusedBlockIndex}"]`);
            const listType = focusedBlock?.dataset.blockType;
            const isInList = listType === 'bulletList' || listType === 'orderedList';

            if (isInList) {
                if (e.shiftKey) {
                    // Sync current list block from DOM first, then insert paragraph
                    const block = this.parseEl(focusedBlock);
                    if (block) this.doc.content[this.focusedBlockIndex] = block;
                    this.insertBlock('paragraph');
                } else {
                    // Enter = new item in same list
                    const block = this.doc.content[this.focusedBlockIndex];
                    if (!block) return;
                    block.content.push({ type: 'listItem', content: [{ type: 'text', text: '' }] });

                    const i = block.content.length - 1;
                    const row = document.createElement('div');
                    row.dataset.listItem = '1';
                    row.style.cssText = listType === 'orderedList'
                        ? 'padding-left:1.8em;position:relative;min-height:1.4em'
                        : 'padding-left:1.2em;position:relative;min-height:1.4em';

                    const prefix = document.createElement('span');
                    prefix.contentEditable = 'false';
                    prefix.style.cssText = 'position:absolute;left:0;user-select:none;pointer-events:none';
                    prefix.textContent = listType === 'orderedList' ? `${i + 1}.` : '•';
                    row.appendChild(prefix);

                    const content = document.createElement('span');
                    content.dataset.itemContent = '1';
                    content.innerHTML = '<br>';
                    row.appendChild(content);
                    focusedBlock.appendChild(row);

                    content.focus();
                    const r = document.createRange();
                    r.selectNodeContents(content);
                    r.collapse(false);
                    window.getSelection().removeAllRanges();
                    window.getSelection().addRange(r);
                }
                return;
            }

            // Regular paragraph — line break within block
            document.execCommand('insertLineBreak');
        },

        handleInput() {
            // no-op: parse only on save
        },

        handleCanvasClick(e) {
            // If user drag-selected text, don't interfere — just update marks
            const sel = window.getSelection();
            if (sel && !sel.isCollapsed) {
                this.updateActiveMarks();
                return;
            }

            const clicked = e.target.closest('[data-block-index]');
            if (clicked?.contentEditable === 'true') {
                this.focusedBlockIndex = parseInt(clicked.dataset.blockIndex);
                return;
            }
            // Clicked empty canvas area — focus last editable block
            const canvas = this.$refs.canvas;
            const editables = canvas.querySelectorAll('[contenteditable="true"]');
            if (editables.length) {
                const last = editables[editables.length - 1];
                last.focus();
                const r = document.createRange();
                r.selectNodeContents(last);
                r.collapse(false);
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(r);
                this.focusedBlockIndex = parseInt(last.dataset.blockIndex ?? '0');
            } else {
                this.doc.content = [this.newParagraph()];
                this.$nextTick(() => this.$refs.canvas?.querySelector('[contenteditable="true"]')?.focus());
            }
        },

        updateActiveMarks() {
            this.activeMarks = {
                bold:      document.queryCommandState('bold'),
                italic:    document.queryCommandState('italic'),
                underline: document.queryCommandState('underline'),
                strike:    document.queryCommandState('strikethrough'),
                highlight: false,
            };
            const sel = window.getSelection();
            if (sel?.rangeCount) {
                const node = sel.getRangeAt(0).startContainer;
                const span = node.nodeType === 3
                    ? node.parentElement?.closest('[data-fontsize]')
                    : node.closest?.('[data-fontsize]');
                this.currentFontSize = span?.dataset?.fontsize ?? 'md';
            }
        },

        // ── Save ──────────────────────────────────────────────────────────────
        saveToLivewire() {
            this.parseAndSync();
            const json = JSON.stringify(this.doc);
            this.$wire.set('article', json).then(() => this.$wire.saveArticle());
        },

        newParagraph() {
            return { type: 'paragraph', content: [{ type: 'text', text: '' }] };
        },
    };
};
</script>