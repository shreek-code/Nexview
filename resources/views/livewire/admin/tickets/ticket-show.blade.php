<div>
    <div class="mb-8 flex justify-between items-start">
        <div>
            <div class="flex items-center space-x-3 mb-1">
                <a href="{{ route('admin.tickets.index') }}" class="text-text-tertiary hover:text-text-primary transition-colors">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold text-text-primary tracking-tight">Ticket #{{ $ticket->id }}</h1>
                <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                    {{ $ticket->status === 'open' ? 'bg-signal-500/10 text-signal-600' : '' }}
                    {{ $ticket->status === 'in_progress' ? 'bg-amber-500/10 text-amber-600' : '' }}
                    {{ $ticket->status === 'resolved' ? 'bg-emerald-500/10 text-emerald-600' : '' }}
                    {{ $ticket->status === 'closed' ? 'bg-surface-3 text-text-tertiary' : '' }}">
                    {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                </span>
            </div>
            <h2 class="text-xl font-medium text-text-secondary mt-2">{{ $ticket->subject }}</h2>
            <div class="text-sm text-text-tertiary mt-1">
                Organization: <a href="{{ route('admin.organizations.show', $ticket->organization) }}" class="text-signal-600 hover:underline">{{ $ticket->organization->name }}</a>
            </div>
        </div>
        <div class="text-right flex flex-col items-end space-y-3">
            <div>
                <p class="text-sm font-medium text-text-secondary">Priority: <span class="px-2 py-0.5 rounded bg-surface-3 text-text-primary text-xs ml-1">{{ ucfirst($ticket->priority) }}</span></p>
                <p class="text-xs text-text-tertiary mt-1">Opened {{ $ticket->created_at->diffForHumans() }}</p>
            </div>
            
            <form wire:submit="updateStatus" class="flex items-center space-x-2">
                <select wire:model="newStatus" class="input-text py-1.5 text-sm w-36">
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
                <button type="submit" class="btn-secondary py-1.5 text-sm" wire:loading.attr="disabled">Update</button>
            </form>
            @if (session()->has('status_message'))
                <span class="text-xs text-emerald-600 font-medium">{{ session('status_message') }}</span>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 text-emerald-600 font-medium flex items-center">
            <x-heroicon-s-check-circle class="w-5 h-5 mr-2" />
            {{ session('message') }}
        </div>
    @endif

    <div class="space-y-6 mb-8">
        @foreach($messages as $msg)
            <div class="flex {{ $msg->sender_type === \App\Models\PlatformUser::class ? 'justify-end' : 'justify-start' }}">
                <div class="flex flex-col space-y-2 max-w-[80%] {{ $msg->sender_type === \App\Models\PlatformUser::class ? 'items-end' : 'items-start' }}">
                    <div class="flex items-center space-x-2 px-1">
                        <span class="text-sm font-semibold text-text-primary">{{ $msg->sender->name ?? 'System' }}</span>
                        @if($msg->sender_type === \App\Models\PlatformUser::class)
                            <span class="px-1.5 py-0.5 rounded bg-signal-500/20 text-signal-600 text-[10px] uppercase font-bold tracking-wider">Admin</span>
                        @else
                            <span class="text-xs text-text-tertiary">User</span>
                        @endif
                        <span class="text-xs text-text-tertiary">{{ $msg->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    
                    <div class="p-4 rounded-2xl shadow-sm {{ $msg->sender_type === \App\Models\PlatformUser::class ? 'bg-signal-600 text-white rounded-tr-sm' : 'bg-surface-2 text-text-primary border border-border-subtle rounded-tl-sm' }}">
                        <p class="whitespace-pre-wrap text-sm leading-relaxed">{{ $msg->message }}</p>
                    </div>

                    @if($msg->attachments->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($msg->attachments as $attachment)
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="flex items-center px-3 py-2 bg-surface-2 hover:bg-surface-3 border border-border-subtle rounded-lg text-sm text-text-secondary transition-colors group">
                                    <x-heroicon-o-paper-clip class="w-4 h-4 mr-2 text-text-tertiary group-hover:text-signal-500" />
                                    <span class="truncate max-w-[150px]">{{ $attachment->file_name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($ticket->status !== 'closed')
        <x-ui.card class="bg-surface-1 border-signal-500/30">
            <h3 class="text-lg font-semibold text-text-primary mb-4">Reply to Ticket</h3>
            <form wire:submit="reply" class="space-y-4">
                <div class="space-y-2">
                    <textarea wire:model="message" rows="4" class="input-text w-full focus:ring-signal-500" placeholder="Type your reply here..."></textarea>
                    @error('message') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <label for="attachments" class="text-sm font-medium text-text-secondary flex items-center cursor-pointer hover:text-signal-600 transition-colors">
                            <x-heroicon-o-paper-clip class="w-5 h-5 mr-2" />
                            Attach Files
                        </label>
                        <input type="file" id="attachments" wire:model="attachments" multiple class="hidden" />
                        @error('attachments.*') <span class="text-sm text-red-500 block">{{ $message }}</span> @enderror
                        @if($attachments)
                            <div class="text-xs text-text-tertiary">
                                {{ count($attachments) }} file(s) selected
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn-primary bg-signal-600 hover:bg-signal-500 text-white" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="reply">Send Reply</span>
                        <span wire:loading wire:target="reply">Sending...</span>
                    </button>
                </div>
            </form>
        </x-ui.card>
    @else
        <div class="p-6 rounded-xl bg-surface-2 border border-border-subtle text-center">
            <x-heroicon-o-lock-closed class="w-8 h-8 mx-auto text-text-tertiary mb-3" />
            <h3 class="text-lg font-medium text-text-primary">This ticket is closed</h3>
            <p class="text-sm text-text-secondary mt-1">Status can be changed from the top right menu if needed.</p>
        </div>
    @endif
</div>
