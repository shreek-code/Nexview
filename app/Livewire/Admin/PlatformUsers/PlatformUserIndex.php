<?php

namespace App\Livewire\Admin\PlatformUsers;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\PlatformUser;

class PlatformUserIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $users = PlatformUser::orderBy('name')->paginate(15);
        return view('livewire.admin.platform-users.platform-user-index', compact('users'));
    }
}
