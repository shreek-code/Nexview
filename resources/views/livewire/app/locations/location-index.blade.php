<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Locations</h1>
            <p class="text-text-secondary mt-1">Manage physical locations for your screens.</p>
        </div>
        <x-ui.button :href="route('app.locations.create')">
            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
            Add Location
        </x-ui.button>
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
        @forelse($locations as $location)
            <x-ui.card class="overflow-hidden hover:shadow-md transition-shadow group">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center text-text-tertiary group-hover:bg-signal-500/10 group-hover:text-signal-600 transition-colors">
                                <x-heroicon-o-map-pin class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-text-primary">{{ $location->name }}</h3>
                                <p class="text-sm text-text-tertiary truncate max-w-[200px]">{{ $location->address ?? 'No address provided' }}</p>
                            </div>
                        </div>
                        <x-ui.dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="text-text-tertiary hover:text-text-primary p-1 rounded-md hover:bg-surface-2 transition-colors">
                                    <x-heroicon-o-ellipsis-vertical class="w-5 h-5" />
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-ui.dropdown-link :href="route('app.locations.edit', $location)">
                                    Edit
                                </x-ui.dropdown-link>
                                <x-ui.delete-action as="dropdown-link" action="delete('{{ $location->uuid }}')" confirmText="Are you sure you want to delete this location?">
                                    Delete
                                </x-ui.delete-action>
                            </x-slot>
                        </x-ui.dropdown>
                    </div>

                    <div class="mt-6 flex items-center justify-between text-sm text-text-secondary pt-4 border-t border-border-subtle">
                        <div class="flex items-center">
                            <x-heroicon-o-clock class="w-4 h-4 mr-1.5 text-text-tertiary" />
                            {{ $location->timezone }}
                        </div>
                        <div class="flex items-center">
                            <x-heroicon-o-computer-desktop class="w-4 h-4 mr-1.5 text-text-tertiary" />
                            {{ $location->screens()->count() }} Screens
                        </div>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <div class="col-span-full">
                <x-ui.card class="py-16 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-text-tertiary mb-4">
                        <x-heroicon-o-map-pin class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-medium text-text-primary mb-1">No locations found</h3>
                    <p class="text-text-secondary max-w-sm mb-6">Create your first location to start grouping your digital signage screens.</p>
                    <x-ui.button :href="route('app.locations.create')">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Add Location
                    </x-ui.button>
                </x-ui.card>
            </div>
        @endforelse
    </div>
</div>
