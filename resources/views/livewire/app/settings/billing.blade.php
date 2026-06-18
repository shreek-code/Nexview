<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Billing & Subscriptions</h1>
            <p class="text-text-secondary mt-1">Manage your plan, payment methods, and billing history.</p>
        </div>
    </div>

    <!-- Current Plan Overview -->
    <x-ui.card>
        <div class="p-6 border-b border-surface-2 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-text-primary">Current Plan</h2>
                <p class="text-text-secondary mt-1">You are currently on the <span class="font-bold text-signal-600">{{ $plan ? $plan->name : 'Free' }}</span> plan.</p>
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
                <div class="flex items-end justify-between mb-1">
                    <span class="text-2xl font-bold text-text-primary">{{ $organization->screens()->count() }}</span>
                    <span class="text-sm text-text-secondary mb-1">/ {{ $plan ? $plan->max_screens : '∞' }} Screens</span>
                </div>
                <div class="w-full bg-surface-2 rounded-full h-2">
                    @php
                        $screenCount = $organization->screens()->count();
                        $maxScreens = $plan ? $plan->max_screens : 1;
                        $screenPercentage = min(100, ($screenCount / max(1, $maxScreens)) * 100);
                    @endphp
                    <div class="bg-signal-500 h-2 rounded-full" style="width: {{ $screenPercentage }}%"></div>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-medium text-text-tertiary mb-2">Storage Usage</h3>
                <div class="flex items-end justify-between mb-1">
                    @php
                        $usedStorage = $organization->mediaAssets()->sum('size');
                        $maxStorage = app(\App\Services\BillingService::class)->getStorageLimit($organization);
                        $storagePercentage = min(100, ($usedStorage / max(1, $maxStorage)) * 100);
                    @endphp
                    <span class="text-2xl font-bold text-text-primary">{{ number_format($usedStorage / 1048576, 2) }} MB</span>
                    <span class="text-sm text-text-secondary mb-1">/ {{ number_format($maxStorage / 1048576, 0) }} MB</span>
                </div>
                <div class="w-full bg-surface-2 rounded-full h-2">
                    <div class="bg-signal-500 h-2 rounded-full" style="width: {{ $storagePercentage }}%"></div>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Payment Methods -->
    <x-ui.card>
        <div class="p-6 border-b border-surface-2 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-text-primary">Payment Methods</h2>
                <p class="text-text-secondary mt-1">Manage your credit cards and billing information.</p>
            </div>
            <div>
                <x-ui.button variant="outline">
                    Add Method
                </x-ui.button>
            </div>
        </div>
        <div class="p-6">
            <p class="text-sm text-text-tertiary text-center py-4">No payment methods found. Add one to upgrade your plan.</p>
        </div>
    </x-ui.card>

    <!-- Billing History -->
    <x-ui.card>
        <div class="p-6 border-b border-surface-2">
            <h2 class="text-lg font-semibold text-text-primary">Billing History</h2>
            <p class="text-text-secondary mt-1">View and download your previous invoices.</p>
        </div>
        <div class="p-6">
            <p class="text-sm text-text-tertiary text-center py-4">No invoices found yet.</p>
        </div>
    </x-ui.card>
</div>
