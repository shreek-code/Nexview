<x-layouts.guest>
    <div class="min-h-screen bg-bg-base flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8 w-full">
        <div class="w-full max-w-3xl">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-text-primary tracking-tight mb-2">Welcome, {{ $organizationName }}!</h1>
                <p class="text-text-secondary text-lg">Let's get your digital signage network up and running.</p>
            </div>

            @php
                $isComplete = $progress['hasLocation'] && $progress['hasScreen'] && $progress['hasMedia'] && $progress['hasPlaylist'] && $progress['hasCampaign'];

                $steps = [
                    [
                        'id' => 'location',
                        'title' => 'Create Your First Location',
                        'description' => 'Locations group your screens (e.g., "Downtown Branch" or "Lobby").',
                        'isComplete' => $progress['hasLocation'],
                        'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>',
                        'actionUrl' => route('app.locations.create', ['wizard' => true] ?? '#'),
                        'actionLabel' => 'Add Location'
                    ],
                    [
                        'id' => 'screen',
                        'title' => 'Provision a Screen',
                        'description' => 'Register a new display and assign it to your location.',
                        'isComplete' => $progress['hasScreen'],
                        'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>',
                        'actionUrl' => route('app.screens.create', ['wizard' => true] ?? '#'),
                        'actionLabel' => 'Add Screen'
                    ],
                    [
                        'id' => 'media',
                        'title' => 'Upload Media',
                        'description' => 'Upload images or videos to play on your screens.',
                        'isComplete' => $progress['hasMedia'],
                        'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>',
                        'actionUrl' => route('app.media.index', ['wizard' => true] ?? '#'),
                        'actionLabel' => 'Upload Media'
                    ],
                    [
                        'id' => 'playlist',
                        'title' => 'Create a Playlist',
                        'description' => 'Group your media assets into a reusable playlist.',
                        'isComplete' => $progress['hasPlaylist'],
                        'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6zm0 6h.008v.008H6V12zm0 6h.008v.008H6V18zm6-12h.008v.008H12V6zm0 6h.008v.008H12V12zm0 6h.008v.008H12V18zm6-12h.008v.008H18V6zm0 6h.008v.008H18V12z" /></svg>',
                        'actionUrl' => route('app.playlists.create', ['wizard' => true] ?? '#'),
                        'actionLabel' => 'Create Playlist'
                    ],
                    [
                        'id' => 'campaign',
                        'title' => 'Launch a Campaign',
                        'description' => 'Schedule your playlist to play on your screens.',
                        'isComplete' => $progress['hasCampaign'],
                        'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" /></svg>',
                        'actionUrl' => route('app.campaigns.create', ['wizard' => true] ?? '#'),
                        'actionLabel' => 'Create Campaign'
                    ]
                ];
            @endphp

            <div class="space-y-6">
                @foreach($steps as $index => $step)
                    @php
                        $isNextStep = !$step['isComplete'] && ($index === 0 || $steps[$index - 1]['isComplete']);
                    @endphp
                    
                    <x-ui.card class="border-none transition-all duration-300 {{ $isNextStep ? 'ring-2 ring-signal-500' : '' }} {{ $step['isComplete'] ? 'opacity-80' : '' }}">
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center space-x-6">
                                <div class="p-3 rounded-full {{ $step['isComplete'] ? 'bg-emerald-500/20 text-emerald-500' : ($isNextStep ? 'bg-signal-500/20 text-signal-500' : 'bg-surface-2 text-text-tertiary') }}">
                                    @if($step['isComplete'])
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @else
                                        {!! $step['icon'] !!}
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold {{ $step['isComplete'] ? 'text-text-secondary line-through' : 'text-text-primary' }}">
                                        {{ $step['title'] }}
                                    </h3>
                                    <p class="text-text-secondary mt-1">{{ $step['description'] }}</p>
                                </div>
                            </div>
                            <div>
                                @if(!$step['isComplete'] && $isNextStep)
                                    <x-ui.button :href="$step['actionUrl']">
                                        {{ $step['actionLabel'] }}
                                    </x-ui.button>
                                @endif
                            </div>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <form method="POST" action="{{ route('app.setup.complete') }}">
                    @csrf
                    <x-ui.button 
                        size="lg" 
                        type="submit"
                        :disabled="!$isComplete" 
                        class="{{ $isComplete ? 'bg-emerald-500 hover:bg-emerald-600 text-white' : '' }}"
                    >
                        {{ $isComplete ? "Finish Setup & Go to Dashboard" : "Complete all steps to continue" }}
                    </x-ui.button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.guest>
