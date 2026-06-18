<x-layouts.app title="Playlists">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Playlists</h1>
            <p class="text-text-secondary mt-1">Group your media assets into reusable playlists.</p>
        </div>
        <div class="flex items-center space-x-3">
            <x-ui.button :href="route('app.playlists.create')">
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($playlists as $playlist)
            <x-ui.card class="overflow-hidden hover:shadow-md transition-shadow group flex flex-col h-full">
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center text-text-tertiary group-hover:bg-signal-500/10 group-hover:text-signal-600 transition-colors">
                                <x-heroicon-o-rectangle-stack class="w-5 h-5" />
                            </div>
                            <h3 class="text-lg font-semibold text-text-primary truncate max-w-[160px]" title="{{ $playlist->name }}">{{ $playlist->name }}</h3>
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
                                <form method="POST" action="{{ route('app.playlists.destroy', $playlist) }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.dropdown-link as="button" type="submit" class="text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20" onclick="return confirm('Are you sure you want to delete this playlist?');">
                                        Delete Playlist
                                    </x-ui.dropdown-link>
                                </form>
                            </x-slot>
                        </x-ui.dropdown>
                    </div>

                    @php
                        $items = collect($playlist->items);
                        $totalDuration = $items->sum('custom_duration');
                        $mediaCount = $items->count();
                    @endphp

                    <div class="bg-surface-2 rounded-lg p-3 text-sm mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-text-tertiary">Total Items</span>
                            <span class="font-medium text-text-primary">{{ $mediaCount }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-text-tertiary">Duration</span>
                            <span class="font-medium text-text-primary">{{ gmdate("H:i:s", $totalDuration) }}</span>
                        </div>
                    </div>

                    <div class="mt-auto space-y-2">
                        <p class="text-xs font-semibold text-text-tertiary uppercase tracking-wider mb-2">Preview Items</p>
                        <div class="flex -space-x-2 overflow-hidden px-1">
                            @foreach($items->take(5) as $item)
                                <div class="inline-block h-8 w-8 rounded-full ring-2 ring-surface-1 bg-surface-2 flex items-center justify-center overflow-hidden shrink-0">
                                    @if($item->mediaAsset && $item->mediaAsset->type === 'image')
                                        <img src="{{ Storage::url($item->mediaAsset->file_path) }}" class="w-full h-full object-cover" />
                                    @else
                                        <x-heroicon-o-film class="w-4 h-4 text-text-tertiary" />
                                    @endif
                                </div>
                            @endforeach
                            @if($mediaCount > 5)
                                <div class="inline-block h-8 w-8 rounded-full ring-2 ring-surface-1 bg-surface-3 flex items-center justify-center shrink-0">
                                    <span class="text-[10px] font-bold text-text-secondary">+{{ $mediaCount - 5 }}</span>
                                </div>
                            @endif
                            @if($mediaCount === 0)
                                <span class="text-xs text-text-tertiary">No items</span>
                            @endif
                        </div>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <div class="col-span-full">
                <x-ui.card class="py-16 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-text-tertiary mb-4">
                        <x-heroicon-o-rectangle-stack class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-medium text-text-primary mb-1">No playlists found</h3>
                    <p class="text-text-secondary max-w-sm mb-6">Group your uploaded media into playlists to schedule them on your screens.</p>
                    <x-ui.button :href="route('app.playlists.create')">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Create Playlist
                    </x-ui.button>
                </x-ui.card>
            </div>
        @endforelse
    </div>
</x-layouts.app>
