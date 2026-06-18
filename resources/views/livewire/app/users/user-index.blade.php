<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Team Management</h1>
            <p class="text-text-secondary mt-1">Manage users, roles, and location access.</p>
        </div>
        <div class="flex items-center space-x-3">
            @if(auth()->user()->role === 'admin')
            <x-ui.button :href="route('app.users.create')" wire:navigate>
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                New User
            </x-ui.button>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 px-4 py-3 rounded-xl flex items-start">
            <x-heroicon-o-check-circle class="w-5 h-5 mr-3 mt-0.5" />
            <div>
                <p class="font-medium">Success</p>
                <p class="text-sm mt-1">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl flex items-start">
            <x-heroicon-o-exclamation-circle class="w-5 h-5 mr-3 mt-0.5" />
            <div>
                <p class="font-medium">Error</p>
                <p class="text-sm mt-1">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-surface-1 border border-border-subtle rounded-xl overflow-hidden shadow-sm">
        @if($users->isEmpty())
            <div class="py-16 flex flex-col items-center justify-center text-center px-4">
                <div class="w-16 h-16 rounded-full bg-surface-2 flex items-center justify-center text-text-tertiary mb-4">
                    <x-heroicon-o-users class="w-8 h-8" />
                </div>
                <h3 class="text-lg font-medium text-text-primary mb-1">No users found</h3>
                <p class="text-text-secondary max-w-sm mb-6">Invite team members to help manage your screens.</p>
                @if(auth()->user()->role === 'admin')
                <x-ui.button :href="route('app.users.create')" wire:navigate>
                    <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                    New User
                </x-ui.button>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-surface-2 text-text-tertiary border-b border-border-subtle">
                        <tr>
                            <th class="px-6 py-4 font-medium">User</th>
                            <th class="px-6 py-4 font-medium">Role</th>
                            <th class="px-6 py-4 font-medium">Locations</th>
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle">
                        @foreach($users as $user)
                            <tr class="hover:bg-surface-2/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-signal-600 to-signal-400 flex items-center justify-center text-white font-bold text-xs uppercase mr-3">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-text-primary">{{ $user->name }}</div>
                                            <div class="text-xs text-text-tertiary mt-1">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <x-ui.badge :variant="$user->role === 'admin' ? 'info' : 'default'">
                                        {{ ucfirst($user->role) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4 text-text-secondary text-xs">
                                    @if($user->role === 'admin')
                                        <span class="italic">All Locations</span>
                                    @elseif($user->locations->count() > 0)
                                        {{ $user->locations->pluck('name')->implode(', ') }}
                                    @else
                                        <span class="text-text-tertiary italic">No locations assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if(auth()->user()->role === 'admin' || auth()->id() === $user->id)
                                    <x-ui.dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button class="text-text-tertiary hover:text-text-primary p-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">
                                                <x-heroicon-o-ellipsis-vertical class="w-5 h-5" />
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-ui.dropdown-link :href="route('app.users.edit', $user)" wire:navigate>
                                                Edit User
                                            </x-ui.dropdown-link>
                                            @if(auth()->id() !== $user->id && auth()->user()->role === 'admin')
                                            <x-ui.delete-action as="dropdown-link" action="delete('{{ $user->uuid }}')" confirmText="Are you sure you want to delete this user?">
                                                Remove User
                                            </x-ui.delete-action>
                                            @endif
                                        </x-slot>
                                    </x-ui.dropdown>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
