<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">{{ $page ? 'Edit Page' : 'Create Page' }}</h1>
            <p class="text-text-secondary mt-1">Manage content for static routes.</p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 text-text-secondary hover:text-text-primary transition-colors">
            Cancel
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="bg-bg-surface border border-bg-border rounded-xl shadow-sm p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Title</label>
                    <input type="text" wire:model.live="title" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Slug</label>
                    <input type="text" wire:model="slug" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <p class="text-xs text-text-secondary mt-1">Example: privacy, terms, about, blogs</p>
                    @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1">Meta Description (Optional)</label>
                <textarea wire:model="meta_description" rows="2" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                @error('meta_description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1">Content (HTML or plain text)</label>
                <textarea wire:model="content" rows="15" class="w-full font-mono text-sm bg-bg-element border border-bg-border rounded-lg px-4 py-3 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                @error('content') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="border-t border-bg-border pt-6 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="is_published" wire:model="is_published" class="h-4 w-4 text-primary-500 bg-bg-element border-bg-border rounded focus:ring-primary-500">
                    <label for="is_published" class="text-sm font-medium text-text-primary">
                        Published
                    </label>
                </div>
                
                <button type="submit" class="px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 font-medium transition-colors">
                    Save Page
                </button>
            </div>
        </div>
    </form>
</div>
