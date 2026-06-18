<?php

namespace App\Livewire\App\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Team Management')]
class UserIndex extends Component
{
    public function delete(User $user)
    {
        // Auth user must be admin or we need a policy check
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if (Auth::id() === $user->id) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }

        if ($user->organization_id !== Auth::user()->organization_id) {
            abort(403);
        }

        $user->delete();

        session()->flash('success', 'User deleted successfully.');
    }

    public function render()
    {
        $organizationId = Auth::user()->organization_id;
        $users = User::where('organization_id', $organizationId)->with('locations')->get();

        return view('livewire.app.users.user-index', [
            'users' => $users,
        ]);
    }
}
