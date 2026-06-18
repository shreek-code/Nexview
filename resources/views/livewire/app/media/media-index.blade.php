<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Media Library</h1>
            <p class="text-text-secondary mt-1">Upload and manage images and videos for your screens.</p>
        </div>
        <div class="flex items-center space-x-3">
            <x-ui.button x-on:click="$dispatch('open-modal', 'upload-media-modal')">
                <x-heroicon-o-arrow-up-tray class="w-5 h-5 mr-2" />
                Upload Media
            </x-ui.button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 px-4 py-3 rounded-xl flex items-start">
            <x-heroicon-o-check-circle class="w-5 h-5 mr-3 mt-0.5" />
            <div>
                <p class="font-medium">Success</p>
                <p class="text-sm mt-1">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @error('file')
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl flex items-start">
            <x-heroicon-o-x-circle class="w-5 h-5 mr-3 mt-0.5" />
            <div>
                <p class="font-medium">Upload Error</p>
                <p class="text-sm mt-1">{{ $message }}</p>
            </div>
        </div>
    @enderror
    @error('plan')
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl flex items-start">
            <x-heroicon-o-x-circle class="w-5 h-5 mr-3 mt-0.5" />
            <div>
                <p class="font-medium">Storage Limit Reached</p>
                <p class="text-sm mt-1">{{ $message }}</p>
            </div>
        </div>
    @enderror

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @forelse($media as $asset)
            <x-ui.card class="overflow-hidden group hover:shadow-md transition-shadow">
                <div class="aspect-video relative bg-surface-2 overflow-hidden">
                    @if($asset->type === 'image')
                        <img src="{{ Storage::url($asset->file_path) }}" alt="{{ $asset->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-text-tertiary">
                            <x-heroicon-o-film class="w-10 h-10 mb-2" />
                            <span class="text-xs uppercase font-medium tracking-wider">Video</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                        <div class="flex gap-2">
                            <x-ui.delete-action action="delete('{{ $asset->uuid }}')" confirmText="Are you sure you want to delete this media asset?" class="w-10 h-10 rounded-full bg-red-500/90 text-white flex items-center justify-center hover:bg-red-600 hover:scale-110 transition-all shadow-lg" title="Delete">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </x-ui.delete-action>
                        </div>
                    </div>
                </div>
                <div class="p-3">
                    <h3 class="text-sm font-medium text-text-primary truncate" title="{{ $asset->name }}">{{ $asset->name }}</h3>
                    <div class="flex items-center justify-between mt-1 text-xs text-text-tertiary">
                        <span>{{ strtoupper(pathinfo($asset->file_path, PATHINFO_EXTENSION)) }}</span>
                        <span>{{ number_format($asset->size / 1048576, 2) }} MB</span>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <div class="col-span-full">
                <x-ui.card class="py-16 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-text-tertiary mb-4">
                        <x-heroicon-o-photo class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-medium text-text-primary mb-1">No media assets found</h3>
                    <p class="text-text-secondary max-w-sm mb-6">Upload images or videos to display on your digital signage screens.</p>
                    <div>
                        <x-ui.button x-on:click="$dispatch('open-modal', 'upload-media-modal')">
                            <x-heroicon-o-arrow-up-tray class="w-4 h-4 mr-2" />
                            Upload Media
                        </x-ui.button>
                    </div>
                </x-ui.card>
            </div>
        @endforelse
    </div>

    <!-- Upload Modal -->
    <x-ui.modal name="upload-media-modal" maxWidth="md">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-text-primary">
                    Upload Media
                </h2>
                <button x-on:click="$dispatch('close-modal', 'upload-media-modal')" class="text-text-tertiary hover:text-text-primary">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            </div>
            
            <form wire:submit="save" x-data="{
                isDragging: false,
                handleDrop(e) {
                    this.isDragging = false;
                    if(e.dataTransfer.files.length) {
                        @this.upload('file', e.dataTransfer.files[0], 
                            (uploadedFilename) => {}, 
                            () => {}, 
                            (event) => {}
                        );
                    }
                }
            }">
                <div 
                    class="mt-4 flex justify-center rounded-xl border-2 border-dashed px-6 py-12 transition-colors relative"
                    :class="isDragging ? 'border-signal-500 bg-signal-500/5' : 'border-border-base hover:border-text-tertiary'"
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop"
                >
                    <div class="text-center">
                        <x-heroicon-o-photo class="mx-auto h-12 w-12 text-text-tertiary" />
                        <div class="mt-4 flex text-sm leading-6 text-text-secondary justify-center">
                            <label for="file_upload" class="relative cursor-pointer rounded-md bg-transparent font-semibold text-signal-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-signal-500 focus-within:ring-offset-2 hover:text-signal-500">
                                <span x-show="!$wire.file">Upload a file</span>
                                <span x-show="$wire.file">Change file</span>
                                <input id="file_upload" wire:model="file" type="file" class="sr-only" accept="image/jpeg,image/png,image/gif,video/mp4,video/quicktime">
                            </label>
                            <p class="pl-1" x-show="!$wire.file">or drag and drop</p>
                        </div>
                        <p class="text-xs leading-5 text-text-tertiary" x-show="!$wire.file">PNG, JPG, GIF up to 10MB</p>
                        <p class="text-xs leading-5 text-text-tertiary" x-show="!$wire.file">MP4, MOV up to 100MB</p>
                        
                        <!-- Upload Progress and File Info -->
                        <div x-show="$wire.file" class="mt-4 text-sm font-medium text-text-primary bg-surface-2 py-2 px-4 rounded-full inline-block">
                            File selected
                        </div>
                        <div wire:loading wire:target="file" class="mt-2 text-sm text-signal-500">
                            Reading file...
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-ui.button type="button" variant="outline" x-on:click="$dispatch('close-modal', 'upload-media-modal')">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="file, save">
                        <div wire:loading wire:target="save" class="mr-2 h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                        <span wire:loading.remove wire:target="save">Upload</span>
                        <span wire:loading wire:target="save">Uploading...</span>
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.modal>
</div>
