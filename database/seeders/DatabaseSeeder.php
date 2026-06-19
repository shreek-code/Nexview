<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlanSeeder::class,
            BlogSeeder::class,
        ]);

        // 1. Create Platform User
        \App\Models\PlatformUser::create([
            'name' => 'Platform Admin',
            'email' => 'admin@nexview.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Create Organization
        $orgService = app(\App\Services\OrganizationService::class);
        $org = $orgService->createOrganizationWithAdmin([
            'name' => 'Acme Corp',
            'slug' => 'acme-corp',
            'admin_name' => 'Org Admin',
            'admin_email' => 'org_admin@acme.com',
            'admin_password' => 'password',
        ]);

        // 3. Create another location for the org
        $location = \App\Models\Location::create([
            'organization_id' => $org->id,
            'name' => 'New York Office',
        ]);

        // 3.5. Assign a plan to the organization
        $plan = \App\Models\Plan::where('slug', 'grow')->first();
        if ($plan) {
            \App\Models\Subscription::create([
                'organization_id' => $org->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'trial_ends_at' => now()->addDays(14),
                'ends_at' => now()->addYear(),
            ]);
        }

        // 4. Create Manager
        $userService = app(\App\Services\UserService::class);
        $adminUser = $org->users()->first();
        $manager = $userService->createUser($adminUser, [
            'organization_id' => $org->id,
            'name' => 'Org Manager',
            'email' => 'manager@acme.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'manager',
            'location_ids' => [$location->id],
        ]); // Assigned by the org admin

        // 5. Provision some screens
        \App\Models\Screen::create([
            'organization_id' => $org->id,
            'name' => 'Lobby Display 1',
            'location_id' => $location->id,
            'device_id' => 'device-' . \Illuminate\Support\Str::random(10),
            'status' => 'online',
            'last_heartbeat_at' => now(),
        ]);

        \App\Models\Screen::create([
            'organization_id' => $org->id,
            'name' => 'Breakroom Display',
            'location_id' => $location->id,
            'device_id' => 'device-' . \Illuminate\Support\Str::random(10),
            'status' => 'online',
            'last_heartbeat_at' => now(),
        ]);

        $this->command->info('Test data seeded successfully!');
        $this->command->info('Platform Admin: admin@nexview.com / password');
        $this->command->info('Org Admin: org_admin@acme.com / password');
        $this->command->info('Org Manager: manager@acme.com / password');
    }
}
