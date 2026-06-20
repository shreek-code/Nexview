<div x-data="{ activeTab: 'general' }">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-text-primary tracking-tight">Account Settings</h1>
            <p class="text-text-secondary mt-2 text-lg">Manage your personal profile, security preferences, and billing.</p>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Navigation for Tabs -->
        <div class="w-full md:w-64 flex-shrink-0">
            <nav class="flex md:flex-col space-x-2 md:space-x-0 md:space-y-2 overflow-x-auto pb-4 md:pb-0">
                <button @click="activeTab = 'general'" :class="{ 'bg-surface-2 text-signal-600 font-semibold shadow-sm border-signal-500/30': activeTab === 'general', 'text-text-secondary hover:bg-surface-2 hover:text-text-primary border-transparent': activeTab !== 'general' }" class="flex items-center px-4 py-3 text-sm rounded-2xl transition-all border whitespace-nowrap">
                    <x-heroicon-o-user-circle class="w-5 h-5 mr-3" x-bind:class="{'text-signal-500': activeTab === 'general'}" />
                    General
                </button>
                <button @click="activeTab = 'security'" :class="{ 'bg-surface-2 text-signal-600 font-semibold shadow-sm border-signal-500/30': activeTab === 'security', 'text-text-secondary hover:bg-surface-2 hover:text-text-primary border-transparent': activeTab !== 'security' }" class="flex items-center px-4 py-3 text-sm rounded-2xl transition-all border whitespace-nowrap">
                    <x-heroicon-o-lock-closed class="w-5 h-5 mr-3" x-bind:class="{'text-signal-500': activeTab === 'security'}" />
                    Security
                </button>
                @if(auth()->user()->role === 'admin')
                <button @click="activeTab = 'organization'" :class="{ 'bg-surface-2 text-signal-600 font-semibold shadow-sm border-signal-500/30': activeTab === 'organization', 'text-text-secondary hover:bg-surface-2 hover:text-text-primary border-transparent': activeTab !== 'organization' }" class="flex items-center px-4 py-3 text-sm rounded-2xl transition-all border whitespace-nowrap">
                    <x-heroicon-o-building-office class="w-5 h-5 mr-3" x-bind:class="{'text-signal-500': activeTab === 'organization'}" />
                    Organization
                </button>
                <button @click="activeTab = 'billing'" :class="{ 'bg-surface-2 text-signal-600 font-semibold shadow-sm border-signal-500/30': activeTab === 'billing', 'text-text-secondary hover:bg-surface-2 hover:text-text-primary border-transparent': activeTab !== 'billing' }" class="flex items-center px-4 py-3 text-sm rounded-2xl transition-all border whitespace-nowrap">
                    <x-heroicon-o-credit-card class="w-5 h-5 mr-3" x-bind:class="{'text-signal-500': activeTab === 'billing'}" />
                    Billing & Subscription
                </button>
                @endif
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="flex-1 space-y-10 min-w-0">
            
            <!-- Tab: General -->
            <div x-show="activeTab === 'general'" x-cloak x-transition.opacity.duration.300ms>
                <div class="p-8 rounded-3xl neumorphic border border-border-subtle bg-surface-1/50 backdrop-blur-sm">
                    <header class="mb-8 border-b border-border-subtle pb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-text-primary flex items-center">
                                Profile Information
                            </h2>
                            <p class="mt-2 text-sm text-text-secondary">Update your account's profile information and email address.</p>
                        </div>
                    </header>

                    <form wire:submit="updateProfile" class="space-y-8">
                        @if (session('profile-updated'))
                            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 px-4 py-4 rounded-2xl flex items-center transition-all fade-in-up">
                                <x-heroicon-s-check-circle class="w-6 h-6 mr-3" />
                                <p class="font-semibold tracking-wide">Profile updated successfully.</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-semibold uppercase tracking-wider text-text-tertiary">Full Name</label>
                                <input id="name" type="text" class="w-full h-12 px-4 rounded-xl neumorphic-inset bg-surface-1 border-transparent focus:border-signal-500 focus:ring-2 focus:ring-signal-500/20 transition-all text-text-primary" wire:model="name" required autofocus autocomplete="name" />
                                @error('name')
                                    <p class="text-sm text-red-600 font-medium mt-2 flex items-center"><x-heroicon-s-exclamation-circle class="w-4 h-4 mr-1"/>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-semibold uppercase tracking-wider text-text-tertiary">Email Address</label>
                                <input id="email" type="email" class="w-full h-12 px-4 rounded-xl neumorphic-inset bg-surface-1 border-transparent focus:border-signal-500 focus:ring-2 focus:ring-signal-500/20 transition-all text-text-primary" wire:model="email" required autocomplete="username" />
                                @error('email')
                                    <p class="text-sm text-red-600 font-medium mt-2 flex items-center"><x-heroicon-s-exclamation-circle class="w-4 h-4 mr-1"/>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="h-12 px-8 rounded-xl font-bold tracking-wider uppercase text-sm bg-signal-600 text-white shadow-glow-signal hover:bg-signal-500 active:scale-95 transition-all flex items-center justify-center min-w-[140px]">
                                <div wire:loading wire:target="updateProfile" class="mr-2 h-4 w-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></div>
                                <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                                <span wire:loading wire:target="updateProfile">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab: Security -->
            <div x-show="activeTab === 'security'" x-cloak x-transition.opacity.duration.300ms>
                <div class="p-8 rounded-3xl neumorphic border border-border-subtle bg-surface-1/50 backdrop-blur-sm">
                    <header class="mb-8 border-b border-border-subtle pb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-text-primary flex items-center">
                                Update Password
                            </h2>
                            <p class="mt-2 text-sm text-text-secondary">Ensure your account is using a long, random password to stay secure.</p>
                        </div>
                    </header>

                    <form wire:submit="updatePassword" class="space-y-8">
                        @if (session('password-updated'))
                            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 px-4 py-4 rounded-2xl flex items-center transition-all fade-in-up">
                                <x-heroicon-s-check-circle class="w-6 h-6 mr-3" />
                                <p class="font-semibold tracking-wide">Password updated successfully.</p>
                            </div>
                        @endif

                        <div class="space-y-2 max-w-xl relative" x-data="{ show: false }">
                            <label for="current_password" class="block text-sm font-semibold uppercase tracking-wider text-text-tertiary">Current Password</label>
                            <div class="relative">
                                <x-ui.input id="current_password" x-bind:type="show ? 'text' : 'password'" class="!h-12 !px-4 !rounded-xl neumorphic-inset bg-surface-1 border-transparent focus:border-signal-500 focus:ring-2 focus:ring-signal-500/20 transition-all text-text-primary w-full pr-12" wire:model="current_password" autocomplete="current-password" />
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-text-tertiary hover:text-text-primary focus:outline-none">
                                    <x-heroicon-o-eye x-show="!show" class="w-5 h-5"/>
                                    <x-heroicon-o-eye-slash x-show="show" x-cloak class="w-5 h-5"/>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-sm text-red-600 font-medium mt-2 flex items-center"><x-heroicon-s-exclamation-circle class="w-4 h-4 mr-1"/>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl">
                            <div class="space-y-2 relative" x-data="{ show: false }">
                                <label for="password" class="block text-sm font-semibold uppercase tracking-wider text-text-tertiary">New Password</label>
                                <div class="relative">
                                    <x-ui.input id="password" x-bind:type="show ? 'text' : 'password'" class="!h-12 !px-4 !rounded-xl neumorphic-inset bg-surface-1 border-transparent focus:border-signal-500 focus:ring-2 focus:ring-signal-500/20 transition-all text-text-primary w-full pr-12" wire:model="password" autocomplete="new-password" />
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-text-tertiary hover:text-text-primary focus:outline-none">
                                        <x-heroicon-o-eye x-show="!show" class="w-5 h-5"/>
                                        <x-heroicon-o-eye-slash x-show="show" x-cloak class="w-5 h-5"/>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-sm text-red-600 font-medium mt-2 flex items-center"><x-heroicon-s-exclamation-circle class="w-4 h-4 mr-1"/>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2 relative" x-data="{ show: false }">
                                <label for="password_confirmation" class="block text-sm font-semibold uppercase tracking-wider text-text-tertiary">Confirm Password</label>
                                <div class="relative">
                                    <x-ui.input id="password_confirmation" x-bind:type="show ? 'text' : 'password'" class="!h-12 !px-4 !rounded-xl neumorphic-inset bg-surface-1 border-transparent focus:border-signal-500 focus:ring-2 focus:ring-signal-500/20 transition-all text-text-primary w-full pr-12" wire:model="password_confirmation" autocomplete="new-password" />
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-text-tertiary hover:text-text-primary focus:outline-none">
                                        <x-heroicon-o-eye x-show="!show" class="w-5 h-5"/>
                                        <x-heroicon-o-eye-slash x-show="show" x-cloak class="w-5 h-5"/>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="text-sm text-red-600 font-medium mt-2 flex items-center"><x-heroicon-s-exclamation-circle class="w-4 h-4 mr-1"/>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="h-12 px-8 rounded-xl font-bold tracking-wider uppercase text-sm bg-signal-600 text-white shadow-glow-signal hover:bg-signal-500 active:scale-95 transition-all flex items-center justify-center min-w-[140px]">
                                <div wire:loading wire:target="updatePassword" class="mr-2 h-4 w-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></div>
                                <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                                <span wire:loading wire:target="updatePassword">Updating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(auth()->user()->role === 'admin')
            <!-- Tab: Organization -->
            <div x-show="activeTab === 'organization'" x-cloak x-transition.opacity.duration.300ms>
                <div class="p-8 rounded-3xl neumorphic border border-border-subtle bg-surface-1/50 backdrop-blur-sm">
                    <header class="mb-8 border-b border-border-subtle pb-6">
                        <h2 class="text-xl font-bold text-text-primary flex items-center">
                            Organization Details
                        </h2>
                        <p class="mt-2 text-sm text-text-secondary">View and manage your organization details.</p>
                    </header>

                    @php
                        $org = auth()->user()->organization;
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold uppercase tracking-wider text-text-tertiary">Organization Name</label>
                            <div class="w-full h-12 px-4 rounded-xl neumorphic-inset bg-surface-1 flex items-center text-text-primary opacity-80">
                                {{ $org->name }}
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold uppercase tracking-wider text-text-tertiary">Organization Slug</label>
                            <div class="w-full h-12 px-4 rounded-xl neumorphic-inset bg-surface-1 flex items-center text-text-primary opacity-80">
                                {{ $org->slug }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Billing & Subscription -->
            <div x-show="activeTab === 'billing'" x-cloak x-transition.opacity.duration.300ms class="space-y-8">
                
                @php
                    $subscription = auth()->user()->organization->subscription()->with('plan')->first();
                @endphp

                <!-- Current Subscription -->
                <div class="p-8 rounded-3xl neumorphic border border-border-subtle bg-surface-1/50 backdrop-blur-sm">
                    <header class="mb-8 border-b border-border-subtle pb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-text-primary flex items-center">
                                My Subscription
                            </h2>
                            <p class="mt-2 text-sm text-text-secondary">Manage your active plan and subscription limits.</p>
                        </div>
                        @if($subscription && $subscription->status === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-600 border border-red-500/20">
                                Inactive
                            </span>
                        @endif
                    </header>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-6">
                            @if($subscription && $subscription->plan)
                                <div class="p-6 rounded-2xl bg-surface-2 border border-border-subtle flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg font-bold text-text-primary">{{ $subscription->plan->name }} Plan</h3>
                                        <p class="text-sm text-text-secondary mt-1">Next billing date: {{ $subscription->ends_at ? $subscription->ends_at->format('M j, Y') : 'Auto-renewing' }}</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 rounded-xl neumorphic-inset bg-surface-1">
                                        <div class="text-sm font-semibold text-text-tertiary mb-1">Screens Used</div>
                                        <div class="text-2xl font-bold text-text-primary">
                                            {{ auth()->user()->organization->screens()->count() }} / {{ $subscription->plan->max_screens }}
                                        </div>
                                    </div>
                                    <div class="p-4 rounded-xl neumorphic-inset bg-surface-1">
                                        <div class="text-sm font-semibold text-text-tertiary mb-1">Storage Used</div>
                                        <div class="text-2xl font-bold text-text-primary">
                                            {{ number_format(auth()->user()->organization->mediaAssets()->sum('size') / 1048576, 2) }} MB / {{ number_format($subscription->plan->max_storage_bytes / 1048576, 0) }} MB
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="p-8 text-center rounded-2xl border-2 border-dashed border-border-subtle text-text-tertiary">
                                    <x-heroicon-o-cube-transparent class="w-12 h-12 mx-auto mb-3 opacity-50" />
                                    <p class="font-medium text-text-primary">No Active Subscription</p>
                                    <p class="text-sm mt-1 mb-4">You are currently on a free or restricted tier.</p>
                                    <button class="px-6 py-2 rounded-xl bg-signal-600 text-white font-bold text-sm shadow-glow-signal hover:bg-signal-500 transition-colors">View Plans</button>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Quick actions -->
                        <div class="space-y-3">
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-text-tertiary mb-4">Quick Actions</h4>
                            <a href="{{ route('web.pricing') }}" class="w-full flex items-center justify-between p-4 rounded-xl border border-border-subtle bg-surface-1 hover:bg-surface-2 transition-colors text-sm font-semibold text-text-primary">
                                <span>Change Plan</span>
                                <x-heroicon-o-chevron-right class="w-4 h-4 text-text-tertiary" />
                            </a>
                            <a href="{{ route('app.support.create') }}?subject=Cancellation+Request&priority=high" class="w-full flex items-center justify-between p-4 rounded-xl border border-border-subtle bg-surface-1 hover:bg-surface-2 transition-colors text-sm font-semibold text-red-600 hover:text-red-700">
                                <span>Cancel Subscription</span>
                                <x-heroicon-o-chevron-right class="w-4 h-4 text-red-500" />
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Billing History -->
                <div class="p-8 rounded-3xl neumorphic border border-border-subtle bg-surface-1/50 backdrop-blur-sm">
                    <header class="mb-8 border-b border-border-subtle pb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-text-primary flex items-center">
                                Billing History
                            </h2>
                            <p class="mt-2 text-sm text-text-secondary">View your past invoices and payment receipts.</p>
                        </div>
                    </header>

                    <div class="overflow-x-auto rounded-2xl border border-border-subtle">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-surface-2 text-text-tertiary uppercase text-xs font-semibold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Description</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Amount</th>
                                    <th class="px-6 py-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-subtle text-text-primary">
                                <!-- Mock Data for Demo -->
                                <tr class="hover:bg-surface-2 transition-colors">
                                    <td class="px-6 py-4">{{ now()->subDays(2)->format('M j, Y') }}</td>
                                    <td class="px-6 py-4 font-medium">Monthly Standard Plan</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600">Paid</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold">$29.00</td>
                                    <td class="px-6 py-4 text-center">
                                        <button class="text-signal-600 hover:text-signal-700 font-semibold text-xs">Download PDF</button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-surface-2 transition-colors">
                                    <td class="px-6 py-4">{{ now()->subDays(32)->format('M j, Y') }}</td>
                                    <td class="px-6 py-4 font-medium">Monthly Standard Plan</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600">Paid</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold">$29.00</td>
                                    <td class="px-6 py-4 text-center">
                                        <button class="text-signal-600 hover:text-signal-700 font-semibold text-xs">Download PDF</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            @endif

        </div>
    </div>
</div>
