<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Organization;

class OrganizationIndex extends Component
{
    public function render()
    {
        $organizations = Organization::with(['subscription.plan'])
            ->withCount(['users', 'locations'])
            ->get()
            ->map(function($org) {
            // Get total screens across all locations for this org
            $screensCount = \App\Models\Screen::whereHas('location', function($q) use ($org) {
                $q->where('organization_id', $org->id);
            })->count();

            $planName = $org->subscription && $org->subscription->plan ? $org->subscription->plan->name : 'N/A';
            $expirationDate = $org->subscription && $org->subscription->ends_at 
                                ? $org->subscription->ends_at->format('M d, Y') 
                                : 'No Expiry';

            return [
                'id' => $org->id,
                'name' => $org->name,
                'slug' => $org->slug,
                'status' => 'active', // default active
                'screens' => $screensCount,
                'users' => $org->users_count,
                'plan' => $planName,
                'expiration_date' => $expirationDate,
                'created_at' => $org->created_at->format('Y-m-d'),
            ];
        });

        return view('livewire.admin.organization-index', [
            'organizations' => $organizations,
        ]);
    }
}
