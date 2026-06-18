<div>
    <div class="mb-8 flex items-center space-x-3 text-sm text-text-tertiary">
        <a href="{{ route('admin.organizations.index') }}" wire:navigate class="hover:text-text-primary transition-colors">Organizations</a>
        <x-heroicon-o-chevron-right class="w-4 h-4" />
        <a href="{{ route('admin.organizations.show', $organization->id) }}" wire:navigate class="hover:text-text-primary transition-colors">{{ $organization->name }}</a>
        <x-heroicon-o-chevron-right class="w-4 h-4" />
        <span class="text-text-primary">Edit</span>
    </div>

    <form wire:submit="save">
        <div class="max-w-3xl space-y-6">
            <x-ui.card class="p-6">
                <h2 class="text-lg font-semibold text-text-primary mb-4 border-b border-border-subtle pb-3">Edit Organization</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-ui.label for="name" value="Organization Name" />
                        <x-ui.input id="name" type="text" wire:model="name" class="mt-1" required />
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <x-ui.label for="slug" value="Organization Slug (Subdomain)" />
                        <x-ui.input id="slug" type="text" wire:model="slug" class="mt-1" required />
                        @error('slug')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-ui.label for="status" value="Status" />
                        <select id="status" wire:model="status" class="mt-1 block w-full rounded-lg border-border-subtle bg-surface-secondary text-text-primary px-4 py-2.5 focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            <option value="active">Active</option>
                            <option value="suspended">Suspended</option>
                        </select>
                        @error('status')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <x-ui.label for="plan_id" value="Subscription Plan" />
                        <select id="plan_id" wire:model="plan_id" class="mt-1 block w-full rounded-lg border-border-subtle bg-surface-secondary text-text-primary px-4 py-2.5 focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>

            <div class="pt-6 border-t border-border-subtle flex items-center justify-end space-x-4">
                <x-ui.button type="button" variant="outline" href="{{ route('admin.organizations.show', $organization->id) }}" wire:navigate>
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit">
                    <div wire:loading wire:target="save" class="mr-2 h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </x-ui.button>
            </div>
        </div>
    </form>
</div>
