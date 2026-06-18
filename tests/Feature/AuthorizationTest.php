<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Location;
use App\Models\ManagerLocation;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_cannot_create_admin_user()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org', 'is_onboarded' => true]);
        $location = Location::create(['organization_id' => $org->id, 'name' => 'Loc A', 'timezone' => 'UTC']);
        $manager = User::factory()->create(['organization_id' => $org->id, 'role' => 'manager']);
        ManagerLocation::insert(['user_id' => $manager->id, 'location_id' => $location->id, 'assigned_by' => $manager->id]);

        $response = $this->actingAs($manager)->post('/app/users', [
            'name' => 'Sneaky Admin',
            'email' => 'sneaky@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ]);

        $response->assertStatus(403);
    }

    public function test_manager_cannot_assign_location_outside_their_scope()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org', 'is_onboarded' => true]);
        $locationA = Location::create(['organization_id' => $org->id, 'name' => 'Loc A', 'timezone' => 'UTC']);
        $locationB = Location::create(['organization_id' => $org->id, 'name' => 'Loc B', 'timezone' => 'UTC']);
        $manager = User::factory()->create(['organization_id' => $org->id, 'role' => 'manager']);
        // Manager only has Loc A
        ManagerLocation::insert(['user_id' => $manager->id, 'location_id' => $locationA->id, 'assigned_by' => $manager->id]);

        $response = $this->actingAs($manager)->post('/app/users', [
            'name' => 'New Manager',
            'email' => 'new@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'manager',
            'location_ids' => [$locationA->id, $locationB->id],
        ]);

        $response->assertStatus(403);
    }
}
