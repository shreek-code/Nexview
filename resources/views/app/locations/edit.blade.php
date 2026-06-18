<x-layouts.app title="Edit Location">
    <div class="mb-8">
        <div class="flex items-center space-x-3 text-sm text-text-tertiary mb-2">
            <a href="{{ route('app.locations.index') }}" class="hover:text-text-primary transition-colors">Locations</a>
            <x-heroicon-o-chevron-right class="w-4 h-4" />
            <span class="text-text-primary">Edit</span>
        </div>
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Edit {{ $location->name }}</h1>
    </div>

    <div class="max-w-2xl">
        <x-ui.card class="p-6">
            <form method="POST" action="{{ route('app.locations.update', $location) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <x-ui.label for="name" value="Location Name" />
                        <x-ui.input id="name" type="text" name="name" class="mt-1" :value="old('name', $location->name)" required autofocus />
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-ui.label for="address" value="Address (Optional)" />
                        <textarea id="address" name="address" rows="3" class="flex w-full rounded-md border border-border-base bg-surface-1 px-3 py-2 text-sm placeholder:text-text-tertiary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-signal-500 focus-visible:border-signal-500 mt-1">{{ old('address', $location->address) }}</textarea>
                        @error('address')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-ui.label for="timezone" value="Timezone" />
                        <select id="timezone" name="timezone" class="flex h-10 w-full rounded-md border border-border-base bg-surface-1 px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-signal-500 focus-visible:border-signal-500 mt-1">
                            @foreach(timezone_identifiers_list() as $timezone)
                                <option value="{{ $timezone }}" {{ old('timezone', $location->timezone) === $timezone ? 'selected' : '' }}>
                                    {{ $timezone }}
                                </option>
                            @endforeach
                        </select>
                        @error('timezone')
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
