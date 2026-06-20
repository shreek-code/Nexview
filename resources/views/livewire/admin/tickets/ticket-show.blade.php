<div>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('admin.tickets.index') }}" class="p-2 rounded-xl bg-surface-2 hover:bg-surface-3 text-text-tertiary hover:text-text-primary transition-all">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-text-primary tracking-tight">{{ $ticket->subject }}</h1>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Content (Messages) -->
        <div class="lg:col-span-3 space-y-6">
            @if (session()->has('message'))
                <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 font-medium flex items-center">
                    <x-heroicon-s-check-circle class="w-5 h-5 mr-2 flex-shrink-0" />
                    {{ session('message') }}
                </div>
            @endif

            <!-- Messages Thread -->
            <x-ui.card class="!p-0 overflow-hidden">
                <div class="bg-surface-2 px-6 py-3 border-b border-border-subtle flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-text-secondary">Conversation Thread</h3>
                    <span class="text-xs text-text-tertiary">{{ $messages->count() }} messages</span>
                </div>
                <div class="divide-y divide-border-subtle" wire:poll.5s>
                    @foreach($messages as $msg)
                        @php
                            $isAdmin = $msg->sender_type === \App\Models\PlatformUser::class;
                        @endphp
                        <div class="p-6 {{ $isAdmin ? 'bg-signal-500/[0.02]' : '' }}">
                            <div class="flex items-start gap-4">
                                <!-- Avatar -->
                                <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold uppercase
                                    {{ $isAdmin ? 'bg-signal-500/10 text-signal-600' : 'bg-amber-500/10 text-amber-600' }}">
                                    {{ strtoupper(substr($msg->sender->name ?? 'S', 0, 1)) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-sm font-semibold text-text-primary">{{ $msg->sender->name ?? 'System' }}</span>
                                        @if($isAdmin)
                                            <span class="px-1.5 py-0.5 rounded bg-signal-500/20 text-signal-600 text-[10px] uppercase font-bold tracking-wider">Admin</span>
                                        @else
                                            <span class="px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-600 text-[10px] uppercase font-bold tracking-wider">Customer</span>
                                        @endif
                                        <span class="text-xs text-text-tertiary ml-auto">{{ $msg->created_at->format('M j, Y \a\t g:i A') }}</span>
                                    </div>

                                    <div class="text-sm text-text-primary leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</div>

                                    @if($msg->attachments->isNotEmpty())
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            @foreach($msg->attachments as $attachment)
                                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-surface-2 hover:bg-surface-3 border border-border-subtle rounded-lg text-sm text-text-secondary transition-colors group">
                                                    <x-heroicon-o-paper-clip class="w-4 h-4 mr-2 text-text-tertiary group-hover:text-signal-500" />
                                                    <span class="truncate max-w-[150px]">{{ $attachment->file_name }}</span>
                                                    <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5 ml-2 text-text-tertiary opacity-0 group-hover:opacity-100 transition-opacity" />
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <!-- Reply Form -->
            @if($ticket->status !== 'closed')
                <x-ui.card>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-text-primary mb-4 flex items-center gap-2">
                            <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5 text-signal-500" />
                            Admin Reply
                        </h3>
                        <form wire:submit="reply" class="space-y-4">
                            <div>
                                <textarea wire:model="message" rows="4" class="input-text w-full" placeholder="Type your reply to the customer..."></textarea>
                                @error('message')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <label for="admin_attachments" class="flex items-center gap-2 text-sm font-medium text-text-secondary cursor-pointer hover:text-signal-600 transition-colors px-3 py-2 rounded-lg hover:bg-surface-2">
                                        <x-heroicon-o-paper-clip class="w-5 h-5" />
                                        Attach Files
                                    </label>
                                    <input type="file" id="admin_attachments" wire:model="attachments" multiple class="hidden" />
                                    @if($attachments)
                                        <span class="text-xs text-text-tertiary bg-surface-2 px-2 py-1 rounded-md">
                                            {{ count($attachments) }} file(s) selected
                                        </span>
                                    @endif
                                    @error('attachments.*')
                                        <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <x-ui.button type="submit" wire:loading.attr="disabled">
                                    <div wire:loading wire:target="reply" class="mr-2 h-4 w-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></div>
                                    <span wire:loading.remove wire:target="reply">Send Reply</span>
                                    <span wire:loading wire:target="reply">Sending...</span>
                                </x-ui.button>
                            </div>
                        </form>
                    </div>
                </x-ui.card>
            @else
                <x-ui.card class="!p-0 overflow-hidden">
                    <div class="bg-surface-2 p-8 text-center">
                        <div class="w-14 h-14 rounded-full bg-surface-3 flex items-center justify-center mx-auto mb-4">
                            <x-heroicon-o-lock-closed class="w-7 h-7 text-text-tertiary" />
                        </div>
                        <h3 class="text-lg font-semibold text-text-primary mb-1">This ticket is closed</h3>
                        <p class="text-sm text-text-secondary">Change the status from the sidebar to re-open if needed.</p>
                    </div>
                </x-ui.card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Status Management -->
            <x-ui.card class="!p-0 overflow-hidden">
                <div class="bg-surface-2 px-4 py-3 border-b border-border-subtle">
                    <h4 class="text-xs font-semibold text-text-tertiary uppercase tracking-wider">Status</h4>
                </div>
                <div class="p-4">
                    <form wire:submit="updateStatus" class="space-y-3">
                        <select wire:model="newStatus" class="input-text w-full text-sm">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                        <button type="submit" class="w-full px-4 py-2 bg-signal-600 text-white rounded-lg hover:bg-signal-500 font-medium text-sm transition-colors" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="updateStatus">Update Status</span>
                            <span wire:loading wire:target="updateStatus">Updating...</span>
                        </button>
                    </form>
                    @if (session()->has('status_message'))
                        <p class="text-xs text-emerald-600 font-medium mt-2 flex items-center gap-1">
                            <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                            {{ session('status_message') }}
                        </p>
                    @endif
                </div>
            </x-ui.card>

            <!-- Ticket Details -->
            <x-ui.card class="!p-0 overflow-hidden">
                <div class="bg-surface-2 px-4 py-3 border-b border-border-subtle">
                    <h4 class="text-xs font-semibold text-text-tertiary uppercase tracking-wider">Details</h4>
                </div>
                <div class="p-4 space-y-4 text-sm">
                    <div>
                        <p class="text-text-tertiary font-medium mb-1">Ticket ID</p>
                        <p class="text-text-primary font-semibold">#{{ $ticket->id }}</p>
                    </div>
                    <div>
                        <p class="text-text-tertiary font-medium mb-1">Priority</p>
                        <span class="inline-flex items-center gap-1 font-semibold
                            {{ $ticket->priority === 'high' ? 'text-red-500' : '' }}
                            {{ $ticket->priority === 'medium' ? 'text-amber-500' : '' }}
                            {{ $ticket->priority === 'low' ? 'text-emerald-600' : '' }}">
                            @if($ticket->priority === 'high')
                                <x-heroicon-s-arrow-up class="w-3.5 h-3.5" />
                            @elseif($ticket->priority === 'medium')
                                <x-heroicon-s-minus class="w-3.5 h-3.5" />
                            @else
                                <x-heroicon-s-arrow-down class="w-3.5 h-3.5" />
                            @endif
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-text-tertiary font-medium mb-1">Organization</p>
                        <a href="{{ route('admin.organizations.show', $ticket->organization) }}" class="text-signal-600 hover:underline font-medium">
                            {{ $ticket->organization->name }}
                        </a>
                    </div>
                    <div>
                        <p class="text-text-tertiary font-medium mb-1">Created By</p>
                        <p class="text-text-primary">{{ $ticket->user->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-text-tertiary">{{ $ticket->user->email ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-text-tertiary font-medium mb-1">Created</p>
                        <p class="text-text-primary">{{ $ticket->created_at->format('M j, Y') }}</p>
                        <p class="text-xs text-text-tertiary">{{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <p class="text-text-tertiary font-medium mb-1">Last Updated</p>
                        <p class="text-text-primary">{{ $ticket->updated_at->format('M j, Y') }}</p>
                        <p class="text-xs text-text-tertiary">{{ $ticket->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
