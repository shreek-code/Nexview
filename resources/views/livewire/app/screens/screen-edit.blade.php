<div>
    <div class="mb-8">
        <div class="flex items-center space-x-3 text-sm text-text-tertiary mb-2">
            <a href="{{ route('app.screens.index') }}" wire:navigate class="hover:text-text-primary transition-colors">Screens</a>
            <x-heroicon-o-chevron-right class="w-4 h-4" />
            <span class="text-text-primary">Edit</span>
        </div>
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Edit {{ $screen->name }}</h1>
    </div>

    <div class="max-w-2xl">
        <x-ui.card class="p-6 mb-6">
            <h2 class="text-lg font-semibold text-text-primary mb-4">Device Status</h2>
            <div class="bg-surface-2 rounded-xl p-4 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-text-tertiary mb-1">Status</p>
                    <x-ui.badge :variant="strtolower($screen->status) === 'online' ? 'online' : 'offline'" :showDot="true">
                        {{ $screen->status }}
                    </x-ui.badge>
                </div>
                <div>
                    <p class="text-sm text-text-tertiary mb-1">Pairing Code</p>
                    <p class="font-mono font-semibold {{ $screen->pairing_code ? 'text-signal-600' : 'text-text-primary' }}">
                        {{ $screen->pairing_code ?? 'PAIRED' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-text-tertiary mb-1">Last Seen</p>
                    <p class="text-sm text-text-primary">
                        {{ $screen->last_seen_at ? \Carbon\Carbon::parse($screen->last_seen_at)->diffForHumans() : 'Never' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-text-tertiary mb-1">Device ID</p>
                    <p class="text-xs font-mono text-text-secondary truncate" title="{{ $screen->device_id }}">
                        {{ $screen->device_id ?? 'Not registered' }}
                    </p>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <h2 class="text-lg font-semibold text-text-primary mb-4">Screen Settings</h2>
            <form wire:submit="save">
                <div class="space-y-6">
                    <div>
                        <x-ui.label for="name" value="Screen Name" />
                        <x-ui.input id="name" type="text" wire:model="name" class="mt-1" required autofocus />
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-ui.label for="location_id" value="Location" />
                        <div x-data="searchableSelect" wire:ignore>
                            <select x-ref="select" id="location_id" wire:model="location_id" class="flex h-10 w-full rounded-md border border-border-base bg-surface-1 px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-signal-500 focus-visible:border-signal-500 mt-1" required>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('location_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-border-subtle flex items-center justify-end space-x-4">
                    <x-ui.button type="button" variant="outline" href="{{ route('app.screens.index') }}" wire:navigate>
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit">
                        <div wire:loading wire:target="save" class="mr-2 h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                        <span wire:loading.remove wire:target="save">Save Changes</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</div>
