<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Playlists</h1>
            <p class="text-text-secondary mt-1">Combine media assets into sequences for your screens.</p>
        </div>
        <div class="flex items-center space-x-3">
            <x-ui.button :href="route('app.playlists.create')" wire:navigate>
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Create Playlist
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($playlists as $playlist)
            <x-ui.card class="overflow-hidden hover:shadow-md transition-shadow group flex flex-col h-full">
                <!-- Thumbnail Preview -->
                <div class="aspect-[21/9] bg-surface-2 relative overflow-hidden border-b border-border-subtle flex p-2 gap-1">
                    @php
                        $items = $playlist->items->take(4);
                        $totalItems = $playlist->items->count();
                    @endphp
                    
                    @if($items->isEmpty())
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-text-tertiary">
                            <x-heroicon-o-film class="w-8 h-8 mb-2" />
                            <span class="text-xs uppercase font-medium tracking-wider">Empty</span>
                        </div>
                    @else
                        @foreach($items as $index => $item)
                            <div class="relative flex-1 rounded-md overflow-hidden bg-surface-3">
                                @if($item->mediaAsset)
                                    @if($item->mediaAsset->type === 'image')
                                        <img src="{{ Storage::url($item->mediaAsset->file_path) }}" class="w-full h-full object-cover" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-900">
                                            <x-heroicon-o-film class="w-6 h-6 text-gray-500" />
                                        </div>
                                    @endif
                                @endif
                                
                                @if($index === 3 && $totalItems > 4)
                                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center backdrop-blur-sm">
                                        <span class="text-white text-xs font-bold">+{{ $totalItems - 3 }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-text-primary truncate max-w-[200px]" title="{{ $playlist->name }}">{{ $playlist->name }}</h3>
                            <p class="text-sm text-text-tertiary mt-0.5 flex items-center">
                                <x-heroicon-o-square-3-stack-3d class="w-3.5 h-3.5 mr-1" />
                                {{ $playlist->items->count() }} items
                            </p>
                        </div>
                        <x-ui.dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="text-text-tertiary hover:text-text-primary p-1 rounded-md hover:bg-surface-2 transition-colors">
                                    <x-heroicon-o-ellipsis-vertical class="w-5 h-5" />
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-ui.dropdown-link :href="route('app.playlists.edit', $playlist)">
                                    Edit Playlist
                                </x-ui.dropdown-link>
                                <x-ui.delete-action as="dropdown-link" action="delete('{{ $playlist->uuid }}')" confirmText="Are you sure you want to delete this playlist? Campaigns using it may stop working properly.">
                                    Delete Playlist
                                </x-ui.delete-action>
                            </x-slot>
                        </x-ui.dropdown>
                    </div>

                    <div class="mt-auto pt-4 border-t border-border-subtle flex justify-between items-center text-sm">
                        <span class="text-text-secondary">Duration</span>
                        <span class="font-mono text-text-primary font-medium">
                            @php
                                $totalDuration = $playlist->items->sum('custom_duration');
                                $mins = floor($totalDuration / 60);
                                $secs = $totalDuration % 60;
                            @endphp
                            {{ sprintf('%02d:%02d', $mins, $secs) }}
                        </span>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <div class="col-span-full">
                <x-ui.card class="py-16 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-text-tertiary mb-4">
                        <x-heroicon-o-queue-list class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-medium text-text-primary mb-1">No playlists found</h3>
                    <p class="text-text-secondary max-w-sm mb-6">Create a playlist to group media assets into a continuous sequence.</p>
                    <x-ui.button :href="route('app.playlists.create')">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Create Playlist
                    </x-ui.button>
                </x-ui.card>
            </div>
        @endforelse
    </div>
</div>
