<?php

namespace App\Livewire\App\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\User;
use App\Models\Location;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Edit User')]
class UserEdit extends Component
{
    public User $user;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'manager';
    public $location_ids = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->location_ids = $user->locations->pluck('id')->toArray();
    }

    public function save(UserService $userService)
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user->id,
            'password' => 'nullable|string|min:8|same:password_confirmation',
            'role' => 'required|in:admin,manager',
            'location_ids' => 'array',
            'location_ids.*' => 'exists:locations,id',
        ]);

        $this->authorize('update', $this->user);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $userService->updateUser($this->user, Auth::user(), $validated);

        session()->flash('success', 'User updated successfully.');
        return $this->redirectRoute('app.users.index', navigate: true);
    }

    public function render()
    {
        $authUser = Auth::user();
        $locations = $authUser->role === 'admin' 
            ? Location::where('organization_id', $authUser->organization_id)->get() 
            : $authUser->locations;

        return view('livewire.app.users.user-edit', [
            'locations' => $locations,
        ]);
    }
}
