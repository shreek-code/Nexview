<div>
    <div class="mb-8">
        <div class="flex items-center space-x-3 text-sm text-text-tertiary mb-2">
            <a href="{{ route('app.users.index') }}" wire:navigate class="hover:text-text-primary transition-colors">Team</a>
            <x-heroicon-o-chevron-right class="w-4 h-4" />
            <span class="text-text-primary">Edit</span>
        </div>
        <h1 class="text-2xl font-bold text-text-primary tracking-tight">Edit Team Member</h1>
    </div>

    <form wire:submit="save" x-data="{ role: @entangle('role') }">
        <div class="max-w-3xl space-y-6">
            <x-ui.card class="p-6">
                <h2 class="text-lg font-semibold text-text-primary mb-4 border-b border-border-subtle pb-3">User Details</h2>
                
                <div class="space-y-6">
                    <div>
                        <x-ui.label for="name" value="Full Name" />
                        <x-ui.input id="name" type="text" wire:model="name" class="mt-1" required autofocus />
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-ui.label for="email" value="Email Address" />
                        <x-ui.input id="email" type="email" wire:model="email" class="mt-1" required />
                        @error('email')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-4 bg-surface-2 rounded-xl border border-border-subtle">
                        <h3 class="text-sm font-medium text-text-primary mb-2">Change Password</h3>
                        <p class="text-xs text-text-secondary mb-4">Leave blank if you don't want to change the password.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-ui.label for="password" value="New Password" />
                                <x-ui.input id="password" type="password" wire:model="password" class="mt-1 block w-full" autocomplete="new-password" />
                                @error('password')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-ui.label for="password_confirmation" value="Confirm New Password" />
                                <x-ui.input id="password_confirmation" type="password" wire:model="password_confirmation" class="mt-1 block w-full" autocomplete="new-password" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="p-6">
                <h2 class="text-lg font-semibold text-text-primary mb-4 border-b border-border-subtle pb-3">Permissions & Access</h2>
                
                <div class="space-y-6">
                    <div>
                        <x-ui.label for="role" value="User Role" />
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if(auth()->user()->role === 'admin' || $user->role === 'admin')
                            <label class="relative cursor-pointer">
                                <input type="radio" wire:model.live="role" value="admin" class="peer sr-only" @if(auth()->user()->role !== 'admin') disabled @endif>
                                <div class="rounded-xl border-2 p-4 transition-all hover:bg-surface-2 peer-checked:border-signal-500 peer-checked:bg-signal-500/5 @if(auth()->user()->role !== 'admin') opacity-50 cursor-not-allowed @endif">
                                    <div class="flex items-center space-x-3 mb-1">
                                        <div class="p-1.5 rounded-lg bg-surface-2 peer-checked:bg-signal-500/10 peer-checked:text-signal-600">
                                            <x-heroicon-o-shield-check class="w-5 h-5" />
                                        </div>
                                        <span class="font-semibold text-text-primary">Admin</span>
                                    </div>
                                    <p class="text-xs text-text-secondary">Full access to all locations, screens, and billing.</p>
                                </div>
                            </label>
                            @endif

                            <label class="relative cursor-pointer">
                                <input type="radio" wire:model.live="role" value="manager" class="peer sr-only">
                                <div class="rounded-xl border-2 p-4 transition-all hover:bg-surface-2 peer-checked:border-signal-500 peer-checked:bg-signal-500/5">
                                    <div class="flex items-center space-x-3 mb-1">
                                        <div class="p-1.5 rounded-lg bg-surface-2 peer-checked:bg-signal-500/10 peer-checked:text-signal-600">
                                            <x-heroicon-o-user-group class="w-5 h-5" />
                                        </div>
                                        <span class="font-semibold text-text-primary">Manager</span>
                                    </div>
                                    <p class="text-xs text-text-secondary">Manage assigned locations, media, and playlists.</p>
                                </div>
                            </label>
                        </div>
                        @error('role')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="role === 'manager'" x-collapse>
                        <x-ui.label for="location_ids" value="Location Access" />
                        <p class="text-sm text-text-secondary mb-3">Select the locations this manager can access.</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-1">
                            @forelse($locations as $location)
                                <label class="flex items-center space-x-3 p-3 border border-border-subtle rounded-lg hover:bg-surface-2 cursor-pointer transition-colors">
                                    <input type="checkbox" wire:model="location_ids" value="{{ $location->id }}" class="rounded border-border-base text-signal-600 focus:ring-signal-500">
                                    <span class="text-sm font-medium text-text-primary">{{ $location->name }}</span>
                                </label>
                            @empty
                                <div class="col-span-full text-sm text-text-secondary bg-surface-2 p-3 rounded-lg text-center">
                                    No locations available to assign.
                                </div>
                            @endforelse
                        </div>
                        @error('location_ids')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>

            <div class="pt-6 border-t border-border-subtle flex items-center justify-end space-x-4">
                <x-ui.button type="button" variant="outline" href="{{ route('app.users.index') }}" wire:navigate>
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit">
                    <div wire:loading wire:target="save" class="mr-2 h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                    <span wire:loading.remove wire:target="save">Update User</span>
                    <span wire:loading wire:target="save">Updating...</span>
                </x-ui.button>
            </div>
        </div>
    </form>
</div>
