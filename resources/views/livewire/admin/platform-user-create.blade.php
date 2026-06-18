<div>
    <div class="mb-8 flex items-center space-x-3 text-sm text-text-tertiary">
        <a href="{{ route('admin.platform-users.index') }}" wire:navigate class="hover:text-text-primary transition-colors">Platform Users</a>
        <x-heroicon-o-chevron-right class="w-4 h-4" />
        <span class="text-text-primary">Create</span>
    </div>

    <form wire:submit="save">
        <div class="max-w-3xl space-y-6">
            <x-ui.card class="p-6">
                <h2 class="text-lg font-semibold text-text-primary mb-4 border-b border-border-subtle pb-3">User Details</h2>
                
                <div class="space-y-6">
                    <div>
                        <x-ui.label for="name" value="Name" />
                        <x-ui.input id="name" type="text" wire:model="name" class="mt-1 w-full" required autofocus />
                        @error('name') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-ui.label for="email" value="Email" />
                        <x-ui.input id="email" type="email" wire:model="email" class="mt-1 w-full" required />
                        @error('email') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-ui.label for="password" value="Password" />
                            <x-ui.input id="password" type="password" wire:model="password" class="mt-1 w-full" required autocomplete="new-password" />
                            @error('password') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-ui.label for="password_confirmation" value="Confirm Password" />
                            <x-ui.input id="password_confirmation" type="password" wire:model="password_confirmation" class="mt-1 w-full" required autocomplete="new-password" />
                        </div>
                    </div>

                    <div>
                        <x-ui.label for="role" value="Role" />
                        <select id="role" wire:model="role" class="mt-1 block w-full rounded-lg border-border-subtle bg-surface-secondary text-text-primary px-4 py-2.5 focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="support">Support</option>
                        </select>
                        @error('role') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
            </x-ui.card>

            <div class="pt-6 border-t border-border-subtle flex items-center justify-end space-x-4">
                <x-ui.button type="button" variant="outline" href="{{ route('admin.platform-users.index') }}" wire:navigate>
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit">
                    <div wire:loading wire:target="save" class="mr-2 h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                    <span wire:loading.remove wire:target="save">Create User</span>
                    <span wire:loading wire:target="save">Creating...</span>
                </x-ui.button>
            </div>
        </div>
    </form>
</div>
