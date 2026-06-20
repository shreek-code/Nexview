<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-300">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'NexView') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-bg-base text-text-primary" x-data="{ isDark: JSON.parse(localStorage.getItem('darkMode') ?? 'false') }" x-init="document.documentElement.classList.toggle('dark', isDark)">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">
        
        <!-- Mobile overlay -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black/50 md:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar Shell -->
        <aside class="w-[260px] m-4 mr-0 rounded-3xl neumorphic flex-col overflow-hidden transition-all" :class="sidebarOpen ? 'fixed inset-y-0 left-0 z-50 flex ml-4' : 'hidden md:flex'">
            <div class="h-20 flex items-center px-8">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-signal-600 to-signal-400 flex items-center justify-center shadow-glow-signal">
                        <span class="text-white font-bold text-lg leading-none">N</span>
                    </div>
                    <span class="font-bold text-xl text-text-primary tracking-tight">NexView</span>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-8">
                <!-- Organization Section -->
                <div>
                    <div class="px-4 mb-3 text-xs font-semibold uppercase tracking-wider text-text-tertiary">Organization</div>
                    <nav class="space-y-1">
                        <a href="{{ route('app.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('app.dashboard') ? 'neumorphic-inset text-signal-600' : 'text-text-secondary hover:bg-surface-3 hover:text-text-primary' }}">
                            <x-heroicon-o-squares-2x2 class="h-5 w-5" />
                            <span>Dashboard</span>
                        </a>
                        @if(auth()->user() && auth()->user()->role === 'admin')
                        <a href="{{ route('app.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('app.users.*') ? 'neumorphic-inset text-signal-600' : 'text-text-secondary hover:bg-surface-3 hover:text-text-primary' }}">
                            <x-heroicon-o-users class="h-5 w-5" />
                            <span>Team</span>
                        </a>
                        @endif
                    </nav>
                </div>

                <!-- Infrastructure Section -->
                <div>
                    <div class="px-4 mb-3 text-xs font-semibold uppercase tracking-wider text-text-tertiary">Infrastructure</div>
                    <nav class="space-y-1">
                        <a href="{{ route('app.locations.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('app.locations.*') ? 'neumorphic-inset text-signal-600' : 'text-text-secondary hover:bg-surface-3 hover:text-text-primary' }}">
                            <x-heroicon-o-map-pin class="h-5 w-5" />
                            <span>Locations</span>
                        </a>
                        <a href="{{ route('app.screens.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('app.screens.*') ? 'neumorphic-inset text-signal-600' : 'text-text-secondary hover:bg-surface-3 hover:text-text-primary' }}">
                            <x-heroicon-o-computer-desktop class="h-5 w-5" />
                            <span>Screens</span>
                        </a>
                    </nav>
                </div>

                <!-- Content Section -->
                <div>
                    <div class="px-4 mb-3 text-xs font-semibold uppercase tracking-wider text-text-tertiary">Content</div>
                    <nav class="space-y-1">
                        <a href="{{ route('app.media.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('app.media.*') ? 'neumorphic-inset text-signal-600' : 'text-text-secondary hover:bg-surface-3 hover:text-text-primary' }}">
                            <x-heroicon-o-play class="h-5 w-5" />
                            <span>Media</span>
                        </a>
                        <a href="{{ route('app.playlists.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('app.playlists.*') ? 'neumorphic-inset text-signal-600' : 'text-text-secondary hover:bg-surface-3 hover:text-text-primary' }}">
                            <x-heroicon-o-rectangle-stack class="h-5 w-5" />
                            <span>Playlists</span>
                        </a>
                        <a href="{{ route('app.campaigns.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('app.campaigns.*') ? 'neumorphic-inset text-signal-600' : 'text-text-secondary hover:bg-surface-3 hover:text-text-primary' }}">
                            <x-heroicon-o-calendar class="h-5 w-5" />
                            <span>Campaigns</span>
                        </a>
                    </nav>
                </div>

                <!-- Support Section -->
                <div class="mt-8">
                    <div class="px-4 mb-3 text-xs font-semibold uppercase tracking-wider text-text-tertiary">Help & Support</div>
                    <nav class="space-y-1">
                        <a href="{{ route('app.support.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('app.support.*') ? 'neumorphic-inset text-signal-600' : 'text-text-secondary hover:bg-surface-3 hover:text-text-primary' }}">
                            <x-heroicon-o-lifebuoy class="h-5 w-5" />
                            <span>Support Tickets</span>
                        </a>
                    </nav>
                </div>
            </div>
            
            @if(auth()->user() && auth()->user()->role === 'admin')
            <div class="p-4 mt-auto">
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 text-sm text-text-secondary hover:text-text-primary cursor-pointer transition-colors rounded-xl hover:bg-surface-3">
                    <x-heroicon-o-cog-8-tooth class="h-5 w-5" />
                    <span>Settings</span>
                </a>
            </div>
            @endif
        </aside>

        <!-- Main Content Shell -->
        <main class="flex-1 flex flex-col min-w-0 px-4 md:px-8 py-4">
            <header class="h-20 rounded-3xl neumorphic flex items-center px-6 md:px-8 justify-between mb-8 z-30">
                <div class="flex items-center">
                    <button class="md:hidden text-text-secondary mr-4 hover:text-text-primary transition-colors" @click="sidebarOpen = true">
                        <x-heroicon-o-bars-3 class="h-6 w-6" />
                    </button>
                    <div class="hidden sm:block"></div>
                </div>
                
                <div class="flex items-center space-x-4 md:space-x-6">
                    <button 
                        @click="isDark = !isDark; document.documentElement.classList.toggle('dark'); localStorage.setItem('darkMode', isDark)"
                        class="w-11 h-11 rounded-full neumorphic flex items-center justify-center text-text-secondary hover:text-signal-600 transition-colors active:shadow-neumorphic-inset dark:active:shadow-neumorphic-inset-dark"
                    >
                        <x-heroicon-o-sun x-show="isDark" x-cloak class="w-5 h-5" />
                        <x-heroicon-o-moon x-show="!isDark" x-cloak class="w-5 h-5" />
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <livewire:shared.notification-bell />

                    <!-- Profile Dropdown -->
                    <div class="relative flex items-center pl-2 border-l border-border-subtle" x-data="{ open: false }" @click.away="open = false">
                        <div class="hidden md:flex flex-col items-end mr-3">
                            <span class="text-sm font-semibold text-text-primary">{{ auth()->user()->name ?? 'User' }}</span>
                            <span class="text-xs text-text-tertiary capitalize">{{ auth()->user() && auth()->user()->role === 'admin' ? 'Organization Admin' : (auth()->user()->role ?? 'Staff') }}</span>
                        </div>
                        <button @click="open = !open" class="h-11 w-11 rounded-full neumorphic flex items-center justify-center text-sm font-bold text-signal-600 cursor-pointer overflow-hidden border-2 border-transparent hover:border-signal-400 transition-all uppercase focus:outline-none">
                            {{ auth()->user() ? substr(auth()->user()->name, 0, 2) : 'US' }}
                        </button>

                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             class="absolute right-0 top-full mt-4 w-56 bg-surface-1 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] border border-border-subtle overflow-hidden z-50 py-2">
                            
                            <div class="px-4 py-3 border-b border-border-subtle md:hidden bg-surface-2 mb-2">
                                <span class="block text-sm font-semibold text-text-primary">{{ auth()->user()->name ?? 'User' }}</span>
                                <span class="block text-xs text-text-tertiary capitalize">{{ auth()->user() && auth()->user()->role === 'admin' ? 'Organization Admin' : (auth()->user()->role ?? 'Staff') }}</span>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-text-secondary hover:bg-surface-2 hover:text-signal-600 transition-colors">
                                <x-heroicon-o-user-circle class="w-5 h-5 mr-3" />
                                My Profile
                            </a>
                            <a href="{{ route('app.settings.index') }}" class="flex items-center px-4 py-2 text-sm text-text-secondary hover:bg-surface-2 hover:text-signal-600 transition-colors">
                                <x-heroicon-o-cog-8-tooth class="w-5 h-5 mr-3" />
                                Account Settings
                            </a>
                            <a href="{{ route('app.billing.index') }}" class="flex items-center px-4 py-2 text-sm text-text-secondary hover:bg-surface-2 hover:text-signal-600 transition-colors">
                                <x-heroicon-o-credit-card class="w-5 h-5 mr-3" />
                                Billing & Subscriptions
                            </a>
                            <div class="h-px bg-border-subtle my-2"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-500/10 transition-colors">
                                    <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 mr-3" />
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <div class="flex-1 overflow-y-auto w-full">
                <div class="fade-in-up">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    @if(auth()->check() && auth()->user()->organization && !auth()->user()->organization->is_onboarded)
        <livewire:app.setup-wizard />
    @endif

    @livewireScripts
</body>
</html>
