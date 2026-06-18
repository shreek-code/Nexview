<?php

namespace App\Livewire\Admin\Organizations;

use App\Models\Organization;
use Livewire\Component;

class OrganizationEdit extends Component
{
    public Organization $organization;

    public $name;
    public $slug;
    public $status;
    public $plan_id;

    public function mount(Organization $organization)
    {
        $this->organization = $organization;
        $this->name = $organization->name;
        $this->slug = $organization->slug;
        $this->status = $organization->status;
        $this->plan_id = $organization->subscription ? $organization->subscription->plan_id : '';
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:organizations,slug,' . $this->organization->id,
            'status' => 'required|in:active,suspended',
            'plan_id' => 'required|string',
        ]);

        $this->organization->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'status' => $this->status,
        ]);

        if ($this->organization->subscription) {
            $this->organization->subscription->update([
                'plan_id' => $this->plan_id,
            ]);
        } else {
            \App\Models\Subscription::create([
                'organization_id' => $this->organization->id,
                'plan_id' => $this->plan_id,
                'status' => 'active',
                'ends_at' => \Carbon\Carbon::now()->addYear(),
            ]);
        }

        session()->flash('success', 'Organization updated successfully.');
        return $this->redirectRoute('admin.organizations.index', navigate: true);
    }

    public function render()
    {
        $plans = \App\Models\Plan::all();
        return view('livewire.admin.organizations.organization-edit', [
            'plans' => $plans
        ]);
    }
}
