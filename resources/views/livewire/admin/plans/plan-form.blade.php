<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">{{ $plan ? 'Edit Plan' : 'Create Plan' }}</h1>
            <p class="text-text-secondary mt-1">Configure subscription limits and nested features.</p>
        </div>
        <a href="{{ route('admin.plans.index') }}" class="px-4 py-2 text-text-secondary hover:text-text-primary transition-colors">
            Cancel
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="bg-bg-surface border border-bg-border rounded-xl shadow-sm p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Name</label>
                    <input type="text" wire:model.live="name" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Slug</label>
                    <input type="text" wire:model="slug" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-bg-border pt-6">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Payment Model</label>
                    <select wire:model="payment_model" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Select...</option>
                        <option value="one_time">One Time</option>
                        <option value="annual">Annual</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    @error('payment_model') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Billing Cycle</label>
                    <select wire:model="billing_cycle" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Select...</option>
                        <option value="yearly">Yearly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    @error('billing_cycle') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Network Restriction</label>
                    <select wire:model="network_restriction" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">None (Anywhere)</option>
                        <option value="same_network_only">Same Network Only</option>
                    </select>
                    @error('network_restriction') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center space-x-3 mt-6">
                    <input type="checkbox" id="remote_access" wire:model="remote_access" class="h-4 w-4 text-primary-500 bg-bg-element border-bg-border rounded focus:ring-primary-500">
                    <label for="remote_access" class="text-sm font-medium text-text-primary">
                        Allow Remote Access
                    </label>
                </div>
            </div>

            <!-- LIMITS -->
            <div class="border-t border-bg-border pt-6">
                <h3 class="text-lg font-medium text-text-primary mb-4">Limits</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Screens Max</label>
                        <input type="number" wire:model="limits.screens.max" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="e.g. 15">
                    </div>
                    <div class="flex items-center space-x-3 mt-6">
                        <input type="checkbox" id="unlimited_screens" wire:model="limits.screens.unlimited" class="h-4 w-4 text-primary-500 bg-bg-element border-bg-border rounded focus:ring-primary-500">
                        <label for="unlimited_screens" class="text-sm font-medium text-text-primary">Unlimited Screens</label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Locations</label>
                        <input type="number" wire:model="limits.locations" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Storage (GB)</label>
                        <input type="number" wire:model="limits.storage_gb" class="w-full bg-bg-element border border-bg-border rounded-lg px-4 py-2 text-text-primary focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
            </div>

            <!-- ANALYTICS -->
            <div class="border-t border-bg-border pt-6">
                <h3 class="text-lg font-medium text-text-primary mb-4">Analytics & Widgets</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-sm font-bold text-text-secondary mb-3 uppercase tracking-wider">Analytics Features</h4>
                        <div class="space-y-3">
                            @foreach(['proof_of_play', 'screen_uptime', 'campaign_delivery_report', 'media_performance', 'export_csv_pdf'] as $key)
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="analytics_{{ $key }}" wire:model="analytics.{{ $key }}" class="h-4 w-4 text-primary-500 bg-bg-element border-bg-border rounded focus:ring-primary-500">
                                <label for="analytics_{{ $key }}" class="text-sm font-medium text-text-secondary">
                                    {{ str_replace('_', ' ', Str::title($key)) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-bold text-text-secondary mb-3 uppercase tracking-wider">Widgets</h4>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['clock', 'static_rss', 'date_display', 'weather', 'live_rss', 'alert_display', 'social_feeds', 'custom_data'] as $key)
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="widgets_{{ $key }}" wire:model="widgets.{{ $key }}" class="h-4 w-4 text-primary-500 bg-bg-element border-bg-border rounded focus:ring-primary-500">
                                <label for="widgets_{{ $key }}" class="text-sm font-medium text-text-secondary">
                                    {{ str_replace('_', ' ', Str::title($key)) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-bg-border pt-6 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="is_active" wire:model="is_active" class="h-4 w-4 text-primary-500 bg-bg-element border-bg-border rounded focus:ring-primary-500">
                    <label for="is_active" class="text-sm font-medium text-text-primary">
                        Plan is Active
                    </label>
                </div>
                
                <button type="submit" class="px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 font-medium transition-colors">
                    Save Plan
                </button>
            </div>
        </div>
    </form>
</div>
