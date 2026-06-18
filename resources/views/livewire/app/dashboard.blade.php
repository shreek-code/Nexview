<div wire:poll.30s>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Dashboard Overview</h1>
        <p class="text-text-secondary mt-1">Manage your digital signage network and campaigns.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Screens Stat -->
        <x-ui.card class="p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-text-tertiary uppercase tracking-wider">Total Screens</p>
                    <h3 class="text-3xl font-bold text-text-primary mt-1">{{ $stats['total_screens'] }}</h3>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-signal-500/10 text-signal-600 flex items-center justify-center">
                    <x-heroicon-o-computer-desktop class="w-6 h-6" />
                </div>
            </div>
            <div class="flex items-center space-x-4 text-sm">
                <div class="flex items-center text-emerald-500">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    {{ $stats['online_screens'] }} Online
                </div>
                <div class="flex items-center text-red-500">
                    <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                    {{ $stats['offline_screens'] }} Offline
                </div>
                @if($stats['degraded_screens'] > 0)
                    <div class="flex items-center text-amber-500">
                        <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                        {{ $stats['degraded_screens'] }} Degraded
                    </div>
                @endif
            </div>
        </x-ui.card>

        <!-- Campaigns Stat -->
        <x-ui.card class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-text-tertiary uppercase tracking-wider">Active Campaigns</p>
                    <h3 class="text-3xl font-bold text-text-primary mt-1">{{ $stats['active_campaigns'] }}</h3>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                    <x-heroicon-o-calendar class="w-6 h-6" />
                </div>
            </div>
            <a href="{{ route('app.campaigns.index') }}" class="text-sm text-signal-600 hover:text-signal-700 font-medium inline-flex items-center">
                View all campaigns <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
            </a>
        </x-ui.card>

        <!-- Media Stat -->
        <x-ui.card class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-text-tertiary uppercase tracking-wider">Media Assets</p>
                    <h3 class="text-3xl font-bold text-text-primary mt-1">{{ $stats['media_assets'] }}</h3>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-blue-500/10 text-blue-600 flex items-center justify-center">
                    <x-heroicon-o-play class="w-6 h-6" />
                </div>
            </div>
            <a href="{{ route('app.media.index') }}" class="text-sm text-signal-600 hover:text-signal-700 font-medium inline-flex items-center">
                Manage library <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
            </a>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Live Feed -->
        <div class="lg:col-span-2">
            <x-ui.card class="overflow-hidden">
                <div class="px-6 py-5 border-b border-border-subtle flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-text-primary flex items-center">
                        <div class="relative flex h-3 w-3 mr-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-signal-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-signal-500"></span>
                        </div>
                        Live Screen Feed
                    </h2>
                </div>
                <div class="divide-y divide-border-subtle">
                    @forelse($liveFeed as $feed)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-surface-2 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-full bg-surface-3 flex items-center justify-center text-text-tertiary">
                                    <x-heroicon-o-computer-desktop class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-text-primary">{{ $feed['name'] }}</p>
                                    <p class="text-xs text-text-tertiary flex items-center mt-0.5">
                                        <x-heroicon-o-map-pin class="w-3 h-3 mr-1" />
                                        {{ $feed['loc'] }}
                                    </p>
                                </div>
                            </div>
                            <x-ui.badge :variant="strtolower($feed['status']) === 'online' ? 'online' : 'offline'" :showDot="true">
                                {{ $feed['status'] }}
                            </x-ui.badge>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-text-secondary">
                            <x-heroicon-o-computer-desktop class="w-12 h-12 mx-auto mb-3 text-text-tertiary opacity-50" />
                            <p>No screens currently active.</p>
                            <div class="mt-4">
                                <x-ui.button :href="route('app.screens.index')" variant="outline" size="sm">
                                    Manage Screens
                                </x-ui.button>
                            </div>
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>

        <!-- Quick Actions -->
        <div>
            <x-ui.card class="p-6">
                <h2 class="text-lg font-semibold text-text-primary mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('app.campaigns.index') }}" class="group flex items-center p-3 rounded-xl border border-border-subtle bg-surface-1 hover:border-signal-400 hover:shadow-glow-signal transition-all">
                        <div class="h-10 w-10 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center mr-4 group-hover:bg-emerald-500/20">
                            <x-heroicon-o-plus class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-text-primary">Create Campaign</p>
                            <p class="text-xs text-text-tertiary">Schedule new content</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('app.screens.index') }}" class="group flex items-center p-3 rounded-xl border border-border-subtle bg-surface-1 hover:border-signal-400 hover:shadow-glow-signal transition-all">
                        <div class="h-10 w-10 rounded-lg bg-signal-500/10 text-signal-600 flex items-center justify-center mr-4 group-hover:bg-signal-500/20">
                            <x-heroicon-o-device-phone-mobile class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-text-primary">Add Screen</p>
                            <p class="text-xs text-text-tertiary">Provision a new display</p>
                        </div>
                    </a>

                    <a href="{{ route('app.media.index') }}" class="group flex items-center p-3 rounded-xl border border-border-subtle bg-surface-1 hover:border-signal-400 hover:shadow-glow-signal transition-all">
                        <div class="h-10 w-10 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center mr-4 group-hover:bg-blue-500/20">
                            <x-heroicon-o-arrow-up-tray class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-text-primary">Upload Media</p>
                            <p class="text-xs text-text-tertiary">Add images or videos</p>
                        </div>
                    </a>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
