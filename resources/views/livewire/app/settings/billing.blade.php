<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Billing & Subscriptions</h1>
            <p class="text-text-secondary mt-1">Manage your plan and billing details.</p>
        </div>
    </div>

    <!-- Current Plan Overview -->
    <x-ui.card>
        <div class="p-6 border-b border-surface-2 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-text-primary">Current Plan</h2>
                <p class="text-text-secondary mt-1">You are currently on the <span class="font-bold text-signal-600">{{ $plan ? $plan->name : 'Free' }}</span> plan.
                    @if($plan && $plan->price_inr > 0)
                        <span class="text-text-tertiary ml-1">({{ $plan->formatted_price }})</span>
                    @endif
                </p>
            </div>
            <div>
                <x-ui.button href="{{ route('web.pricing') }}">
                    Upgrade Plan
                </x-ui.button>
            </div>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-text-tertiary mb-2">Screen Usage</h3>
                @php
                    $screenCount = $organization->screens()->count();
                    $maxScreens = $plan ? $plan->max_screens : 1;
                    $isUnlimitedScreens = $maxScreens >= PHP_INT_MAX;
                    $screenPercentage = $isUnlimitedScreens ? 0 : min(100, ($screenCount / max(1, $maxScreens)) * 100);
                @endphp
                <div class="flex items-end justify-between mb-1">
                    <span class="text-2xl font-bold text-text-primary">{{ $screenCount }}</span>
                    <span class="text-sm text-text-secondary mb-1">/ {{ $isUnlimitedScreens ? '∞' : $maxScreens }} Screens</span>
                </div>
                <div class="w-full bg-surface-2 rounded-full h-2">
                    @if(!$isUnlimitedScreens)
                        <div class="bg-signal-500 h-2 rounded-full transition-all" style="width: {{ $screenPercentage }}%"></div>
                    @else
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 100%"></div>
                    @endif
                </div>
                @if($isUnlimitedScreens)
                    <p class="text-xs text-emerald-600 mt-1 font-medium">Unlimited screens on your plan</p>
                @endif
            </div>
            <div>
                <h3 class="text-sm font-medium text-text-tertiary mb-2">Storage Usage</h3>
                @php
                    $usedStorage = $organization->mediaAssets()->sum('size');
                    $maxStorage = app(\App\Services\BillingService::class)->getStorageLimit($organization);
                    $storagePercentage = min(100, ($usedStorage / max(1, $maxStorage)) * 100);
                @endphp
                <div class="flex items-end justify-between mb-1">
                    <span class="text-2xl font-bold text-text-primary">{{ number_format($usedStorage / 1048576, 2) }} MB</span>
                    <span class="text-sm text-text-secondary mb-1">/ {{ number_format($maxStorage / 1073741824, 1) }} GB</span>
                </div>
                <div class="w-full bg-surface-2 rounded-full h-2">
                    <div class="bg-signal-500 h-2 rounded-full transition-all" style="width: {{ $storagePercentage }}%"></div>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Subscription Status -->
    <x-ui.card>
        <div class="p-6 border-b border-surface-2 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-text-primary">Subscription Details</h2>
                <p class="text-text-secondary mt-1">Your subscription status and renewal information.</p>
            </div>
            @if($subscription && $subscription->status === 'active')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                    Active
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-600 border border-red-500/20">
                    Inactive
                </span>
            @endif
        </div>
        <div class="p-6">
            @if($subscription && $plan)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-text-tertiary">Plan Name</p>
                        <p class="text-lg font-semibold text-text-primary mt-1">{{ $plan->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-text-tertiary">Billing Cycle</p>
                        <p class="text-lg font-semibold text-text-primary mt-1 capitalize">{{ $plan->billing_cycle ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-text-tertiary">Next Renewal</p>
                        <p class="text-lg font-semibold text-text-primary mt-1">{{ $subscription->ends_at ? $subscription->ends_at->format('M j, Y') : 'Auto-renewing' }}</p>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <x-ui.button href="{{ route('web.pricing') }}" variant="outline">
                        <x-heroicon-o-arrow-path class="w-4 h-4 mr-2" />
                        Change Plan
                    </x-ui.button>
                    <x-ui.button href="{{ route('app.support.create') }}?subject=Cancellation+Request&priority=high" variant="outline" class="text-red-600 border-red-500/30 hover:bg-red-500/5">
                        Cancel Subscription
                    </x-ui.button>
                </div>
            @else
                <div class="text-center py-8">
                    <x-heroicon-o-credit-card class="w-12 h-12 text-text-tertiary mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-text-primary mb-2">No Active Subscription</h3>
                    <p class="text-sm text-text-secondary mb-6">Upgrade to unlock more screens, storage, and features.</p>
                    <x-ui.button href="{{ route('web.pricing') }}">
                        View Plans
                    </x-ui.button>
                </div>
            @endif
        </div>
    </x-ui.card>

    <!-- Billing History -->
    <x-ui.card>
        <div class="p-6 border-b border-surface-2">
            <h2 class="text-lg font-semibold text-text-primary">Billing History</h2>
            <p class="text-text-secondary mt-1">View your previous invoices and payment receipts.</p>
        </div>
        <div class="p-6">
            <p class="text-sm text-text-tertiary text-center py-4">No invoices found yet.</p>
        </div>
    </x-ui.card>
</div>
