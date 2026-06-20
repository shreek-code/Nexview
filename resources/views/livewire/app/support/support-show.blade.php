<div>
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('app.support.index') }}" wire:navigate class="p-2 rounded-xl bg-surface-2 hover:bg-surface-3 text-text-tertiary hover:text-text-primary transition-all">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
            </a>
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-text-primary tracking-tight">{{ $ticket->subject }}</h1>
                </div>
            </div>
        </div>

        <!-- Ticket Meta -->
        <div class="flex flex-wrap items-center gap-3">
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                {{ $ticket->status === 'open' ? 'bg-signal-500/10 text-signal-600' : '' }}
                {{ $ticket->status === 'in_progress' ? 'bg-amber-500/10 text-amber-600' : '' }}
                {{ $ticket->status === 'resolved' ? 'bg-emerald-500/10 text-emerald-600' : '' }}
                {{ $ticket->status === 'closed' ? 'bg-surface-3 text-text-tertiary' : '' }}">
                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
            </span>
            <span class="inline-flex items-center gap-1 text-xs font-medium
                {{ $ticket->priority === 'high' ? 'text-red-500' : '' }}
                {{ $ticket->priority === 'medium' ? 'text-amber-500' : '' }}
                {{ $ticket->priority === 'low' ? 'text-text-tertiary' : '' }}">
                @if($ticket->priority === 'high')
                    <x-heroicon-s-arrow-up class="w-3.5 h-3.5" />
                @elseif($ticket->priority === 'medium')
                    <x-heroicon-s-minus class="w-3.5 h-3.5" />
                @else
                    <x-heroicon-s-arrow-down class="w-3.5 h-3.5" />
                @endif
                {{ ucfirst($ticket->priority) }} Priority
            </span>
            <span class="text-xs text-text-tertiary">•</span>
            <span class="text-xs text-text-tertiary">Ticket #{{ $ticket->id }}</span>
            <span class="text-xs text-text-tertiary">•</span>
            <span class="text-xs text-text-tertiary">Opened {{ $ticket->created_at->format('M j, Y') }}</span>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 font-medium flex items-center">
            <x-heroicon-s-check-circle class="w-5 h-5 mr-2 flex-shrink-0" />
            {{ session('message') }}
        </div>
    @endif

    <!-- Messages Thread -->
    <x-ui.card class="!p-0 overflow-hidden mb-6">
        <div class="bg-surface-2 px-6 py-3 border-b border-border-subtle">
            <h3 class="text-sm font-semibold text-text-secondary">Conversation</h3>
        </div>
        <div class="divide-y divide-border-subtle">
            @foreach($messages as $msg)
                @php
                    $isUser = $msg->sender_type === \App\Models\User::class;
                @endphp
                <div class="p-6 {{ !$isUser ? 'bg-surface-2/30' : '' }}">
                    <div class="flex items-start gap-4">
                        <!-- Avatar -->
                        <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-bold uppercase
                            {{ $isUser ? 'bg-signal-500/10 text-signal-600' : 'bg-emerald-500/10 text-emerald-600' }}">
                            {{ strtoupper(substr($msg->sender->name ?? 'S', 0, 1)) }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-sm font-semibold text-text-primary">{{ $msg->sender->name ?? 'System' }}</span>
                                @if(!$isUser)
                                    <span class="px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-600 text-[10px] uppercase font-bold tracking-wider">Support Team</span>
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

    @if($ticket->status !== 'closed')
        <x-ui.card>
            <div class="p-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4 flex items-center gap-2">
                    <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5 text-signal-500" />
                    Reply to Ticket
                </h3>
                <form wire:submit="reply" class="space-y-4">
                    <div>
                        <textarea wire:model="message" rows="4" class="input-text w-full" placeholder="Type your reply here..."></textarea>
                        @error('message')
                            <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <label for="reply_attachments" class="flex items-center gap-2 text-sm font-medium text-text-secondary cursor-pointer hover:text-signal-600 transition-colors px-3 py-2 rounded-lg hover:bg-surface-2">
                                <x-heroicon-o-paper-clip class="w-5 h-5" />
                                Attach Files
                            </label>
                            <input type="file" id="reply_attachments" wire:model="attachments" multiple class="hidden" />
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
                <p class="text-sm text-text-secondary">If you have further questions, please open a new ticket.</p>
                <div class="mt-4">
                    <x-ui.button href="{{ route('app.support.create') }}" variant="outline">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                        Open New Ticket
                    </x-ui.button>
                </div>
            </div>
        </x-ui.card>
    @endif
</div>
