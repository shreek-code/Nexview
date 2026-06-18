<div>
    <h1 class="text-2xl font-bold text-text-primary tracking-tight">System Audit Logs</h1>
    <p class="text-text-secondary mt-1">Review administrative actions and security events.</p>

    <div class="mt-8 bg-bg-surface border border-bg-border rounded-xl shadow-sm overflow-hidden">
        @if($logs->isEmpty())
            <div class="p-12 text-center">
                <p class="text-text-secondary">No audit logs found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-bg-element border-b border-bg-border text-text-secondary">
                        <tr>
                            <th class="px-6 py-4 font-medium">Time</th>
                            <th class="px-6 py-4 font-medium">Admin</th>
                            <th class="px-6 py-4 font-medium">Action</th>
                            <th class="px-6 py-4 font-medium">Organization</th>
                            <th class="px-6 py-4 font-medium">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bg-border">
                        @foreach($logs as $log)
                            <tr class="hover:bg-bg-element transition-colors">
                                <td class="px-6 py-4 text-text-secondary">
                                    {{ $log->created_at->format('M d, Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-text-primary">{{ $log->platformUser->name ?? 'System' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-surface-tertiary text-text-primary">
                                        {{ str_replace('_', ' ', Str::title($log->action)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->organization)
                                        <div class="font-medium text-text-primary">{{ $log->organization->name }}</div>
                                    @else
                                        <span class="text-text-tertiary">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->metadata)
                                        <div class="text-xs text-text-secondary font-mono bg-bg-element p-2 rounded">
                                            {{ json_encode($log->metadata) }}
                                        </div>
                                    @else
                                        <span class="text-text-tertiary">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="p-4 border-t border-bg-border bg-bg-element">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
