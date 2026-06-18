<x-layouts.web.app title="Pricing | NexView">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-text-primary tracking-tight mb-4">Simple, Transparent Pricing</h1>
        <p class="text-gray-500 dark:text-text-secondary mb-12">Pay only for the screens you use. No hidden fees.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto text-left">
            @foreach($plans as $index => $plan)
            <div class="bg-surface-0 dark:bg-surface-2 p-8 rounded-3xl border {{ $index === 1 ? 'border-2 border-signal-500 relative shadow-xl' : 'border-gray-100 dark:border-surface-3' }} flex flex-col">
                @if($index === 1)
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-signal-500 text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Most Popular</div>
                @endif
                <h3 class="text-xl font-bold text-gray-900 dark:text-text-primary">{{ $plan->name }}</h3>
                <p class="text-gray-500 dark:text-text-secondary mt-2">
                    {{ $plan->billing_cycle === 'yearly' ? 'Billed annually' : 'One-time payment' }}
                </p>
                <div class="mt-4 mb-6">
                    <span class="text-4xl font-extrabold text-gray-900 dark:text-text-primary">Custom</span>
                    <span class="text-gray-500 dark:text-text-secondary"> pricing</span>
                </div>
                <ul class="space-y-4 mb-8 flex-1 text-sm">
                    {{-- Screens Limit --}}
                    <li class="flex items-center text-gray-600 dark:text-text-secondary">
                        <x-heroicon-o-check class="w-5 h-5 text-signal-500 mr-2"/> 
                        @if(isset($plan->limits['screens']['unlimited']) && $plan->limits['screens']['unlimited'])
                            Unlimited Screens
                        @elseif(isset($plan->limits['screens']['min']) && isset($plan->limits['screens']['max']))
                            {{ $plan->limits['screens']['min'] }} to {{ $plan->limits['screens']['max'] }} Screens
                        @elseif(isset($plan->limits['screens']['max']))
                            Up to {{ $plan->limits['screens']['max'] }} Screens
                        @endif
                    </li>
                    
                    {{-- Locations Limit --}}
                    <li class="flex items-center text-gray-600 dark:text-text-secondary">
                        <x-heroicon-o-check class="w-5 h-5 text-signal-500 mr-2"/> 
                        @if(isset($plan->limits['locations']['unlimited']) && $plan->limits['locations']['unlimited'])
                            Unlimited Locations
                        @else
                            {{ $plan->limits['locations'] ?? 0 }} Locations
                        @endif
                    </li>

                    {{-- Storage Limit --}}
                    <li class="flex items-center text-gray-600 dark:text-text-secondary">
                        <x-heroicon-o-check class="w-5 h-5 text-signal-500 mr-2"/> 
                        @if(isset($plan->limits['storage_gb']))
                            {{ $plan->limits['storage_gb'] }} GB Storage
                        @elseif(isset($plan->limits['storage_mb']))
                            {{ $plan->limits['storage_mb'] }} MB Storage
                        @endif
                    </li>

                    {{-- Remote Access --}}
                    <li class="flex items-center text-gray-600 dark:text-text-secondary">
                        @if($plan->remote_access)
                            <x-heroicon-o-check class="w-5 h-5 text-signal-500 mr-2"/> Full Remote Access
                        @else
                            <x-heroicon-o-exclamation-circle class="w-5 h-5 text-yellow-500 mr-2"/> Same-Network Control Only
                        @endif
                    </li>

                    {{-- Analytics --}}
                    @if(isset($plan->analytics['proof_of_play']) && $plan->analytics['proof_of_play'])
                    <li class="flex items-center text-gray-600 dark:text-text-secondary">
                        <x-heroicon-o-check class="w-5 h-5 text-signal-500 mr-2"/> Proof of Play Analytics
                    </li>
                    @endif
                </ul>
                @auth
                    <a href="{{ route('app.onboarding', ['plan_id' => $plan->id, 'cycle' => $plan->billing_cycle]) }}" class="w-full block text-center px-6 py-3 {{ $index === 1 ? 'bg-signal-600 text-white hover:bg-signal-700' : 'bg-gray-100 dark:bg-surface-3 text-gray-900 dark:text-text-primary hover:bg-gray-200 dark:hover:bg-surface-4' }} rounded-full font-medium transition-colors">Select Plan</a>
                @else
                    <a href="{{ route('register', ['plan_id' => $plan->id, 'cycle' => $plan->billing_cycle]) }}" class="w-full block text-center px-6 py-3 {{ $index === 1 ? 'bg-signal-600 text-white hover:bg-signal-700' : 'bg-gray-100 dark:bg-surface-3 text-gray-900 dark:text-text-primary hover:bg-gray-200 dark:hover:bg-surface-4' }} rounded-full font-medium transition-colors">Select Plan</a>
                @endauth
            </div>
            @endforeach
        </div>
    </div>
</x-layouts.web.app>
