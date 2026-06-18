<div>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">{{ $organization->name }}</h1>
            <p class="text-text-secondary mt-1">Manage organization details and status.</p>
        </div>
        <div>
            <form action="{{ route('admin.impersonate.start', $organization->users->first()) }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary">
                    <x-heroicon-o-user-circle class="w-5 h-5 mr-2" />
                    Impersonate Admin
                </button>
            </form>
        </div>
    </div>

    <x-ui.card class="p-6">
        <h3 class="text-lg font-semibold text-text-primary mb-4">Organization Details</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-text-secondary">Slug</p>
                <p class="text-base text-text-primary">{{ $organization->slug }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-text-secondary">Status</p>
                <p class="text-base text-text-primary">{{ ucfirst($organization->status) }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-text-secondary">Plan</p>
                <p class="text-base text-text-primary">{{ $organization->subscription && $organization->subscription->plan ? $organization->subscription->plan->name : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-text-secondary">Subscription Expiry</p>
                <p class="text-base text-text-primary">{{ $organization->subscription && $organization->subscription->ends_at ? $organization->subscription->ends_at->format('M d, Y') : 'No Expiry' }}</p>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <x-ui.button :href="route('admin.organizations.edit', $organization->id)" wire:navigate variant="outline">
                <x-heroicon-o-pencil class="w-4 h-4 mr-2" />
                Edit Organization
            </x-ui.button>
        </div>
        </div>
    </x-ui.card>
</div>
