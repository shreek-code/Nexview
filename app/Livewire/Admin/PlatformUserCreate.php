<?php

namespace App\Livewire\Admin;

use App\Models\PlatformUser;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class PlatformUserCreate extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'admin';

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:platform_users,email',
            'password' => 'required|string|min:8|same:password_confirmation',
            'role' => 'required|in:admin,super_admin,support',
        ]);

        PlatformUser::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        session()->flash('success', 'Platform user created successfully.');
        return $this->redirectRoute('admin.platform-users.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.platform-user-create');
    }
}
