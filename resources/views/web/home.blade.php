<x-layouts.web.app title="NexView | Modern Digital Signage">
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
            <div class="text-center">
                <h1 class="text-4xl sm:text-6xl font-extrabold text-gray-900 dark:text-text-primary tracking-tight mb-6">
                    Digital Signage <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-signal-500 to-signal-700">Reimagined</span>
                </h1>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-text-secondary mx-auto">
                    Manage all your screens from one place with real-time updates, powerful campaigns, and stunning visuals.
                </p>
                <div class="mt-10 flex justify-center gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-signal-600 hover:bg-signal-700 transition-colors">
                        Start Free Trial
                    </a>
                    <a href="{{ route('web.features') }}" class="px-8 py-3 border border-gray-300 dark:border-surface-2 text-base font-medium rounded-full text-gray-700 dark:text-text-secondary bg-white dark:bg-surface-2 hover:bg-gray-50 dark:hover:bg-surface-3 transition-colors">
                        Explore Features
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.web.app>
