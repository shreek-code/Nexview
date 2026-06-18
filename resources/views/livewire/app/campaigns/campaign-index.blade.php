<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Campaigns</h1>
            <p class="text-text-secondary mt-1">Schedule and deploy playlists to your screens.</p>
        </div>
        <div class="flex items-center space-x-3">
            <x-ui.button :href="route('app.campaigns.create')" wire:navigate>
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                New Campaign
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

    <div class="bg-surface-1 border border-border-subtle rounded-xl overflow-hidden shadow-sm">
        @if($campaigns->isEmpty())
            <div class="py-16 flex flex-col items-center justify-center text-center px-4">
                <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-text-tertiary mb-4">
                    <x-heroicon-o-megaphone class="w-8 h-8" />
                </div>
                <h3 class="text-lg font-medium text-text-primary mb-1">No campaigns found</h3>
                <p class="text-text-secondary max-w-sm mb-6">Create a campaign to deploy playlists to your screens across locations.</p>
                <x-ui.button :href="route('app.campaigns.create')" wire:navigate>
                    <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                    New Campaign
                </x-ui.button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-surface-2 text-text-tertiary border-b border-border-subtle">
                        <tr>
                            <th class="px-6 py-4 font-medium">Campaign</th>
                            <th class="px-6 py-4 font-medium">Target</th>
                            <th class="px-6 py-4 font-medium">Playlist</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle">
                        @foreach($campaigns as $campaign)
                            <tr class="hover:bg-surface-2/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-text-primary">{{ $campaign->name }}</div>
                                    <div class="text-xs text-text-tertiary mt-1">Created {{ $campaign->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($campaign->target_type === 'location')
                                        <div class="flex items-center text-text-secondary">
                                            <x-heroicon-o-map-pin class="w-4 h-4 mr-1.5 text-text-tertiary" />
                                            {{ $campaign->targetLocation ? $campaign->targetLocation->name : 'Multiple Locations' }}
                                        </div>
                                    @else
                                        <div class="flex items-center text-text-secondary">
                                            <x-heroicon-o-tv class="w-4 h-4 mr-1.5 text-text-tertiary" />
                                            Specific Screens
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded bg-surface-3 flex items-center justify-center mr-3 shrink-0">
                                            <x-heroicon-o-queue-list class="w-4 h-4 text-text-tertiary" />
                                        </div>
                                        <span class="text-text-primary font-medium truncate max-w-[150px]" title="{{ $campaign->playlist->name }}">
                                            {{ $campaign->playlist->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <x-ui.badge :variant="$campaign->status === 'active' ? 'online' : 'offline'" :showDot="true">
                                        {{ ucfirst($campaign->status) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <x-ui.dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button class="text-text-tertiary hover:text-text-primary p-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">
                                                <x-heroicon-o-ellipsis-vertical class="w-5 h-5" />
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-ui.dropdown-link :href="route('app.campaigns.edit', $campaign)" wire:navigate>
                                                Edit Campaign
                                            </x-ui.dropdown-link>
                                            <x-ui.delete-action as="dropdown-link" action="delete('{{ $campaign->uuid }}')" confirmText="Are you sure you want to delete this campaign? Screens will fall back to their default media.">
                                                Delete Campaign
                                            </x-ui.delete-action>
                                        </x-slot>
                                    </x-ui.dropdown>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
