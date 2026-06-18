<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Platform Settings</h1>
        <p class="text-text-secondary mt-1">Configure global application settings and maintenance mode.</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 px-4 py-4 rounded-2xl flex items-center transition-all fade-in-up">
            <x-heroicon-s-check-circle class="w-6 h-6 mr-3" />
            <p class="font-semibold tracking-wide">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <x-ui.card class="p-8">
                <form wire:submit="save" class="space-y-6">
                    <h2 class="text-lg font-bold text-text-primary border-b border-border-subtle pb-4 mb-6">General Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="appName" class="block text-sm font-semibold uppercase tracking-wider text-text-tertiary mb-2">Application Name</label>
                            <input id="appName" type="text" class="w-full h-12 px-4 rounded-xl neumorphic-inset bg-surface-1 border-transparent focus:border-signal-500 focus:ring-2 focus:ring-signal-500/20 transition-all text-text-primary" wire:model="appName" />
                        </div>
                        
                        <div>
                            <label for="supportEmail" class="block text-sm font-semibold uppercase tracking-wider text-text-tertiary mb-2">Support Email</label>
                            <input id="supportEmail" type="email" class="w-full h-12 px-4 rounded-xl neumorphic-inset bg-surface-1 border-transparent focus:border-signal-500 focus:ring-2 focus:ring-signal-500/20 transition-all text-text-primary" wire:model="supportEmail" />
                        </div>
                    </div>

                    <h2 class="text-lg font-bold text-text-primary border-b border-border-subtle pb-4 mb-6 mt-10">System Configuration</h2>

                    <div class="space-y-6">
                        <label class="flex items-start cursor-pointer group">
                            <div class="flex items-center h-6">
                                <input type="checkbox" wire:model="allowRegistration" class="w-5 h-5 rounded-md border-border-strong text-signal-600 focus:ring-signal-500 focus:ring-offset-surface-1 bg-surface-1 cursor-pointer transition-colors" />
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="block font-semibold text-text-primary group-hover:text-signal-600 transition-colors">Allow New Registrations</span>
                                <span class="block text-text-secondary mt-1">If unchecked, users cannot sign up for new organizations automatically.</span>
                            </div>
                        </label>

                        <label class="flex items-start cursor-pointer group">
                            <div class="flex items-center h-6">
                                <input type="checkbox" wire:model="maintenanceMode" class="w-5 h-5 rounded-md border-border-strong text-red-500 focus:ring-red-500 focus:ring-offset-surface-1 bg-surface-1 cursor-pointer transition-colors" />
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="block font-semibold text-red-500 group-hover:text-red-400 transition-colors">Maintenance Mode</span>
                                <span class="block text-text-secondary mt-1">Put the application into maintenance mode. Only Platform Admins will be able to log in.</span>
                            </div>
                        </label>
                    </div>

                    <div class="pt-6 border-t border-border-subtle flex justify-end">
                        <button type="submit" class="h-12 px-8 rounded-xl font-bold tracking-wider uppercase text-sm bg-signal-600 text-white shadow-glow-signal hover:bg-signal-500 active:scale-95 transition-all flex items-center justify-center min-w-[140px]">
                            <div wire:loading wire:target="save" class="mr-2 h-4 w-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></div>
                            <span wire:loading.remove wire:target="save">Save Settings</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </x-ui.card>
        </div>

        <div class="space-y-6">
            <x-ui.card class="p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-text-tertiary mb-4">Environment Info</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-border-subtle">
                        <span class="text-text-secondary">App Version</span>
                        <span class="font-semibold text-text-primary">v1.0.4-beta</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-border-subtle">
                        <span class="text-text-secondary">Laravel Version</span>
                        <span class="font-semibold text-text-primary">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-border-subtle">
                        <span class="text-text-secondary">PHP Version</span>
                        <span class="font-semibold text-text-primary">{{ phpversion() }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-text-secondary">Environment</span>
                        <span class="px-2 py-1 bg-surface-2 text-text-primary rounded text-xs font-mono font-semibold">{{ app()->environment() }}</span>
                    </div>
                </div>
            </x-ui.card>

            <div class="p-6 rounded-2xl border-2 border-dashed border-border-strong bg-surface-1/50">
                <h3 class="text-sm font-bold uppercase tracking-wider text-text-primary mb-2 flex items-center">
                    <x-heroicon-o-light-bulb class="w-5 h-5 mr-2 text-warning" />
                    Pro Tip
                </h3>
                <p class="text-sm text-text-secondary">
                    Changing the application name requires a cache clear for the updates to be fully reflected across all templates.
                </p>
                <button type="button" class="mt-4 w-full py-2 bg-surface-2 hover:bg-surface-3 transition-colors text-text-primary text-sm font-semibold rounded-lg border border-border-subtle">
                    Clear Application Cache
                </button>
            </div>
        </div>
    </div>
</div>
