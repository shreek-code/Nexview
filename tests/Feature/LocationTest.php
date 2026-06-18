<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Location;
use App\Models\ManagerLocation;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_cannot_create_location()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org', 'is_onboarded' => true]);
        $manager = User::factory()->create(['organization_id' => $org->id, 'role' => 'manager']);

        $response = $this->actingAs($manager)->post('/app/locations', [
            'name' => 'New Location',
            'timezone' => 'UTC',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_location()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org', 'is_onboarded' => true]);
        $admin = User::factory()->create(['organization_id' => $org->id, 'role' => 'admin']);

        $response = $this->actingAs($admin)->post('/app/locations', [
            'name' => 'New Location',
            'timezone' => 'UTC',
        ]);

        $response->assertRedirect('/app/locations');
        $this->assertDatabaseHas('locations', ['name' => 'New Location']);
    }

    public function test_manager_can_only_view_assigned_locations()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org', 'is_onboarded' => true]);
        $loc1 = Location::create(['organization_id' => $org->id, 'name' => 'Loc 1', 'timezone' => 'UTC']);
        $loc2 = Location::create(['organization_id' => $org->id, 'name' => 'Loc 2', 'timezone' => 'UTC']);

        $manager = User::factory()->create(['organization_id' => $org->id, 'role' => 'manager']);
        ManagerLocation::insert(['user_id' => $manager->id, 'location_id' => $loc1->id, 'assigned_by' => $manager->id]);

        $response = $this->actingAs($manager)->get('/app/locations');

        $response->assertStatus(200);
        // We can't directly check the Inertia props without a specialized assert, 
        // but we can check if the query was scoped properly by checking the policy on an individual location.
        $this->assertTrue($manager->can('view', $loc1));
        $this->assertFalse($manager->can('view', $loc2));
    }
}
