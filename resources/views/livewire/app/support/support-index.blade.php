<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Support Tickets</h1>
            <p class="text-text-secondary mt-1">Get help and track your support requests.</p>
        </div>
        <x-ui.button href="{{ route('app.support.create') }}">
            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
            New Ticket
        </x-ui.button>
    </div>

    @if (session('message'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 px-4 py-3 rounded-xl flex items-start">
            <x-heroicon-o-check-circle class="w-5 h-5 mr-3 mt-0.5" />
            <p class="font-medium">{{ session('message') }}</p>
        </div>
    @endif

    @if($tickets->isNotEmpty())
        <!-- Stats Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @php
                $open = $tickets->where('status', 'open')->count();
                $inProgress = $tickets->where('status', 'in_progress')->count();
                $resolved = $tickets->where('status', 'resolved')->count();
                $closed = $tickets->where('status', 'closed')->count();
            @endphp
            <x-ui.card class="!p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-text-tertiary uppercase tracking-wider">Open</p>
                        <p class="text-2xl font-bold text-signal-600 mt-1">{{ $open }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-signal-500/10 flex items-center justify-center">
                        <x-heroicon-o-inbox class="w-5 h-5 text-signal-600" />
                    </div>
                </div>
            </x-ui.card>
            <x-ui.card class="!p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-text-tertiary uppercase tracking-wider">In Progress</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $inProgress }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                        <x-heroicon-o-arrow-path class="w-5 h-5 text-amber-600" />
                    </div>
                </div>
            </x-ui.card>
            <x-ui.card class="!p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-text-tertiary uppercase tracking-wider">Resolved</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $resolved }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                        <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-600" />
                    </div>
                </div>
            </x-ui.card>
            <x-ui.card class="!p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-text-tertiary uppercase tracking-wider">Closed</p>
                        <p class="text-2xl font-bold text-text-tertiary mt-1">{{ $closed }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-surface-2 flex items-center justify-center">
                        <x-heroicon-o-archive-box class="w-5 h-5 text-text-tertiary" />
                    </div>
                </div>
            </x-ui.card>
        </div>
    @endif

    <!-- Ticket List -->
    <div class="space-y-3">
        @forelse($tickets as $ticket)
            <a href="{{ route('app.support.show', $ticket) }}" wire:navigate class="block group">
                <x-ui.card class="!p-0 overflow-hidden hover:shadow-md transition-all hover:border-signal-500/30">
                    <div class="flex items-center">
                        <!-- Status Indicator Bar -->
                        <div class="w-1 self-stretch flex-shrink-0
                            {{ $ticket->status === 'open' ? 'bg-signal-500' : '' }}
                            {{ $ticket->status === 'in_progress' ? 'bg-amber-500' : '' }}
                            {{ $ticket->status === 'resolved' ? 'bg-emerald-500' : '' }}
                            {{ $ticket->status === 'closed' ? 'bg-text-tertiary' : '' }}
                        "></div>

                        <div class="flex-1 p-5 flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="font-semibold text-text-primary group-hover:text-signal-600 transition-colors truncate">
                                        {{ $ticket->subject }}
                                    </h3>
                                    <span class="flex-shrink-0 px-2.5 py-0.5 rounded-full text-[11px] font-semibold uppercase tracking-wide
                                        {{ $ticket->status === 'open' ? 'bg-signal-500/10 text-signal-600' : '' }}
                                        {{ $ticket->status === 'in_progress' ? 'bg-amber-500/10 text-amber-600' : '' }}
                                        {{ $ticket->status === 'resolved' ? 'bg-emerald-500/10 text-emerald-600' : '' }}
                                        {{ $ticket->status === 'closed' ? 'bg-surface-3 text-text-tertiary' : '' }}">
                                        {{ str_replace('_', ' ', $ticket->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-text-tertiary">
                                    <span class="flex items-center gap-1">
                                        <x-heroicon-o-hashtag class="w-3.5 h-3.5" />
                                        {{ $ticket->id }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        @if($ticket->priority === 'high')
                                            <x-heroicon-o-arrow-up class="w-3.5 h-3.5 text-red-500" />
                                            <span class="text-red-500 font-medium">High</span>
                                        @elseif($ticket->priority === 'medium')
                                            <x-heroicon-o-minus class="w-3.5 h-3.5 text-amber-500" />
                                            <span class="text-amber-600">Medium</span>
                                        @else
                                            <x-heroicon-o-arrow-down class="w-3.5 h-3.5 text-text-tertiary" />
                                            <span>Low</span>
                                        @endif
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <x-heroicon-o-chat-bubble-left-ellipsis class="w-3.5 h-3.5" />
                                        {{ $ticket->messages()->count() }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 flex-shrink-0">
                                <span class="text-xs text-text-tertiary">{{ $ticket->updated_at->diffForHumans() }}</span>
                                <x-heroicon-o-chevron-right class="w-4 h-4 text-text-tertiary group-hover:text-signal-500 transition-colors" />
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            </a>
        @empty
            <x-ui.card class="py-16 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-signal-500/10 to-signal-600/5 flex items-center justify-center text-signal-500 mb-6">
                    <x-heroicon-o-lifebuoy class="w-10 h-10" />
                </div>
                <h3 class="text-lg font-semibold text-text-primary mb-2">No Support Tickets</h3>
                <p class="text-text-secondary max-w-sm mb-6">You haven't submitted any support requests yet. We're here to help whenever you need us.</p>
                <x-ui.button href="{{ route('app.support.create') }}">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                    Create Your First Ticket
                </x-ui.button>
            </x-ui.card>
        @endforelse
    </div>
</div>
