<x-layouts.app title="Edit Screen">
    <div class="mb-8">
        <div class="flex items-center space-x-3 text-sm text-text-tertiary mb-2">
            <a href="{{ route('app.screens.index') }}" class="hover:text-text-primary transition-colors">Screens</a>
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
            <form method="POST" action="{{ route('app.screens.update', $screen) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <x-ui.label for="name" value="Screen Name" />
                        <x-ui.input id="name" type="text" name="name" class="mt-1" :value="old('name', $screen->name)" required autofocus />
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-ui.label for="location_id" value="Location" />
                        <select id="location_id" name="location_id" class="flex h-10 w-full rounded-md border border-border-base bg-surface-1 px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-signal-500 focus-visible:border-signal-500 mt-1" required>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id', $screen->location_id) == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-border-subtle flex items-center justify-end space-x-4">
                    <x-ui.button type="button" variant="outline" onclick="window.history.back()">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit">
                        Save Changes
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-layouts.app>
