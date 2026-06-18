<div>
    <div class="mb-8">
        <div class="flex items-center space-x-3 text-sm text-text-tertiary mb-2">
            <a href="{{ route('app.campaigns.index') }}" wire:navigate class="hover:text-text-primary transition-colors">Campaigns</a>
            <x-heroicon-o-chevron-right class="w-4 h-4" />
            <span class="text-text-primary">Create</span>
        </div>
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Create New Campaign</h1>
    </div>

    <form wire:submit="save" x-data="{
        targetType: @entangle('target_type'),
        contentType: @entangle('content_type'),
        
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
        }
    }">
        <div class="max-w-3xl space-y-6">
            <!-- Campaign Basics -->
            <x-ui.card class="p-6">
                <h2 class="text-lg font-semibold text-text-primary mb-4 border-b border-border-subtle pb-3">Campaign Details</h2>
                
                <div>
                    <x-ui.label for="name" value="Campaign Name" />
                    <x-ui.input id="name" type="text" wire:model="name" class="mt-1" placeholder="e.g. Summer Promo 2024" required autofocus />
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </x-ui.card>

            <!-- Target Selection -->
            <x-ui.card class="p-6">
                <h2 class="text-lg font-semibold text-text-primary mb-4 border-b border-border-subtle pb-3">Where should this play?</h2>
                
                <div class="space-y-6">
                    <!-- Target Type Toggle -->
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" wire:model.live="target_type" value="location" class="peer sr-only">
                            <div class="rounded-xl border-2 p-4 transition-all hover:bg-surface-2 peer-checked:border-signal-500 peer-checked:bg-signal-500/5">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="p-2 rounded-lg bg-surface-2 peer-checked:bg-signal-500/10 peer-checked:text-signal-600">
                                        <x-heroicon-o-building-office class="w-6 h-6" />
                                    </div>
                                    <span class="font-semibold text-text-primary">Locations</span>
                                </div>
                                <p class="text-sm text-text-secondary">Deploy to all screens in selected branches.</p>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" wire:model.live="target_type" value="screens" class="peer sr-only">
                            <div class="rounded-xl border-2 p-4 transition-all hover:bg-surface-2 peer-checked:border-signal-500 peer-checked:bg-signal-500/5">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="p-2 rounded-lg bg-surface-2 peer-checked:bg-signal-500/10 peer-checked:text-signal-600">
                                        <x-heroicon-o-computer-desktop class="w-6 h-6" />
                                    </div>
                                    <span class="font-semibold text-text-primary">Specific Screens</span>
                                </div>
                                <p class="text-sm text-text-secondary">Deploy to individually selected displays.</p>
                            </div>
                        </label>
                    </div>

                    <!-- Location Selector -->
                    <div x-show="targetType === 'location'" x-collapse>
                        <x-ui.label for="location_ids" value="Select Locations" />
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-1">
                            @forelse($locations as $location)
                                <label class="flex items-center space-x-3 p-3 border border-border-subtle rounded-lg hover:bg-surface-2 cursor-pointer transition-colors">
                                    <input type="checkbox" wire:model="location_ids" value="{{ $location->id }}" class="rounded border-border-base text-signal-600 focus:ring-signal-500">
                                    <span class="text-sm font-medium text-text-primary">{{ $location->name }}</span>
                                </label>
                            @empty
                                <div class="col-span-full text-sm text-text-secondary bg-surface-2 p-3 rounded-lg text-center">
                                    No locations available.
                                </div>
                            @endforelse
                        </div>
                        @error('location_ids')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Screen Selector -->
                    <div x-show="targetType === 'screens'" x-collapse style="display: none;">
                        <x-ui.label for="screen_ids" value="Select Screens" />
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-1">
                            @forelse($screens as $screen)
                                <label class="flex items-start space-x-3 p-3 border border-border-subtle rounded-lg hover:bg-surface-2 cursor-pointer transition-colors">
                                    <input type="checkbox" wire:model="screen_ids" value="{{ $screen->id }}" class="rounded border-border-base text-signal-600 focus:ring-signal-500 mt-1">
                                    <div>
                                        <span class="text-sm font-medium text-text-primary block">{{ $screen->name }}</span>
                                        <span class="text-xs text-text-tertiary">{{ $screen->location->name ?? 'No Location' }}</span>
                                    </div>
                                </label>
                            @empty
                                <div class="col-span-full text-sm text-text-secondary bg-surface-2 p-3 rounded-lg text-center">
                                    No screens available.
                                </div>
                            @endforelse
                        </div>
                        @error('screen_ids')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>

            <!-- Content Selection -->
            <x-ui.card class="p-6">
                <h2 class="text-lg font-semibold text-text-primary mb-4 border-b border-border-subtle pb-3">What should play?</h2>
                
                <div class="space-y-6">
                    <!-- Content Type Toggle -->
                    <div class="flex p-1 space-x-1 bg-surface-2 rounded-xl">
                        <button type="button" @click="contentType = 'existing_playlist'" class="w-full rounded-lg py-2.5 text-sm font-medium leading-5 transition-colors focus:outline-none focus:ring-2 focus:ring-signal-500 focus:ring-offset-2" :class="contentType === 'existing_playlist' ? 'bg-surface-1 text-signal-600 shadow' : 'text-text-secondary hover:text-text-primary hover:bg-surface-1/50'">
                            Existing Playlist
                        </button>
                        <button type="button" @click="contentType = 'new_playlist'" class="w-full rounded-lg py-2.5 text-sm font-medium leading-5 transition-colors focus:outline-none focus:ring-2 focus:ring-signal-500 focus:ring-offset-2" :class="contentType === 'new_playlist' ? 'bg-surface-1 text-signal-600 shadow' : 'text-text-secondary hover:text-text-primary hover:bg-surface-1/50'">
                            Create New On-The-Fly
                        </button>
                    </div>

                    <!-- Existing Playlist Selector -->
                    <div x-show="contentType === 'existing_playlist'" x-collapse>
                        <x-ui.label for="playlist_id" value="Select Playlist" />
                        <div x-data="searchableSelect" wire:ignore>
                            <select x-ref="select" id="playlist_id" wire:model="playlist_id" class="flex h-10 w-full rounded-md border border-border-base bg-surface-1 px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-signal-500 focus-visible:border-signal-500 mt-1" x-bind:required="contentType === 'existing_playlist'">
                                <option value="">Select a playlist...</option>
                                @foreach($playlists as $playlist)
                                    <option value="{{ $playlist->id }}">
                                        {{ $playlist->name }} ({{ $playlist->items->count() }} items)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('playlist_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Playlist Builder -->
                    <div x-show="contentType === 'new_playlist'" x-collapse style="display: none;">
                        <p class="text-sm text-text-secondary mb-4">A new playlist will be created and named after this campaign.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Selected Media (Left side) -->
                            <div class="bg-surface-1 border border-border-subtle rounded-xl p-4 min-h-[300px]">
                                <h3 class="text-sm font-semibold text-text-primary mb-3">Selected Items</h3>
                                
                                <div class="space-y-2" x-show="selectedItems.length > 0">
                                    <template x-for="(item, index) in selectedItems" :key="item.uid">
                                        <div class="flex items-center p-2 bg-surface-2 rounded-lg border border-border-base">
                                            <div class="w-10 h-10 rounded bg-surface-3 overflow-hidden shrink-0 mr-3">
                                                <template x-if="item.type === 'image'">
                                                    <img :src="'/storage/' + item.file_path" class="w-full h-full object-cover" />
                                                </template>
                                                <template x-if="item.type !== 'image'">
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <x-heroicon-o-film class="w-5 h-5 text-text-tertiary" />
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-medium text-text-primary truncate" x-text="item.name"></p>
                                                <div class="flex items-center mt-1 space-x-2">
                                                    <input type="number" x-model.number="item.duration" min="1" class="w-16 h-6 rounded border border-border-base bg-surface-1 px-1 py-0.5 text-xs text-center">
                                                    <span class="text-[10px] text-text-tertiary">sec</span>
                                                </div>
                                            </div>
                                            <div class="ml-2 flex flex-col space-y-1">
                                                <button type="button" @click="moveItem(index, 'up')" :disabled="index === 0" class="text-text-tertiary hover:text-text-primary disabled:opacity-30">
                                                    <x-heroicon-s-chevron-up class="w-3 h-3" />
                                                </button>
                                                <button type="button" @click="moveItem(index, 'down')" :disabled="index === selectedItems.length - 1" class="text-text-tertiary hover:text-text-primary disabled:opacity-30">
                                                    <x-heroicon-s-chevron-down class="w-3 h-3" />
                                                </button>
                                            </div>
                                            <button type="button" @click="removeMedia(item.uid)" class="ml-2 p-1 text-text-tertiary hover:text-red-500 transition-colors">
                                                <x-heroicon-o-x-mark class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <div x-show="selectedItems.length === 0" class="h-full flex items-center justify-center text-sm text-text-tertiary italic p-6 text-center">
                                    Click items in the media library to add them here.
                                </div>
                            </div>

                            <!-- Media Library (Right side) -->
                            <div class="bg-surface-2 rounded-xl p-4 max-h-[400px] overflow-y-auto">
                                <h3 class="text-sm font-semibold text-text-primary mb-3">Media Library</h3>
                                <div class="space-y-2">
                                    <template x-for="item in mediaLibrary" :key="item.id">
                                        <div class="flex items-center p-2 rounded-lg bg-surface-1 border border-border-base cursor-pointer hover:border-signal-400 group" @click="addMedia(item.id)">
                                            <div class="w-8 h-8 rounded overflow-hidden shrink-0 bg-surface-3">
                                                <template x-if="item.type === 'image'">
                                                    <img :src="'/storage/' + item.file_path" class="w-full h-full object-cover" />
                                                </template>
                                            </div>
                                            <div class="ml-2 flex-1 min-w-0">
                                                <p class="text-xs font-medium text-text-primary truncate" x-text="item.name"></p>
                                            </div>
                                            <x-heroicon-o-plus class="w-4 h-4 text-text-tertiary group-hover:text-signal-500 opacity-0 group-hover:opacity-100" />
                                        </div>
                                    </template>
                                    <div x-show="mediaLibrary.length === 0" class="text-xs text-text-tertiary">
                                        No media available.
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('selectedMedia')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>
            
            <div class="pt-6 border-t border-border-subtle flex items-center justify-end space-x-4">
                <x-ui.button type="button" variant="outline" href="{{ route('app.campaigns.index') }}" wire:navigate>
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit">
                    <div wire:loading wire:target="save" class="mr-2 h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                    <span wire:loading.remove wire:target="save">Deploy Campaign</span>
                    <span wire:loading wire:target="save">Deploying...</span>
                </x-ui.button>
            </div>
        </div>
    </form>
</div>
