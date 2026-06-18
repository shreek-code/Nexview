<div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-3 text-sm text-text-tertiary mb-2">
                <a href="{{ route('app.screens.index') }}" wire:navigate class="hover:text-text-primary transition-colors">Screens</a>
                <x-heroicon-o-chevron-right class="w-4 h-4" />
                <span class="text-text-primary">{{ $screen->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight flex items-center space-x-3">
                <span>{{ $screen->name }}</span>
                @if($screen->status === 'online')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                        <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        Online
                    </span>
                @elseif($screen->status === 'unregistered')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-500 border border-amber-500/20">
                        <span class="w-1.5 h-1.5 mr-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                        Pending Pairing
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500/10 text-text-secondary border border-border-subtle">
                        <span class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></span>
                        Offline
                    </span>
                @endif
            </h1>
        </div>

        <div>
            <x-ui.button variant="outline" class="text-red-600 border-red-600/30 hover:bg-red-50" wire:click="delete" wire:confirm="Are you sure you want to delete this screen?">
                <x-heroicon-o-trash class="w-4 h-4 mr-2" />
                Delete Screen
            </x-ui.button>
        </div>
    </div>

    <!-- MAIN BODY -->
    <!-- Remote Control & Remote Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left remote control deck -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Playback & Media Overview -->
                <x-ui.card class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-text-primary">Remote Deck</h3>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs text-text-secondary font-mono">Live Sync Enabled</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Play/Pause Remote Button -->
                        <div class="bg-surface-2 rounded-2xl p-6 flex flex-col items-center justify-center border border-border-subtle space-y-4">
                            <span class="text-xs font-semibold uppercase tracking-wider text-text-tertiary">Playback Control</span>
                            
                            <button wire:click="togglePlay" class="w-20 h-20 rounded-full flex items-center justify-center shadow-lg transition-transform active:scale-95 focus:outline-none {{ $is_playing ? 'bg-signal-500 text-white hover:bg-signal-600 shadow-signal-500/20' : 'bg-red-500 text-white hover:bg-red-600 shadow-red-500/20' }}">
                                @if($is_playing)
                                    <x-heroicon-s-pause class="w-10 h-10" />
                                @else
                                    <x-heroicon-s-play class="w-10 h-10 translate-x-0.5" />
                                @endif
                            </button>

                            <span class="text-sm font-semibold {{ $is_playing ? 'text-emerald-500' : 'text-red-500' }}">
                                {{ $is_playing ? 'Currently Playing' : 'Playback Paused' }}
                            </span>
                        </div>

                        <!-- Remote Volume Control (Analogue Fader Style) -->
                        <div class="bg-surface-2 rounded-2xl p-6 flex flex-col justify-between border border-border-subtle shadow-[inset_0_2px_4px_rgba(0,0,0,0.3)] min-h-[300px]">
                            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-text-tertiary">
                                <span>Master Volume</span>
                                <span class="font-mono text-signal-500 bg-surface-1 px-2 py-1 rounded shadow-inner">{{ $volume }}%</span>
                            </div>

                            <div class="flex flex-col items-center py-6 space-y-4 flex-1 justify-center">
                                <!-- Mixing Console Fader Style -->
                                <div class="relative w-12 h-32 bg-surface-1 rounded-full shadow-inner border border-border-subtle flex justify-center py-2 shrink-0">
                                    <input type="range" min="0" max="100" wire:model.live="volume" class="absolute w-32 h-12 -rotate-90 appearance-none bg-transparent cursor-pointer z-10 top-10" style="accent-color: transparent;" />
                                    <!-- Visual Fader Knob -->
                                    <div class="absolute w-10 h-6 bg-gradient-to-b from-surface-3 to-surface-2 border border-border-subtle rounded shadow-[0_4px_6px_rgba(0,0,0,0.5)] pointer-events-none transition-all duration-75" style="bottom: {{ $volume }}%; transform: translateY(50%);">
                                        <div class="w-full h-0.5 bg-signal-500 absolute top-1/2 -translate-y-1/2 shadow-[0_0_4px_#ef4444]"></div>
                                    </div>
                                    <!-- Tick marks -->
                                    <div class="absolute left-1 h-full py-2 flex flex-col justify-between items-center pointer-events-none">
                                        <div class="w-1 h-0.5 bg-text-tertiary"></div>
                                        <div class="w-1 h-0.5 bg-text-tertiary"></div>
                                        <div class="w-1 h-0.5 bg-text-tertiary"></div>
                                        <div class="w-1 h-0.5 bg-text-tertiary"></div>
                                        <div class="w-1 h-0.5 bg-text-tertiary"></div>
                                    </div>
                                </div>
                                <div class="flex space-x-6 mt-4">
                                    <button wire:click="$set('volume', 0)" class="text-text-tertiary hover:text-red-500 transition-colors p-2 bg-surface-1 rounded-lg border border-border-subtle shadow-sm active:shadow-inner active:translate-y-0.5">
                                        <x-heroicon-o-speaker-x-mark class="w-5 h-5" />
                                    </button>
                                    <button wire:click="$set('volume', 100)" class="text-text-tertiary hover:text-signal-500 transition-colors p-2 bg-surface-1 rounded-lg border border-border-subtle shadow-sm active:shadow-inner active:translate-y-0.5">
                                        <x-heroicon-o-speaker-wave class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Orientation Control -->
                        <div class="bg-surface-2 rounded-2xl p-6 flex flex-col justify-between border border-border-subtle min-h-[300px]">
                            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-text-tertiary mb-6">
                                <span>Orientation</span>
                            </div>

                            <div class="flex space-x-4 flex-1 items-center justify-center">
                                <button wire:click="setOrientation('landscape')" class="flex flex-col items-center justify-center w-24 h-24 rounded-xl border-2 transition-all {{ $screen->orientation === 'landscape' ? 'border-signal-500 bg-signal-500/10 text-signal-500 shadow-[inset_0_0_15px_rgba(239,68,68,0.2)]' : 'border-border-subtle text-text-tertiary hover:border-text-secondary hover:bg-surface-3' }}">
                                    <div class="w-10 h-6 border-2 border-current rounded mb-2 transition-all"></div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Land</span>
                                </button>
                                <button wire:click="setOrientation('portrait')" class="flex flex-col items-center justify-center w-24 h-24 rounded-xl border-2 transition-all {{ $screen->orientation === 'portrait' ? 'border-signal-500 bg-signal-500/10 text-signal-500 shadow-[inset_0_0_15px_rgba(239,68,68,0.2)]' : 'border-border-subtle text-text-tertiary hover:border-text-secondary hover:bg-surface-3' }}">
                                    <div class="w-6 h-10 border-2 border-current rounded mb-2 transition-all"></div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Port</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Currently running override and default media -->
                <x-ui.card class="p-6">
                    <h3 class="text-lg font-semibold text-text-primary mb-4">Content Control</h3>

                    <div class="space-y-6">
                        <!-- Override Row -->
                        <div class="p-5 rounded-2xl border {{ $screen->currentMedia ? 'border-amber-500/30 bg-amber-500/[0.01]' : 'border-border-subtle bg-surface-2' }}">
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-500/10 text-amber-500 border border-amber-500/20">Override</span>
                                        <h4 class="font-semibold text-text-primary">Instant Media Override</h4>
                                    </div>
                                    <p class="text-sm text-text-secondary">Force-display a single media asset immediately, bypassing all default playlists or campaigns.</p>
                                </div>
                                @if($screen->currentMedia)
                                    <x-ui.button variant="outline" size="sm" class="text-red-600 border-red-600/30" wire:click="clearOverride">
                                        Clear Override
                                    </x-ui.button>
                                @else
                                    <x-ui.button variant="outline" size="sm" x-on:click="$dispatch('open-modal', 'set-current-media')">
                                        Set Override
                                    </x-ui.button>
                                @endif
                            </div>

                            @if($screen->currentMedia)
                                <div class="mt-4 flex items-center space-x-4 bg-surface-2 p-3 rounded-xl border border-border-subtle">
                                    <div class="w-16 h-12 bg-surface-3 rounded-lg flex items-center justify-center shrink-0 overflow-hidden text-text-tertiary">
                                        @if(str_starts_with($screen->currentMedia->mime_type, 'image/'))
                                            <x-heroicon-o-photo class="w-6 h-6" />
                                        @else
                                            <x-heroicon-o-video-camera class="w-6 h-6" />
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-sm font-semibold text-text-primary truncate">{{ $screen->currentMedia->name }}</h5>
                                        <p class="text-xs text-text-tertiary font-mono uppercase">{{ $screen->currentMedia->type }} &bull; {{ $screen->currentMedia->mime_type }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 text-sm text-text-tertiary italic">
                                    No active override. The screen is displaying standard campaign/playlist content.
                                </div>
                            @endif
                        </div>

                        <!-- Default Media Row -->
                        <div class="p-5 rounded-2xl border border-border-subtle bg-surface-2">
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <h4 class="font-semibold text-text-primary">Fallback Default Media</h4>
                                    <p class="text-sm text-text-secondary">The media shown when no campaigns or playlists are scheduled.</p>
                                </div>
                                <x-ui.button variant="outline" size="sm" x-on:click="$dispatch('open-modal', 'set-default-media')">
                                    Change Default
                                </x-ui.button>
                            </div>

                            @if($screen->defaultMedia)
                                <div class="mt-4 flex items-center space-x-4 bg-surface-1 p-3 rounded-xl border border-border-subtle">
                                    <div class="w-16 h-12 bg-surface-3 rounded-lg flex items-center justify-center shrink-0 overflow-hidden text-text-tertiary">
                                        @if(str_starts_with($screen->defaultMedia->mime_type, 'image/'))
                                            <x-heroicon-o-photo class="w-6 h-6" />
                                        @else
                                            <x-heroicon-o-video-camera class="w-6 h-6" />
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-sm font-semibold text-text-primary truncate">{{ $screen->defaultMedia->name }}</h5>
                                        <p class="text-xs text-text-tertiary font-mono uppercase">{{ $screen->defaultMedia->type }} &bull; {{ $screen->defaultMedia->mime_type }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 text-sm text-text-tertiary italic">
                                    No fallback default media configured. Screen will go black when idle.
                                </div>
                            @endif
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Right hardware parameters deck -->
            <div class="space-y-6">
                <x-ui.card class="p-6">
                    <h3 class="text-sm font-semibold text-text-primary mb-4">Hardware Info</h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between py-1.5 border-b border-border-subtle">
                            <span class="text-text-tertiary">Device ID</span>
                            <span class="text-text-secondary font-mono text-xs select-all">{{ $screen->device_id ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-border-subtle">
                            <span class="text-text-tertiary">Resolution</span>
                            <span class="text-text-secondary font-medium">{{ $screen->resolution ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-border-subtle">
                            <span class="text-text-tertiary">Orientation</span>
                            <span class="text-text-secondary capitalize font-medium">{{ $screen->orientation ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-border-subtle">
                            <span class="text-text-tertiary">Player Version</span>
                            <span class="text-text-secondary font-mono">{{ $screen->player_version ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-border-subtle">
                            <span class="text-text-tertiary">Last Heartbeat</span>
                            <span class="text-text-secondary">{{ $screen->last_heartbeat_at ? \Carbon\Carbon::parse($screen->last_heartbeat_at)->diffForHumans() : 'Never' }}</span>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>

    <!-- MODAL: Set Default Media -->
    <x-ui.modal name="set-default-media" title="Select Fallback Default Media">
        <div class="p-6">
            <h3 class="text-lg font-medium text-text-primary mb-4">Select Fallback Media Asset</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                @foreach($media as $item)
                    <button wire:click="setDefaultMedia({{ $item->id }})" class="flex items-center space-x-3 p-3 rounded-lg border border-border-subtle hover:border-signal-500 hover:bg-surface-2 transition-all text-left">
                        <div class="w-12 h-12 bg-surface-3 rounded-lg flex items-center justify-center shrink-0 overflow-hidden text-text-tertiary">
                            @if(str_starts_with($item->mime_type, 'image/'))
                                <x-heroicon-o-photo class="w-6 h-6" />
                            @else
                                <x-heroicon-o-video-camera class="w-6 h-6" />
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-semibold text-text-primary truncate">{{ $item->name }}</h4>
                            <span class="text-xs text-text-tertiary font-mono uppercase">{{ $item->type }}</span>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    </x-ui.modal>

    <!-- MODAL: Set Override Media -->
    <x-ui.modal name="set-current-media" title="Select Override Media">
        <div class="p-6">
            <h3 class="text-lg font-medium text-text-primary mb-4">Select Override Media Asset</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                @foreach($media as $item)
                    <button wire:click="setCurrentMedia({{ $item->id }})" class="flex items-center space-x-3 p-3 rounded-lg border border-border-subtle hover:border-signal-500 hover:bg-surface-2 transition-all text-left">
                        <div class="w-12 h-12 bg-surface-3 rounded-lg flex items-center justify-center shrink-0 overflow-hidden text-text-tertiary">
                            @if(str_starts_with($item->mime_type, 'image/'))
                                <x-heroicon-o-photo class="w-6 h-6" />
                            @else
                                <x-heroicon-o-video-camera class="w-6 h-6" />
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-semibold text-text-primary truncate">{{ $item->name }}</h4>
                            <span class="text-xs text-text-tertiary font-mono uppercase">{{ $item->type }}</span>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    </x-ui.modal>
</div>
