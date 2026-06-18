<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Support Tickets</h1>
        <p class="text-text-secondary mt-1">Manage and respond to support requests from organizations.</p>
    </div>

    <x-ui.card class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-border-subtle bg-surface-2 text-xs font-semibold text-text-tertiary uppercase tracking-wider">
                        <th class="px-6 py-4">Ticket</th>
                        <th class="px-6 py-4">Organization</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Priority</th>
                        <th class="px-6 py-4 text-right">Last Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-surface-2 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.tickets.show', $ticket) }}'">
                            <td class="px-6 py-4">
                                <div class="font-medium text-text-primary">#{{ $ticket->id }} - {{ Str::limit($ticket->subject, 40) }}</div>
                                <div class="text-xs text-text-secondary mt-1">Submitted by {{ $ticket->user->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-text-primary">{{ $ticket->organization->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                                    {{ $ticket->status === 'open' ? 'bg-signal-500/10 text-signal-600' : '' }}
                                    {{ $ticket->status === 'in_progress' ? 'bg-amber-500/10 text-amber-600' : '' }}
                                    {{ $ticket->status === 'resolved' ? 'bg-emerald-500/10 text-emerald-600' : '' }}
                                    {{ $ticket->status === 'closed' ? 'bg-surface-3 text-text-tertiary' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-surface-3 text-text-secondary">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-text-tertiary">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-text-tertiary">
                                <div class="flex flex-col items-center justify-center">
                                    <x-heroicon-o-lifebuoy class="w-12 h-12 mb-4 text-border-strong" />
                                    <p class="text-base font-medium text-text-secondary">No support tickets found</p>
                                    <p class="text-sm mt-1">All caught up! No active tickets to manage.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
</div>
