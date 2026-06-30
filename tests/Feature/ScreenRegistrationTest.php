<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Organization;
use App\Models\Location;
use App\Models\Screen;
use App\Models\User;
use App\Services\ScreenService;

class ScreenRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_screen_can_be_provisioned_and_registered()
    {
        // 1. Setup
        $org = Organization::create([
            'name' => 'Test Org',
            'slug' => 'test-org',
        ]);
        
        $location = Location::create([
            'organization_id' => $org->id,
            'name' => 'Lobby',
        ]);

        $user = User::factory()->create(['organization_id' => $org->id, 'role' => 'admin']);
        $service = app(ScreenService::class);

        // 2. Player announces intent
        $response1 = $this->postJson('/api/player/register', [
            'registration_code' => 'TEST12',
            'player_version' => '1.0.5',
            'resolution' => '1920x1080',
            'orientation' => 'landscape',
            'device_id' => 'device-1234',
        ]);
        $response1->assertStatus(202);

        // 3. User provisions screen from Dashboard
        $screen = $service->provisionScreen($user, [
            'name' => 'Test Screen',
            'location_id' => $location->id,
            'registration_code' => 'TEST12',
        ]);

        $this->assertEquals('online', $screen->status);
        $this->assertEquals('device-1234', $screen->device_id);

        // 4. Player polls again and is now registered
        $response2 = $this->postJson('/api/player/register', [
            'registration_code' => 'TEST12',
            'player_version' => '1.0.5',
            'resolution' => '1920x1080',
            'orientation' => 'landscape',
            'device_id' => 'device-1234',
        ]);

        $response2->assertStatus(200);
        $response2->assertJson([
            'message' => 'Successfully registered.',
            'device_id' => 'device-1234',
            'screen_name' => 'Test Screen',
            'location_id' => $location->id,
        ]);

        // 5. Verify Database State
        $screen->refresh();
        $this->assertEquals('online', $screen->status);
        $this->assertNull($screen->registration_code);
        $this->assertEquals('device-1234', $screen->device_id);
        $this->assertEquals('1.0.5', $screen->player_version);
        $this->assertEquals('1920x1080', $screen->resolution);
        $this->assertEquals('landscape', $screen->orientation);
        $this->assertNotNull($screen->last_heartbeat_at);
    }

    public function test_screen_cannot_register_with_expired_code()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org2']);
        $location = Location::create(['organization_id' => $org->id, 'name' => 'Lobby']);
        $user = User::factory()->create(['organization_id' => $org->id, 'role' => 'admin']);
        $service = app(ScreenService::class);
        
        // Ensure cache is clear
        \Illuminate\Support\Facades\Cache::forget('device_registration:TEST12');

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        
        // This should fail because the code isn't in the cache
        $service->provisionScreen($user, [
            'name' => 'Test Screen',
            'location_id' => $location->id,
            'registration_code' => 'TEST12'
        ]);
    }

    public function test_screen_cannot_reuse_registration_code()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org3']);
        $location = Location::create(['organization_id' => $org->id, 'name' => 'Lobby']);
        $user = User::factory()->create(['organization_id' => $org->id, 'role' => 'admin']);
        $service = app(ScreenService::class);

        // Player 1 announces
        $this->postJson('/api/player/register', [
            'registration_code' => 'TEST12',
            'device_id' => 'device-1234',
        ])->assertStatus(202);

        // Dashboard provisions
        $screen = $service->provisionScreen($user, [
            'name' => 'Test Screen',
            'location_id' => $location->id,
            'registration_code' => 'TEST12'
        ]);

        // Player 1 polls and registers successfully
        $this->postJson('/api/player/register', [
            'registration_code' => 'TEST12',
            'device_id' => 'device-1234',
        ])->assertStatus(200);

        // Player 2 tries to reuse the SAME code on screen
        $this->postJson('/api/player/register', [
            'registration_code' => 'TEST12',
            'device_id' => 'device-5678',
        ])->assertStatus(202); // Just caches and waits forever
    }

    public function test_registration_endpoint_is_rate_limited()
    {
        // 100 is the rate limit
        for ($i = 0; $i < 101; $i++) {
            $response = $this->postJson('/api/player/register', [
                'registration_code' => 'INVALI',
                'device_id' => 'device-fail',
            ]);
        }

        // 6th request should be rate limited (429 Too Many Requests)
        $response->assertStatus(429);
    }

    public function test_device_cannot_hijack_another_orgs_screen()
    {
        $orgA = Organization::create(['name' => 'Org A', 'slug' => 'org-a']);
        $locationA = Location::create(['organization_id' => $orgA->id, 'name' => 'Lobby A']);
        $userA = User::factory()->create(['organization_id' => $orgA->id, 'role' => 'admin']);
        
        $orgB = Organization::create(['name' => 'Org B', 'slug' => 'org-b']);
        $locationB = Location::create(['organization_id' => $orgB->id, 'name' => 'Lobby B']);
        $userB = User::factory()->create(['organization_id' => $orgB->id, 'role' => 'admin']);

        $service = app(ScreenService::class);

        // Org A player announces and gets provisioned
        $this->postJson('/api/player/register', [
            'registration_code' => 'CODE-A',
            'device_id' => 'device-A',
        ])->assertStatus(202);

        $screenA = $service->provisionScreen($userA, [
            'name' => 'Screen A',
            'location_id' => $locationA->id,
            'registration_code' => 'CODE-A'
        ]);

        // Malicious Org B player attempts to use Org A's device_id
        $this->postJson('/api/player/register', [
            'registration_code' => 'CODE-B',
            'device_id' => 'device-A',
        ])->assertStatus(202); // Should treat it as a new device pending dashboard approval, NOT auto-pair it!

        // Confirm screen A's token count hasn't gone up (no token minted for Org B)
        $this->assertEquals(0, $screenA->tokens()->count());
    }

    public function test_repoll_does_not_create_duplicate_tokens()
    {
        $org = Organization::create(['name' => 'Org', 'slug' => 'org-test-tokens']);
        $location = Location::create(['organization_id' => $org->id, 'name' => 'Lobby']);
        $user = User::factory()->create(['organization_id' => $org->id, 'role' => 'admin']);
        $service = app(ScreenService::class);

        // Announce
        $this->postJson('/api/player/register', [
            'registration_code' => 'CODE-T',
            'device_id' => 'device-T',
        ]);

        // Provision
        $screen = $service->provisionScreen($user, [
            'name' => 'Screen',
            'location_id' => $location->id,
            'registration_code' => 'CODE-T'
        ]);

        // Poll 1
        $this->postJson('/api/player/register', [
            'registration_code' => 'CODE-T',
            'device_id' => 'device-T',
        ])->assertStatus(200);

        // Poll 2 (Code is now cleared because it's a one-time credential)
        $this->postJson('/api/player/register', [
            'registration_code' => 'CODE-T',
            'device_id' => 'device-T',
        ])->assertStatus(202);

        // Poll 3
        $this->postJson('/api/player/register', [
            'registration_code' => 'CODE-T',
            'device_id' => 'device-T',
        ])->assertStatus(202);

        // Only 1 token should exist for this screen
        $this->assertEquals(1, $screen->tokens()->count());
    }

    public function test_billing_limit_enforced_during_provisioning()
    {
        $org = Organization::create(['name' => 'Org Limits', 'slug' => 'org-limits']);
        $location = Location::create(['organization_id' => $org->id, 'name' => 'Lobby']);
        $user = User::factory()->create(['organization_id' => $org->id, 'role' => 'admin']);
        $service = app(ScreenService::class);

        // Add plan with limit 1 screen
        $plan = \App\Models\Plan::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'stripe_price_id' => 'price_123',
            'max_screens' => 1,
            'max_storage_bytes' => 1024,
            'is_active' => true,
        ]);
        $org->subscription()->create([
            'plan_id' => $plan->id,
            'stripe_subscription_id' => 'sub_123',
            'status' => 'active',
        ]);

        // Create 1 screen manually
        Screen::create([
            'organization_id' => $org->id,
            'location_id' => $location->id,
            'name' => 'Existing Screen',
            'device_id' => 'existing-device',
            'status' => 'online',
        ]);

        // Player announces
        $this->postJson('/api/player/register', [
            'registration_code' => 'CODE-LIMIT',
            'device_id' => 'device-new',
        ]);

        // Try to provision 2nd screen
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $service->provisionScreen($user, [
            'name' => 'New Screen',
            'location_id' => $location->id,
            'registration_code' => 'CODE-LIMIT'
        ]);
    }
}
