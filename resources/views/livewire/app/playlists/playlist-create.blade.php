<div>
    <div class="mb-8">
        <div class="flex items-center space-x-3 text-sm text-text-tertiary mb-2">
            <a href="{{ route('app.playlists.index') }}" wire:navigate class="hover:text-text-primary transition-colors">Playlists</a>
            <x-heroicon-o-chevron-right class="w-4 h-4" />
            <span class="text-text-primary">Create</span>
        </div>
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Create New Playlist</h1>
    </div>

    <form wire:submit="save" x-data="{
        mediaLibrary: {{ json_encode($media) }},
        selectedItems: @entangle('selectedMedia'),
        
        addMedia(mediaId) {
            const media = this.mediaLibrary.find(m => m.id === mediaId);
            if(media) {
                this.selectedItems.push({
                    uid: Date.now().toString() + Math.random().toString(),
                    id: media.id,
                    name: media.name,
                    type: media.type,
                    file_path: media.file_path,
                    duration: media.type === 'image' ? 10 : 30
                });
            }
        },
        
        removeMedia(uid) {
            this.selectedItems = this.selectedItems.filter(item => item.uid !== uid);
        },
        
        moveItem(index, direction) {
            if (direction === 'up' && index > 0) {
                const temp = this.selectedItems[index - 1];
                this.selectedItems[index - 1] = this.selectedItems[index];
                this.selectedItems[index] = temp;
            } else if (direction === 'down' && index < this.selectedItems.length - 1) {
                const temp = this.selectedItems[index + 1];
                this.selectedItems[index + 1] = this.selectedItems[index];
                this.selectedItems[index] = temp;
            }
        },
        
        getTotalDuration() {
            const total = this.selectedItems.reduce((sum, item) => sum + parseInt(item.duration || 0), 0);
            const h = Math.floor(total / 3600);
            const m = Math.floor((total % 3600) / 60);
            const s = Math.floor(total % 60);
            return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }
    }">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Playlist Builder -->
            <div class="xl:col-span-2 space-y-6">
                <x-ui.card class="p-6">
                    <h2 class="text-lg font-semibold text-text-primary mb-4">Playlist Details</h2>
                    <div>
                        <x-ui.label for="name" value="Playlist Name" />
                        <x-ui.input id="name" type="text" wire:model="name" class="mt-1" placeholder="e.g. Lobby Morning Loop" required autofocus />
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </x-ui.card>

                <x-ui.card class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-text-primary">Playlist Items</h2>
                        <div class="text-sm font-medium text-text-secondary bg-surface-2 px-3 py-1 rounded-full">
                            Total Duration: <span x-text="getTotalDuration()"></span>
                        </div>
                    </div>
                    
                    @error('selectedMedia')
                        <div class="mb-4 text-sm text-red-600 bg-red-500/10 border border-red-500/20 p-3 rounded-lg">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Selected Items List -->
                    <div class="space-y-3" x-show="selectedItems.length > 0">
                        <template x-for="(item, index) in selectedItems" :key="item.uid">
                            <div class="flex items-center p-3 bg-surface-2 border border-border-subtle rounded-xl group transition-all">
                                <div class="flex flex-col space-y-1 mr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" @click="moveItem(index, 'up')" :disabled="index === 0" class="text-text-tertiary hover:text-text-primary disabled:opacity-30">
                                        <x-heroicon-s-chevron-up class="w-4 h-4" />
                                    </button>
                                    <button type="button" @click="moveItem(index, 'down')" :disabled="index === selectedItems.length - 1" class="text-text-tertiary hover:text-text-primary disabled:opacity-30">
                                        <x-heroicon-s-chevron-down class="w-4 h-4" />
                                    </button>
                                </div>
                                
                                <div class="w-12 h-12 rounded-lg bg-surface-3 flex items-center justify-center overflow-hidden shrink-0">
                                    <template x-if="item.type === 'image'">
                                        <img :src="'/storage/' + item.file_path" class="w-full h-full object-cover" />
                                    </template>
                                    <template x-if="item.type !== 'image'">
                                        <x-heroicon-o-film class="w-6 h-6 text-text-tertiary" />
                                    </template>
                                </div>
                                
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-text-primary truncate" x-text="item.name"></p>
                                    <p class="text-xs text-text-tertiary uppercase tracking-wider mt-0.5" x-text="item.type"></p>
                                </div>
                                
                                <div class="ml-4 flex items-center space-x-2">
                                    <div class="flex items-center">
                                        <input type="number" x-model.number="item.duration" min="1" class="w-20 h-9 rounded-l-md border border-r-0 border-border-base bg-surface-1 px-3 py-1 text-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-signal-500 z-10" required>
                                        <div class="h-9 px-3 flex items-center bg-surface-3 border border-border-base rounded-r-md text-sm text-text-tertiary font-medium">sec</div>
                                    </div>
                                    
                                    <button type="button" @click="removeMedia(item.uid)" class="p-2 ml-2 text-text-tertiary hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="selectedItems.length === 0" class="py-12 border-2 border-dashed border-border-base rounded-xl flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 rounded-full bg-surface-2 flex items-center justify-center text-text-tertiary mb-3">
                            <x-heroicon-o-queue-list class="w-6 h-6" />
                        </div>
                        <p class="text-text-primary font-medium">No media selected</p>
                        <p class="text-sm text-text-secondary mt-1">Select items from the media library to add them to your playlist.</p>
                    </div>
                </x-ui.card>
            </div>

            <!-- Media Library Sidebar -->
            <div class="xl:col-span-1">
                <x-ui.card class="p-0 overflow-hidden sticky top-6 h-[calc(100vh-8rem)] flex flex-col">
                    <div class="p-4 border-b border-border-subtle bg-surface-1 z-10">
                        <h2 class="text-sm font-semibold text-text-primary uppercase tracking-wider mb-3">Media Library</h2>
                        <x-ui.input type="text" placeholder="Search media..." class="h-9 text-sm" x-data="{ query: '' }" @input="$el.dispatchEvent(new CustomEvent('search', { detail: $el.value, bubbles: true }))" />
                    </div>
                    
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-surface-2/50" x-data="{ searchQuery: '' }" @search.window="searchQuery = $event.detail.toLowerCase()">
                        <template x-for="item in mediaLibrary" :key="item.id">
                            <div 
                                x-show="item.name.toLowerCase().includes(searchQuery)"
                                class="group relative rounded-xl border border-border-base bg-surface-1 overflow-hidden hover:border-signal-400 transition-colors cursor-pointer"
                                @click="addMedia(item.id)"
                            >
                                <div class="flex items-center p-2">
                                    <div class="w-12 h-12 rounded-lg bg-surface-2 overflow-hidden shrink-0">
                                        <template x-if="item.type === 'image'">
                                            <img :src="'/storage/' + item.file_path" class="w-full h-full object-cover" />
                                        </template>
                                        <template x-if="item.type !== 'image'">
                                            <div class="w-full h-full flex items-center justify-center">
                                                <x-heroicon-o-film class="w-5 h-5 text-text-tertiary" />
                                            </div>
                                        </template>
                                    </div>
                                    <div class="ml-3 flex-1 min-w-0">
                                        <p class="text-sm font-medium text-text-primary truncate" x-text="item.name"></p>
                                        <p class="text-xs text-text-tertiary uppercase" x-text="item.type"></p>
                                    </div>
                                    <div class="px-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="w-8 h-8 rounded-full bg-signal-500/10 text-signal-600 flex items-center justify-center">
                                            <x-heroicon-o-plus class="w-5 h-5" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div x-show="mediaLibrary.length === 0" class="text-center py-6 text-sm text-text-tertiary">
                            No media available. Upload media first.
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-border-subtle flex items-center justify-end space-x-4">
            <x-ui.button type="button" variant="outline" href="{{ route('app.playlists.index') }}" wire:navigate>
                Cancel
            </x-ui.button>
            <x-ui.button type="submit" x-bind:disabled="selectedItems.length === 0">
                <div wire:loading wire:target="save" class="mr-2 h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                <span wire:loading.remove wire:target="save">Create Playlist</span>
                <span wire:loading wire:target="save">Creating...</span>
            </x-ui.button>
        </div>
    </form>
</div>
