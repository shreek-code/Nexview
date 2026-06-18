<?php

namespace App\Livewire\Admin;

use App\Models\PlatformUser;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class PlatformUserEdit extends Component
{
    public PlatformUser $user;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = '';

    public function mount(PlatformUser $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:platform_users,email,' . $this->user->id,
            'password' => 'nullable|string|min:8|same:password_confirmation',
            'role' => 'required|in:admin,super_admin,support',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);

        session()->flash('success', 'Platform user updated successfully.');
        return $this->redirectRoute('admin.platform-users.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.platform-user-edit');
    }
}
