<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\OrganizationService;

class OrganizationCreate extends Component
{
    public $name = '';
    public $slug = '';
    public $admin_name = '';
    public $admin_email = '';
    public $admin_password = '';
    public $admin_password_confirmation = '';
    public $plan_id = 'starter';

    public function updatedName($value)
    {
        // Auto-generate slug from name
        $this->slug = \Illuminate\Support\Str::slug($value);
    }

    public function save(OrganizationService $organizationService)
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:organizations',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|string|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8|same:admin_password_confirmation',
            'plan_id' => 'required|string',
        ]);

        $organizationService->createOrganizationWithAdmin($validated);

        session()->flash('success', 'Organization created successfully.');
        return $this->redirectRoute('admin.organizations.index', navigate: true);
    }

    public function render()
    {
        $plans = \App\Models\Plan::all();
        return view('livewire.admin.organization-create', [
            'plans' => $plans
        ]);
    }
}
