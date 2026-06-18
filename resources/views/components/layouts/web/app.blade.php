<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'NexView | Modern Digital Signage' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        // Check local storage or system preference for dark mode
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="antialiased bg-white dark:bg-surface-1 text-gray-900 dark:text-text-primary transition-colors duration-200">

    <!-- Header -->
    <header class="fixed w-full z-50 bg-white/80 dark:bg-surface-1/80 backdrop-blur-md border-b border-gray-100 dark:border-surface-2 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('web.home') }}" class="flex items-center space-x-2 text-signal-600 font-bold text-xl tracking-tight">
                        <x-heroicon-s-sparkles class="w-6 h-6" />
                        <span>NexView</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('web.features') }}" class="text-gray-600 dark:text-text-secondary hover:text-signal-600 dark:hover:text-signal-400 font-medium transition-colors">Features</a>
                    <a href="{{ route('web.pricing') }}" class="text-gray-600 dark:text-text-secondary hover:text-signal-600 dark:hover:text-signal-400 font-medium transition-colors">Pricing</a>
                </nav>

                <!-- Right Side (Auth & Theme) -->
                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle -->
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-text-tertiary hover:bg-gray-100 dark:hover:bg-surface-2 focus:outline-none rounded-lg text-sm p-2 transition-colors">
                        <x-heroicon-o-moon id="theme-toggle-dark-icon" class="hidden w-5 h-5" />
                        <x-heroicon-o-sun id="theme-toggle-light-icon" class="hidden w-5 h-5" />
                    </button>

                    @auth
                        <a href="{{ route('app.dashboard') }}" class="text-sm font-medium text-gray-600 dark:text-text-secondary hover:text-signal-600 dark:hover:text-signal-400">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-text-secondary hover:text-signal-600 dark:hover:text-signal-400">Log in</a>
                        <a href="{{ route('register') }}" class="ml-4 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-signal-600 hover:bg-signal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-signal-500 transition-colors">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-16 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 dark:bg-surface-0 border-t border-gray-200 dark:border-surface-2 transition-colors duration-200 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <span class="flex items-center space-x-2 text-signal-600 font-bold text-xl tracking-tight mb-4">
                        <x-heroicon-s-sparkles class="w-6 h-6" />
                        <span>NexView</span>
                    </span>
                    <p class="text-gray-500 dark:text-text-tertiary text-sm max-w-xs">
                        The modern, real-time digital signage platform for growing organizations.
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-text-primary tracking-wider uppercase mb-4">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('web.features') }}" class="text-sm text-gray-500 dark:text-text-secondary hover:text-signal-600 dark:hover:text-signal-400">Features</a></li>
                        <li><a href="{{ route('web.pricing') }}" class="text-sm text-gray-500 dark:text-text-secondary hover:text-signal-600 dark:hover:text-signal-400">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-text-primary tracking-wider uppercase mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('web.privacy') }}" class="text-sm text-gray-500 dark:text-text-secondary hover:text-signal-600 dark:hover:text-signal-400">Privacy Policy</a></li>
                        <li><a href="{{ route('web.terms') }}" class="text-sm text-gray-500 dark:text-text-secondary hover:text-signal-600 dark:hover:text-signal-400">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-surface-2">
                <p class="text-center text-sm text-gray-400 dark:text-text-tertiary">
                    &copy; {{ date('Y') }} NexView Inc. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Theme toggle logic
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }

            // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        });
    </script>
</body>
</html>
