<div x-data="{ open: !sessionStorage.getItem('dismissed-onboarding') }" class="fixed bottom-6 right-6 z-50">
    <!-- Floating Onboarding Toggle Button -->
    <button @click="open = true" class="flex items-center space-x-2 bg-gradient-to-r from-signal-600 to-indigo-600 text-white px-5 py-3.5 rounded-full shadow-glow-signal hover:shadow-2xl hover:scale-105 transition-all duration-300">
        <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 {{ $isComplete ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
        </span>
        <span class="font-semibold text-sm tracking-wide">Onboarding Checklist ({{ $completedCount }}/5)</span>
    </button>

    <!-- Slide-over drawer -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         x-cloak
         class="fixed inset-y-0 right-0 w-full max-w-md bg-[#0F0F25]/95 backdrop-blur-xl border-l border-white/10 shadow-2xl z-50 flex flex-col p-6 text-white overflow-hidden">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-white/10">
            <div>
                <h3 class="text-xl font-bold">Onboarding Setup</h3>
                <p class="text-xs text-white/50">Setup your digital signage network</p>
            </div>
            <button @click="open = false; sessionStorage.setItem('dismissed-onboarding', 'true')" class="text-white/60 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/5">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Progress Bar -->
        <div class="mt-6">
            <div class="flex justify-between text-sm mb-1.5 font-medium">
                <span class="text-white/70">Overall Progress</span>
                <span class="text-emerald-400">{{ round(($completedCount / 5) * 100) }}%</span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-2">
                <div class="bg-gradient-to-r from-signal-500 to-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ ($completedCount / 5) * 100 }}%"></div>
            </div>
        </div>

        <!-- Steps list -->
        <div class="mt-6 flex-1 overflow-y-auto space-y-4 pr-1">
            @php
                $steps = [
                    [
                        'id' => 'location',
                        'title' => 'Create a Location',
                        'description' => 'Locations group your screens (e.g., "Downtown Branch").',
                        'isComplete' => $progress['hasLocation'],
                        'actionUrl' => route('app.locations.create', ['wizard' => true]),
                        'actionLabel' => 'Add Location'
                    ],
                    [
                        'id' => 'screen',
                        'title' => 'Provision a Screen',
                        'description' => 'Register a display and assign it to your location.',
                        'isComplete' => $progress['hasScreen'],
                        'actionUrl' => route('app.screens.index', ['add' => true, 'wizard' => true]),
                        'actionLabel' => 'Add Screen'
                    ],
                    [
                        'id' => 'media',
                        'title' => 'Upload Media',
                        'description' => 'Upload images or videos to play on your screens.',
                        'isComplete' => $progress['hasMedia'],
                        'actionUrl' => route('app.media.index', ['wizard' => true]),
                        'actionLabel' => 'Upload Media'
                    ],
                    [
                        'id' => 'playlist',
                        'title' => 'Create a Playlist',
                        'description' => 'Group your media assets into a playlist.',
                        'isComplete' => $progress['hasPlaylist'],
                        'actionUrl' => route('app.playlists.create', ['wizard' => true]),
                        'actionLabel' => 'Create Playlist'
                    ],
                    [
                        'id' => 'campaign',
                        'title' => 'Launch a Campaign',
                        'description' => 'Schedule your playlist to play on screens.',
                        'isComplete' => $progress['hasCampaign'],
                        'actionUrl' => route('app.campaigns.create', ['wizard' => true]),
                        'actionLabel' => 'Create Campaign'
                    ]
                ];
            @endphp

            @foreach($steps as $index => $step)
                @php
                    $isNextStep = !$step['isComplete'] && ($index === 0 || $steps[$index - 1]['isComplete']);
                @endphp
                
                <div class="p-4 rounded-2xl border transition-all duration-300 {{ $step['isComplete'] ? 'bg-white/5 border-emerald-500/20' : ($isNextStep ? 'bg-white/10 border-signal-500/40 ring-1 ring-signal-500/30' : 'bg-white/5 border-white/5 opacity-60') }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full flex-shrink-0 mt-0.5 {{ $step['isComplete'] ? 'bg-emerald-500/20 text-emerald-400' : ($isNextStep ? 'bg-signal-500/20 text-signal-400' : 'bg-white/5 text-white/30') }}">
                            @if($step['isComplete'])
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            @else
                                <span class="text-xs font-bold">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold {{ $step['isComplete'] ? 'text-white/60 line-through' : 'text-white' }}">
                                {{ $step['title'] }}
                            </h4>
                            <p class="text-xs text-white/50 mt-0.5 leading-relaxed">{{ $step['description'] }}</p>
                            
                            @if(!$step['isComplete'] && $isNextStep)
                                <div class="mt-3">
                                    <a href="{{ $step['actionUrl'] }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-signal-600 hover:bg-signal-500 text-white transition-colors">
                                        {{ $step['actionLabel'] }}
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Complete Button -->
        <div class="pt-6 border-t border-white/10 mt-auto">
            @if($isComplete)
                <button wire:click="complete" class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition-all shadow-md shadow-emerald-500/20 text-sm tracking-wide">
                    Finish Setup & Go to Dashboard
                </button>
            @else
                <button disabled class="w-full py-3 bg-white/5 border border-white/10 text-white/30 font-semibold rounded-xl cursor-not-allowed text-sm">
                    Complete all steps to continue
                </button>
            @endif
        </div>
    </div>

    <!-- Backdrop for drawer -->
    <div x-show="open" @click="open = false; sessionStorage.setItem('dismissed-onboarding', 'true')" x-cloak class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40"></div>
</div>
