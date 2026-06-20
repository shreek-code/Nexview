<div class="relative" x-data="{ open: false }" @click.away="open = false" wire:poll.10s>
    <button @click="open = !open" class="flex items-center justify-center w-11 h-11 rounded-full neumorphic text-text-secondary cursor-pointer hover:text-signal-600 transition-colors focus:outline-none">
        <x-heroicon-o-bell class="w-5 h-5" />
        @if($unreadCount > 0)
            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 border-2 border-surface-1 rounded-full"></span>
        @endif
    </button>
    
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
         class="absolute right-0 mt-4 w-80 bg-surface-1 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] border border-border-subtle overflow-hidden z-50">
        
        <div class="px-4 py-3 border-b border-border-subtle flex items-center justify-between bg-surface-2">
            <h3 class="text-sm font-bold text-text-primary">Notifications</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-signal-600 hover:text-signal-700 font-medium transition-colors">Mark all as read</button>
            @endif
        </div>
        
        <div class="max-h-80 overflow-y-auto">
            @if($notifications->isEmpty())
                <div class="px-4 py-8 text-center text-sm text-text-tertiary">
                    <x-heroicon-o-bell-slash class="w-8 h-8 mx-auto mb-2 opacity-50" />
                    No new notifications
                </div>
            @else
                <div class="divide-y divide-border-subtle">
                    @foreach($notifications as $notification)
                        @php
                            $type = class_basename($notification->type);
                            $icon = match($type) {
                                'TicketCreated' => 'heroicon-o-ticket',
                                'TicketReply' => 'heroicon-o-chat-bubble-left-right',
                                'TicketStatusUpdated' => 'heroicon-o-arrow-path',
                                default => 'heroicon-o-bell'
                            };
                            $color = match($type) {
                                'TicketCreated' => 'text-emerald-500 bg-emerald-500/10',
                                'TicketReply' => 'text-signal-500 bg-signal-500/10',
                                'TicketStatusUpdated' => 'text-amber-500 bg-amber-500/10',
                                default => 'text-text-tertiary bg-surface-3'
                            };
                        @endphp
                        <button wire:click="markAsRead('{{ $notification->id }}')" class="w-full text-left px-4 py-3 hover:bg-surface-2 transition-colors flex items-start gap-3 group">
                            <div class="p-2 rounded-lg {{ $color }} flex-shrink-0 mt-0.5">
                                @svg($icon, 'w-4 h-4')
                            </div>
                            <div class="flex-1 min-w-0">
                                @if($type === 'TicketCreated')
                                    <p class="text-sm text-text-primary font-medium leading-tight mb-1">New Support Ticket</p>
                                    <p class="text-xs text-text-secondary truncate">{{ $notification->data['subject'] ?? 'Ticket #' . ($notification->data['ticket_id'] ?? '') }}</p>
                                @elseif($type === 'TicketReply')
                                    <p class="text-sm text-text-primary font-medium leading-tight mb-1">New Reply on Ticket</p>
                                    <p class="text-xs text-text-secondary truncate">Ticket #{{ $notification->data['ticket_id'] ?? '' }}</p>
                                @elseif($type === 'TicketStatusUpdated')
                                    <p class="text-sm text-text-primary font-medium leading-tight mb-1">Ticket Status Updated</p>
                                    <p class="text-xs text-text-secondary truncate">Now: {{ str_replace('_', ' ', ucfirst($notification->data['status'] ?? '')) }}</p>
                                @endif
                                <p class="text-[10px] text-text-tertiary mt-1.5">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
