<?php

namespace App\Livewire\App\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Location;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Create User')]
class UserCreate extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'manager';
    public $location_ids = [];

    public function save(UserService $userService)
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|same:password_confirmation',
            'role' => 'required|in:admin,manager',
            'location_ids' => 'array',
            'location_ids.*' => 'exists:locations,id',
        ]);

        $this->authorize('create', \App\Models\User::class);

        $userService->createUser(Auth::user(), $validated);

        session()->flash('success', 'User created successfully.');
        return $this->redirectRoute('app.users.index', navigate: true);
    }

    public function render()
    {
        $user = Auth::user();
        $locations = $user->role === 'admin' 
            ? Location::where('organization_id', $user->organization_id)->get() 
            : $user->locations;

        return view('livewire.app.users.user-create', [
            'locations' => $locations,
        ]);
    }
}
