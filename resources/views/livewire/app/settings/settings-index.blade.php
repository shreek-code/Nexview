<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Account Settings</h1>
        <p class="text-text-secondary mt-1">Manage your organization details, subscriptions, and billing.</p>
    </div>

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Navigation -->
        <div class="w-full md:w-64 flex-shrink-0">
            <nav class="space-y-1">
                <button wire:click="setTab('general')" class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all {{ $currentTab === 'general' ? 'bg-surface-2 text-signal-600 shadow-sm border border-border-subtle' : 'text-text-secondary hover:bg-surface-2 hover:text-text-primary' }}">
                    <x-heroicon-o-building-office class="w-5 h-5 mr-3" />
                    Organization
                </button>
                <button wire:click="setTab('subscription')" class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all {{ $currentTab === 'subscription' ? 'bg-surface-2 text-signal-600 shadow-sm border border-border-subtle' : 'text-text-secondary hover:bg-surface-2 hover:text-text-primary' }}">
                    <x-heroicon-o-credit-card class="w-5 h-5 mr-3" />
                    My Subscriptions
                </button>
                <button wire:click="setTab('billing')" class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all {{ $currentTab === 'billing' ? 'bg-surface-2 text-signal-600 shadow-sm border border-border-subtle' : 'text-text-secondary hover:bg-surface-2 hover:text-text-primary' }}">
                    <x-heroicon-o-document-text class="w-5 h-5 mr-3" />
                    Billing History
                </button>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="flex-1">
            @if($currentTab === 'general')
                <x-ui.card class="p-6">
                    <h2 class="text-lg font-medium text-text-primary mb-4">Organization Profile</h2>
                    <div class="space-y-4 max-w-lg">
                        <div>
                            <x-ui.label value="Organization Name" />
                            <x-ui.input type="text" value="{{ $organization->name }}" class="block w-full mt-1" disabled />
                        </div>
                        <p class="text-sm text-text-tertiary">To change your organization name, please contact support.</p>
                    </div>
                </x-ui.card>
            @elseif($currentTab === 'subscription')
                <x-ui.card class="p-6">
                    <h2 class="text-lg font-medium text-text-primary mb-4">Subscription Plan</h2>
                    @if($subscription && $subscription->plan)
                        <div class="bg-surface-2 border border-border-subtle rounded-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-text-primary">{{ $subscription->plan->name }}</h3>
                                    <p class="text-sm text-text-secondary">Currently active</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-text-primary">${{ number_format($subscription->plan->price_monthly, 2) }}<span class="text-sm font-normal text-text-tertiary">/mo</span></p>
                                </div>
                            </div>
                            <x-ui.button>Upgrade Plan</x-ui.button>
                        </div>
                    @else
                        <div class="bg-surface-2 border border-border-subtle rounded-xl p-6 text-center">
                            <x-heroicon-o-credit-card class="w-12 h-12 text-text-tertiary mx-auto mb-4" />
                            <h3 class="text-lg font-medium text-text-primary mb-2">No Active Subscription</h3>
                            <p class="text-sm text-text-secondary mb-4">You are currently on the basic free tier. Upgrade to unlock more features.</p>
                            <x-ui.button>View Plans</x-ui.button>
                        </div>
                    @endif
                </x-ui.card>
            @elseif($currentTab === 'billing')
                <x-ui.card class="p-6">
                    <h2 class="text-lg font-medium text-text-primary mb-4">Billing History</h2>
                    <div class="text-center py-8 text-text-secondary text-sm">
                        <x-heroicon-o-document-text class="w-10 h-10 mx-auto mb-3 text-text-tertiary" />
                        No past invoices available.
                    </div>
                </x-ui.card>
            @endif
        </div>
    </div>
</div>
