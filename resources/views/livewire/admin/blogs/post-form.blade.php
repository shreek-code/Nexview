<div>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    
    <style>
        /* Quill Dark Mode & Base Overrides */
        .ql-toolbar.ql-snow { border-color: var(--color-bg-border); border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; background: var(--color-bg-element); }
        .ql-container.ql-snow { border-color: var(--color-bg-border); border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; background: var(--color-bg-element); color: var(--color-text-primary); font-family: inherit; font-size: 1rem; }
        .ql-editor { min-height: 300px; }
        .ql-snow .ql-stroke { stroke: var(--color-text-secondary); }
        .ql-snow .ql-fill, .ql-snow .ql-stroke.ql-fill { fill: var(--color-text-secondary); }
        .ql-snow .ql-picker { color: var(--color-text-secondary); }
        
        /* TomSelect Dark Mode Overrides */
        .ts-control { background-color: var(--color-bg-element); border-color: var(--color-bg-border); color: var(--color-text-primary); border-radius: 0.5rem; padding: 0.5rem 0.75rem; }
        .ts-dropdown, .ts-control, .ts-control input { color: var(--color-text-primary); }
        .ts-dropdown { background-color: var(--color-bg-element); border-color: var(--color-bg-border); }
        .ts-dropdown .option { color: var(--color-text-primary); }
        .ts-dropdown .active { background-color: var(--color-primary-500); color: white; }
        .ts-control.multi .ts-wrapper { background-color: transparent; }
        .ts-control > input { color: var(--color-text-primary); }
        .ts-wrapper.multi .ts-control > div { background: var(--color-primary-500); color: white; border: none; border-radius: 0.25rem; }
    </style>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">{{ $post ? 'Edit Blog Post' : 'Create Blog Post' }}</h1>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="text-text-secondary hover:text-text-primary font-medium flex items-center">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Back to Posts
        </a>
    </div>

    <form wire:submit.prevent="save" class="bg-bg-surface border border-bg-border rounded-xl shadow-sm p-6 space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.label for="title" value="Post Title" />
                <x-ui.input id="title" type="text" class="mt-1 block w-full" wire:model.blur="title" required />
                <x-ui.input-error for="title" class="mt-2" />
            </div>

            <div>
                <x-ui.label for="slug" value="URL Slug" />
                <x-ui.input id="slug" type="text" class="mt-1 block w-full" wire:model="slug" required />
                <x-ui.input-error for="slug" class="mt-2" />
            </div>
        </div>

        <div>
            <x-ui.label for="excerpt" value="Excerpt (Optional)" />
            <textarea id="excerpt" rows="2" class="mt-1 block w-full border-bg-border bg-bg-element text-text-primary rounded-lg focus:ring-signal-500 focus:border-signal-500" wire:model="excerpt"></textarea>
            <x-ui.input-error for="excerpt" class="mt-2" />
        </div>

        <!-- Quill Editor -->
        <div wire:ignore>
            <x-ui.label for="content" value="Content" class="mb-1" />
            <div x-data="{
                    content: @entangle('content'),
                    init() {
                        let quill = new Quill(this.$refs.quillEditor, {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    [{ 'header': [1, 2, 3, false] }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    ['blockquote', 'code-block'],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    ['link', 'image'],
                                    ['clean']
                                ]
                            }
                        });
                        quill.root.innerHTML = this.content;
                        quill.on('text-change', () => {
                            this.content = quill.root.innerHTML;
                        });
                    }
                }"
            >
                <div x-ref="quillEditor" class="bg-bg-element text-text-primary"></div>
            </div>
        </div>
        <x-ui.input-error for="content" class="mt-2" />

        <!-- Selects -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div wire:ignore>
                <x-ui.label for="authors" value="Authors" class="mb-1" />
                <div x-data="{
                    selected: @entangle('selected_authors'),
                    init() {
                        let ts = new TomSelect(this.$refs.select, {
                            plugins: ['remove_button'],
                            maxItems: null,
                            onChange: (value) => {
                                this.selected = value;
                            }
                        });
                    }
                }">
                    <select x-ref="select" multiple class="hidden">
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" @if(in_array($author->id, $selected_authors ?? [])) selected @endif>{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-ui.input-error for="selected_authors" class="mt-2" />
            </div>

            <div wire:ignore>
                <x-ui.label for="categories" value="Categories" class="mb-1" />
                <div x-data="{
                    selected: @entangle('selected_categories'),
                    init() {
                        let ts = new TomSelect(this.$refs.select, {
                            plugins: ['remove_button'],
                            maxItems: null,
                            onChange: (value) => {
                                this.selected = value;
                            }
                        });
                    }
                }">
                    <select x-ref="select" multiple class="hidden">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if(in_array($category->id, $selected_categories ?? [])) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-ui.input-error for="selected_categories" class="mt-2" />
            </div>

            <div wire:ignore>
                <x-ui.label for="tags" value="Tags" class="mb-1" />
                <div x-data="{
                    selected: @entangle('selected_tags'),
                    init() {
                        let ts = new TomSelect(this.$refs.select, {
                            plugins: ['remove_button'],
                            maxItems: null,
                            onChange: (value) => {
                                this.selected = value;
                            }
                        });
                    }
                }">
                    <select x-ref="select" multiple class="hidden">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" @if(in_array($tag->id, $selected_tags ?? [])) selected @endif>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-ui.input-error for="selected_tags" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.label for="featured_image" value="Featured Image" />
                <input type="file" id="featured_image" wire:model="featured_image" class="mt-1 block w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-signal-500/10 file:text-signal-500 hover:file:bg-signal-500/20" />
                <x-ui.input-error for="featured_image" class="mt-2" />
                
                @if($featured_image)
                    <div class="mt-2">
                        <p class="text-xs text-text-secondary mb-1">New Image Preview:</p>
                        <img src="{{ $featured_image->temporaryUrl() }}" class="h-24 object-cover rounded-md">
                    </div>
                @elseif($existing_image)
                    <div class="mt-2">
                        <p class="text-xs text-text-secondary mb-1">Current Image:</p>
                        <img src="{{ Storage::url($existing_image) }}" class="h-24 object-cover rounded-md">
                    </div>
                @endif
            </div>
        </div>

        <div class="border-t border-bg-border pt-6 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <div class="flex items-center space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" wire:model="is_published" class="rounded border-bg-border text-signal-500 focus:ring-signal-500 bg-bg-element">
                    <span class="ml-2 text-sm text-text-primary">Publish Post</span>
                </label>

                <div x-data="{ published: @entangle('is_published') }" x-show="published" class="flex items-center space-x-2">
                    <x-ui.label for="published_at" value="Publish Date" class="mb-0" />
                    <x-ui.input id="published_at" type="datetime-local" class="text-sm" wire:model="published_at" />
                </div>
            </div>

            <div class="flex justify-end">
                <x-ui.button type="submit" class="bg-signal-600 hover:bg-signal-700">
                    {{ $post ? 'Update Post' : 'Create Post' }}
                </x-ui.button>
            </div>
        </div>

    </form>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</div>
