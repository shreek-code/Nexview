<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrganizationService
{
    public function createOrganizationWithAdmin(array $data)
    {
        return DB::transaction(function () use ($data) {
            $organization = Organization::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'is_onboarded' => true,
            ]);

            \App\Models\Subscription::create([
                'organization_id' => $organization->id,
                'plan_id' => $data['plan_id'] ?? 'starter',
                'status' => 'active',
                'ends_at' => \Carbon\Carbon::now()->addYear(),
            ]);

            $admin = User::create([
                'organization_id' => $organization->id,
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'role' => 'admin',
            ]);

            return $organization;
        });
    }

    public function suspendOrganization(Organization $organization)
    {
        return DB::transaction(function () use ($organization) {
            $organization->update(['status' => 'suspended']);

            // Optionally, we could force-logout users, or we can handle it via middleware
            // For now, we rely on a middleware that checks organization status.
            
            // We should also turn off active screens (pause campaigns or set to offline)
            // But checking status in middleware/API is more robust.
            
            return $organization;
        });
    }

    public function activateOrganization(Organization $organization)
    {
        $organization->update(['status' => 'active']);
        return $organization;
    }
}
