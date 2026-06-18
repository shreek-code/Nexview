<x-layouts.app title="Campaigns">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Campaigns</h1>
            <p class="text-text-secondary mt-1">Manage active content distribution across your network.</p>
        </div>
        <div class="flex items-center space-x-3">
            <x-ui.button :href="route('app.campaigns.create')">
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Create Campaign
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
        @forelse($campaigns as $campaign)
            <x-ui.card class="overflow-hidden hover:shadow-md transition-shadow group flex flex-col h-full">
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="relative w-12 h-12 rounded-xl bg-surface-2 flex items-center justify-center text-text-tertiary">
                                <x-heroicon-o-megaphone class="w-6 h-6" />
                                @if($campaign->status === 'active')
                                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 border-2 border-surface-1 rounded-full bg-emerald-500"></span>
                                @else
                                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 border-2 border-surface-1 rounded-full bg-surface-3"></span>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-text-primary truncate max-w-[150px]" title="{{ $campaign->name }}">{{ $campaign->name }}</h3>
                                <div class="flex items-center mt-1">
                                    <x-ui.badge :variant="$campaign->status === 'active' ? 'online' : 'offline'" size="sm">
                                        {{ ucfirst($campaign->status) }}
                                    </x-ui.badge>
                                </div>
                            </div>
                        </div>
                        <x-ui.dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="text-text-tertiary hover:text-text-primary p-1 rounded-md hover:bg-surface-2 transition-colors">
                                    <x-heroicon-o-ellipsis-vertical class="w-5 h-5" />
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-ui.dropdown-link :href="route('app.campaigns.edit', $campaign)">
                                    Edit Campaign
                                </x-ui.dropdown-link>
                                <form method="POST" action="{{ route('app.campaigns.destroy', $campaign) }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.dropdown-link as="button" type="submit" class="text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20" onclick="return confirm('Are you sure you want to delete this campaign?');">
                                        Delete Campaign
                                    </x-ui.dropdown-link>
                                </form>
                            </x-slot>
                        </x-ui.dropdown>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div class="bg-surface-2 rounded-lg p-3 text-sm">
                            <div class="flex justify-between items-center mb-2 border-b border-border-subtle pb-2">
                                <span class="text-text-tertiary flex items-center">
                                    <x-heroicon-o-play-circle class="w-4 h-4 mr-1.5" />
                                    Source
                                </span>
                                <span class="font-medium text-text-primary truncate max-w-[120px]" title="{{ optional($campaign->playlist)->name }}">
                                    {{ optional($campaign->playlist)->name ?? 'Unknown Playlist' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-text-tertiary flex items-center">
                                    <x-heroicon-o-map-pin class="w-4 h-4 mr-1.5" />
                                    Target
                                </span>
                                <span class="font-medium text-text-primary">
                                    @if($campaign->target_type === 'location')
                                        Location: {{ optional($campaign->targetLocation)->name ?? 'All Locations' }}
                                    @else
                                        Specific Screens
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-sm">
                            <span class="text-text-tertiary">Priority</span>
                            <span class="text-text-secondary bg-surface-2 px-2 py-0.5 rounded-md font-medium">
                                Level {{ $campaign->priority }}
                            </span>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <div class="col-span-full">
                <x-ui.card class="py-16 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-text-tertiary mb-4">
                        <x-heroicon-o-megaphone class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-medium text-text-primary mb-1">No campaigns found</h3>
                    <p class="text-text-secondary max-w-sm mb-6">Create a campaign to deploy your playlists to specific screens or locations.</p>
                    <x-ui.button :href="route('app.campaigns.create')">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Create Campaign
                    </x-ui.button>
                </x-ui.card>
            </div>
        @endforelse
    </div>
</x-layouts.app>
