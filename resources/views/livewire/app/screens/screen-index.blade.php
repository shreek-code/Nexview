<div wire:poll.10s>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Screens</h1>
            <p class="text-text-secondary mt-1">Manage your digital signage displays and monitor their status.</p>
        </div>
        <div class="flex items-center space-x-3">
            <x-ui.button type="button" wire:click="openAddModal">
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Add Screen
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{
        selectedScreen: null,
        selectedMediaId: null,
        openModal(screen) {
            this.selectedScreen = screen;
            this.selectedMediaId = screen.default_media_id;
            this.$dispatch('open-modal', 'default-media-modal');
        },
        saveMedia() {
            @this.call('setDefaultMedia', this.selectedScreen.id, this.selectedMediaId);
            this.$dispatch('close-modal', 'default-media-modal');
        }
    }">
        @forelse($screens as $screen)
            <x-ui.card class="overflow-hidden hover:shadow-md transition-shadow group flex flex-col h-full">
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="relative w-12 h-12 rounded-xl bg-surface-2 flex items-center justify-center text-text-tertiary">
                                <x-heroicon-o-computer-desktop class="w-6 h-6" />
                                @if($screen->status === 'online')
                                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 border-2 border-surface-1 rounded-full bg-emerald-500"></span>
                                @else
                                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 border-2 border-surface-1 rounded-full bg-red-500"></span>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-text-primary truncate max-w-[150px]" title="{{ $screen->name }}">
                                    <a href="{{ route('app.screens.show', $screen) }}" wire:navigate class="hover:underline hover:text-signal-600 transition-colors">
                                        {{ $screen->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-text-tertiary flex items-center mt-0.5">
                                    <x-heroicon-o-map-pin class="w-3.5 h-3.5 mr-1" />
                                    {{ $screen->location->name ?? 'Unassigned' }}
                                </p>
                            </div>
                        </div>
                        <x-ui.dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="text-text-tertiary hover:text-text-primary p-1 rounded-md hover:bg-surface-2 transition-colors">
                                    <x-heroicon-o-ellipsis-vertical class="w-5 h-5" />
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-ui.dropdown-link :href="route('app.screens.show', $screen)" wire:navigate>
                                    Control Screen
                                </x-ui.dropdown-link>
                                <x-ui.dropdown-link :href="route('app.screens.edit', $screen)">
                                    Edit Settings
                                </x-ui.dropdown-link>
                                <x-ui.delete-action as="dropdown-link" action="delete('{{ $screen->uuid }}')" confirmText="Are you sure you want to delete this screen?">
                                    Delete Screen
                                </x-ui.delete-action>
                            </x-slot>
                        </x-ui.dropdown>
                    </div>

                    <div class="mt-auto space-y-4">
                        <div class="bg-surface-2 rounded-lg p-3 text-sm">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-text-tertiary">Device ID</span>
                                <span class="font-mono font-medium tracking-widest text-text-secondary">
                                    @if($screen->device_id)
                                        {{ $screen->device_id }}
                                    @else
                                        <span class="text-amber-500 animate-pulse text-xs uppercase flex items-center">
                                            <x-heroicon-o-clock class="w-3.5 h-3.5 mr-1" />
                                            Pending...
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-text-tertiary">Last Seen</span>
                                <span class="text-text-secondary">
                                    {{ $screen->last_seen_at ? \Carbon\Carbon::parse($screen->last_seen_at)->diffForHumans() : 'Never' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                            @if($screen->default_media_id)
                                @php
                                    $mediaAsset = collect($media)->firstWhere('id', $screen->default_media_id);
                                @endphp
                                <div class="flex items-center text-sm text-text-secondary bg-surface-2 p-2 rounded-lg">
                                    <x-heroicon-o-play-circle class="w-4 h-4 mr-2 text-signal-500" />
                                    <span class="truncate">Default: {{ $mediaAsset ? $mediaAsset['name'] : 'Unknown Media' }}</span>
                                </div>
                            @endif
                            <div class="grid grid-cols-2 gap-2">
                                <x-ui.button variant="outline" type="button" class="justify-center" @click="openModal({{ json_encode($screen) }})">
                                    <x-heroicon-o-photo class="w-4 h-4 mr-1.5" />
                                    Media
                                </x-ui.button>
                                <x-ui.button variant="outline" type="button" class="justify-center" href="{{ route('app.screens.show', $screen) }}" wire:navigate>
                                    <x-heroicon-o-adjustments-horizontal class="w-4 h-4 mr-1.5" />
                                    Control
                                </x-ui.button>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <div class="col-span-full">
                <x-ui.card class="py-16 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-text-tertiary mb-4">
                        <x-heroicon-o-computer-desktop class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-medium text-text-primary mb-1">No screens found</h3>
                    <p class="text-text-secondary max-w-sm mb-6">Connect your first digital signage display to start showing content.</p>
                    <x-ui.button type="button" wire:click="openAddModal">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Connect Screen
                    </x-ui.button>
                </x-ui.card>
            </div>
        @endforelse

        <!-- Connect Screen Modal -->
        <x-ui.modal name="add-screen-modal" :show="$showAddModal">
            <form wire:submit.prevent="connectScreen" class="p-6 relative">
                <!-- Loader Overlay -->
                <div wire:loading.flex wire:target="connectScreen" class="absolute inset-0 z-10 bg-surface-1/90 backdrop-blur-sm flex-col items-center justify-center rounded-xl">
                    <div class="w-12 h-12 border-4 border-signal-500/30 border-t-signal-500 rounded-full animate-spin mb-4"></div>
                    <p class="text-lg font-medium text-text-primary">Connecting...</p>
                    <p class="text-sm text-text-secondary mt-1">Pairing screen...</p>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-text-primary">Connect New Screen</h2>
                    <button type="button" @click="$dispatch('close-modal', 'add-screen-modal')" class="text-text-tertiary hover:text-text-primary">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>
                
                @error('plan')
                    <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl flex items-start">
                        <x-heroicon-o-exclamation-circle class="w-5 h-5 mr-3 mt-0.5" />
                        <div class="flex-1">
                            <p class="font-medium">Plan Limit Reached</p>
                            <p class="text-sm mt-1">{{ $message }}</p>
                        </div>
                    </div>
                @enderror

                <p class="text-sm text-text-secondary mb-6">
                    Enter the 6-digit code displayed on your Player App to connect it to your organization.
                </p>

                <div class="mb-4">
                    <x-ui.label for="registration_code" value="Registration Code" />
                    <x-ui.input id="registration_code" type="text" class="block w-full mt-1 font-mono uppercase tracking-widest text-lg" wire:model="registration_code" placeholder="e.g. A1B2C3" autofocus maxlength="6" />
                    <x-ui.input-error for="registration_code" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-ui.label for="name" value="Screen Name" />
                    <x-ui.input id="name" type="text" class="block w-full mt-1" wire:model="name" placeholder="e.g. Front Lobby Display" />
                    <x-ui.input-error for="name" class="mt-2" />
                </div>

                <div class="mb-6" wire:ignore>
                    <x-ui.label for="location_id" value="Location" />
                    <div x-data="searchableSelect({ placeholder: 'Select a location...' })" class="mt-1">
                        <select id="location_id" wire:model="location_id" x-ref="select" class="hidden">
                            <option value="">Select a location...</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-ui.input-error for="location_id" class="mt-2" />
                </div>

                <div class="flex justify-end space-x-3">
                    <x-ui.button type="button" variant="outline" @click="$dispatch('close-modal', 'add-screen-modal')">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="connectScreen">Connect Device</span>
                        <span wire:loading wire:target="connectScreen">Connecting...</span>
                    </x-ui.button>
                </div>
            </form>
        </x-ui.modal>

        <!-- Default Media Modal -->
        <x-ui.modal name="default-media-modal">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-text-primary">
                        Set Default Media for <span x-text="selectedScreen?.name" class="font-bold"></span>
                    </h2>
                    <button @click="$dispatch('close-modal', 'default-media-modal')" class="text-text-tertiary hover:text-text-primary">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>
                
                <p class="text-sm text-text-secondary mb-6">
                    Default media is shown when there is no active campaign running on this screen.
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6 max-h-[60vh] overflow-y-auto p-1">
                    <!-- Option for No Default Media -->
                    <div 
                        @click="selectedMediaId = null"
                        class="relative rounded-xl border-2 cursor-pointer overflow-hidden aspect-video flex flex-col items-center justify-center transition-all bg-surface-2"
                        :class="selectedMediaId === null ? 'border-signal-500' : 'border-transparent hover:border-border-subtle'"
                    >
                        <x-heroicon-o-no-symbol class="w-8 h-8 text-text-tertiary mb-2" />
                        <span class="text-xs font-medium text-text-secondary">None</span>
                        <div x-show="selectedMediaId === null" class="absolute top-2 right-2 bg-signal-500 rounded-full p-0.5">
                            <x-heroicon-s-check class="w-3 h-3 text-white" />
                        </div>
                    </div>

                    @foreach($media as $asset)
                        <div 
                            @click="selectedMediaId = {{ $asset->id }}"
                            class="relative rounded-xl border-2 cursor-pointer overflow-hidden aspect-video transition-all bg-surface-2 group"
                            :class="selectedMediaId === {{ $asset->id }} ? 'border-signal-500' : 'border-transparent hover:border-border-subtle'"
                        >
                            @if($asset->type === 'image')
                                <img src="{{ Storage::url($asset->file_path) }}" class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-900">
                                    <x-heroicon-o-film class="w-8 h-8 text-gray-500" />
                                </div>
                            @endif
                            <div class="absolute inset-x-0 bottom-0 bg-black/60 p-2 backdrop-blur-sm">
                                <p class="text-xs text-white truncate">{{ $asset->name }}</p>
                            </div>
                            <div x-show="selectedMediaId === {{ $asset->id }}" class="absolute top-2 right-2 bg-signal-500 rounded-full p-0.5">
                                <x-heroicon-s-check class="w-3 h-3 text-white" />
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end space-x-3">
                    <x-ui.button type="button" variant="outline" @click="$dispatch('close-modal', 'default-media-modal')">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="button" @click="saveMedia">
                        Save Changes
                    </x-ui.button>
                </div>
            </div>
        </x-ui.modal>
    </div>
</div>
