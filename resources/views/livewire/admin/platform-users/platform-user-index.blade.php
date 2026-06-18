<div>
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-text-primary tracking-tight">Platform Users</h1>
            <p class="text-text-secondary mt-1">Manage system administrators and their roles.</p>
        </div>
        <a href="{{ route('admin.platform-users.create') }}" wire:navigate class="px-5 py-2.5 bg-signal-600 text-white rounded-xl hover:bg-signal-700 font-medium transition-colors text-sm shadow-md flex items-center">
            <x-heroicon-o-plus class="w-4 h-4 mr-2" />
            Create User
        </a>
    </div>

    <div class="mt-8 bg-bg-surface border border-bg-border rounded-xl shadow-sm overflow-hidden">
        @if($users->isEmpty())
            <div class="p-12 text-center">
                <p class="text-text-secondary">No platform users found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-bg-element border-b border-bg-border text-text-secondary">
                        <tr>
                            <th class="px-6 py-4 font-medium">Name</th>
                            <th class="px-6 py-4 font-medium">Email</th>
                            <th class="px-6 py-4 font-medium">Role</th>
                            <th class="px-6 py-4 font-medium">Added</th>
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bg-border">
                        @foreach($users as $user)
                            <tr class="hover:bg-bg-element transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-text-primary">{{ $user->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-text-secondary">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-primary-500/10 text-primary-500">
                                        {{ str_replace('_', ' ', Str::title($user->role ?? 'Admin')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-text-secondary">
                                    {{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.platform-users.edit', $user->id) }}" wire:navigate class="text-signal-500 hover:text-signal-600 font-medium text-sm">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-4 border-t border-bg-border bg-bg-element">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
