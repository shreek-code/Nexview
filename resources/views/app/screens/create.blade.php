<x-layouts.app title="Provision Screen">
    <div class="mb-8">
        <div class="flex items-center space-x-3 text-sm text-text-tertiary mb-2">
            <a href="{{ route('app.screens.index') }}" class="hover:text-text-primary transition-colors">Screens</a>
            <x-heroicon-o-chevron-right class="w-4 h-4" />
            <span class="text-text-primary">Provision</span>
        </div>
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Provision New Screen</h1>
    </div>

    <div class="max-w-2xl">
        <x-ui.card class="p-6">
            <form method="POST" action="{{ route('app.screens.store') }}">
                @csrf

                <div class="space-y-6">
                    <div>
                        <x-ui.label for="name" value="Screen Name" />
                        <x-ui.input id="name" type="text" name="name" class="mt-1" :value="old('name')" placeholder="e.g. Lobby Main Display" required autofocus />
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-ui.label for="location_id" value="Location" />
                        <select id="location_id" name="location_id" class="flex h-10 w-full rounded-md border border-border-base bg-surface-1 px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-signal-500 focus-visible:border-signal-500 mt-1" required>
                            <option value="">Select a location...</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                        @if($locations->isEmpty())
                            <p class="text-sm text-amber-600 mt-2 flex items-center">
                                <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1" />
                                You need to create a location first. <a href="{{ route('app.locations.create') }}" class="underline ml-1 hover:text-amber-700">Add Location</a>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-border-subtle flex items-center justify-end space-x-4">
                    <x-ui.button type="button" variant="outline" onclick="window.history.back()">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" :disabled="$locations->isEmpty()">
                        Provision Screen
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
        
        <div class="mt-6 bg-surface-2 rounded-xl p-5 border border-border-subtle flex items-start space-x-4">
            <div class="w-10 h-10 rounded-full bg-signal-500/10 text-signal-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-information-circle class="w-5 h-5" />
            </div>
            <div>
                <h4 class="text-sm font-semibold text-text-primary">What happens next?</h4>
                <p class="text-sm text-text-secondary mt-1">
                    After provisioning, you will receive a 6-character pairing code. Enter this code into the NexView Player app on your Android or Android TV device to securely link it to this account.
                </p>
            </div>
        </div>
    </div>
</x-layouts.app>
