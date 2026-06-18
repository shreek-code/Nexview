<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Platform Overview</h1>
        <p class="text-text-secondary mt-1">Monitor the overall health and usage of the NexView network.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-ui.card class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-text-secondary">Organizations</h3>
                <div class="p-2 rounded-lg bg-surface-2 text-text-tertiary">
                    <x-heroicon-o-building-office-2 class="w-5 h-5" />
                </div>
            </div>
            <div class="text-3xl font-bold text-text-primary">{{ number_format($stats['total_organizations']) }}</div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-text-secondary">Active Screens</h3>
                <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-500">
                    <x-heroicon-o-computer-desktop class="w-5 h-5" />
                </div>
            </div>
            <div class="text-3xl font-bold text-text-primary">{{ number_format($stats['active_screens']) }}</div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-text-secondary">Offline Screens</h3>
                <div class="p-2 rounded-lg bg-red-500/10 text-red-500">
                    <x-heroicon-o-signal-slash class="w-5 h-5" />
                </div>
            </div>
            <div class="text-3xl font-bold text-text-primary">{{ number_format($stats['offline_screens']) }}</div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-text-secondary">Total Storage</h3>
                <div class="p-2 rounded-lg bg-signal-500/10 text-signal-500">
                    <x-heroicon-o-circle-stack class="w-5 h-5" />
                </div>
            </div>
            <div class="text-3xl font-bold text-text-primary">{{ $stats['storage_usage'] }}</div>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Screen Registrations -->
        <x-ui.card class="p-0">
            <div class="p-6 border-b border-border-subtle">
                <h2 class="text-lg font-semibold text-text-primary">Recent Screen Registrations</h2>
            </div>
            <div class="p-0">
                @if($recent_registrations->isEmpty())
                    <div class="p-6 text-center text-text-secondary text-sm">No recent registrations.</div>
                @else
                    <ul class="divide-y divide-border-subtle">
                        @foreach($recent_registrations as $screen)
                            <li class="p-4 hover:bg-surface-2 transition-colors flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-text-primary">{{ $screen['name'] }}</p>
                                    <p class="text-xs text-text-secondary">{{ $screen['organization'] }}</p>
                                </div>
                                <div class="text-xs text-text-tertiary">
                                    {{ $screen['date'] }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </x-ui.card>

        <!-- Recent Audit Events -->
        <x-ui.card class="p-0">
            <div class="p-6 border-b border-border-subtle">
                <h2 class="text-lg font-semibold text-text-primary">Platform Audit Log</h2>
            </div>
            <div class="p-0">
                @if($recent_audit_events->isEmpty())
                    <div class="p-6 text-center text-text-secondary text-sm">No recent events.</div>
                @else
                    <ul class="divide-y divide-border-subtle">
                        @foreach($recent_audit_events as $event)
                            <li class="p-4 hover:bg-surface-2 transition-colors flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-text-primary">{{ $event['action'] }}</p>
                                    <p class="text-xs text-text-secondary">By {{ $event['user'] }}</p>
                                </div>
                                <div class="text-xs text-text-tertiary">
                                    {{ $event['date'] }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </x-ui.card>
    </div>
</div>
