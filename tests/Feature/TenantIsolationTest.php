<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_other_organization_data()
    {
        $orgA = Organization::create(['name' => 'Org A', 'slug' => 'org-a', 'is_onboarded' => true]);
        $orgB = Organization::create(['name' => 'Org B', 'slug' => 'org-b', 'is_onboarded' => true]);

        $userA = User::factory()->create(['organization_id' => $orgA->id, 'role' => 'admin']);
        $userB = User::factory()->create(['organization_id' => $orgB->id, 'role' => 'admin']);

        // Authenticate as User A
        $response = $this->actingAs($userA)->get('/app/users');
        $response->assertStatus(200);

        // Ensure User B is not in the response
        $response->assertDontSee($userB->email);
    }
}
