<div>
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Subscription Plans</h1>
            <p class="text-text-secondary mt-1">Manage platform billing and plans.</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="px-5 py-2.5 bg-signal-600 text-white rounded-xl hover:bg-signal-700 font-medium transition-colors text-sm shadow-md flex items-center">
            <x-heroicon-o-plus class="w-4 h-4 mr-2" />
            Create Plan
        </a>
    </div>

    <div class="mt-8 bg-bg-surface border border-bg-border rounded-xl shadow-sm overflow-hidden">
        @if($plans->isEmpty())
            <div class="p-12 text-center">
                <p class="text-text-secondary">No plans found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-bg-element border-b border-bg-border text-text-secondary">
                        <tr>
                            <th class="px-6 py-4 font-medium">Name</th>
                            <th class="px-6 py-4 font-medium">Payment Model</th>
                            <th class="px-6 py-4 font-medium">Remote Access</th>
                            <th class="px-6 py-4 font-medium">Screens Limit</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bg-border">
                        @foreach($plans as $plan)
                            <tr class="hover:bg-bg-element transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-text-primary">{{ $plan->name }}</div>
                                    <div class="text-xs text-text-secondary">{{ $plan->slug }}</div>
                                </td>
                                <td class="px-6 py-4 text-text-secondary capitalize">
                                    {{ str_replace('_', ' ', $plan->payment_model ?? 'Custom') }}
                                    @if($plan->billing_cycle)
                                        <span class="text-xs ml-1">({{ $plan->billing_cycle }})</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-text-secondary">
                                    @if($plan->remote_access)
                                        <span class="text-green-500">Allowed</span>
                                    @else
                                        <span class="text-yellow-500 text-xs">Same Network Only</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-text-secondary">
                                    @if(isset($plan->limits['screens']['unlimited']) && $plan->limits['screens']['unlimited'])
                                        Unlimited
                                    @elseif(isset($plan->limits['screens']['max']))
                                        Up to {{ $plan->limits['screens']['max'] }}
                                    @else
                                        Custom
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($plan->is_active)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-500/10 text-green-500">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-500/10 text-red-500">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.plans.edit', $plan) }}" class="text-signal-500 hover:text-signal-600 font-medium">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
