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
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/player/register', [
                'registration_code' => 'INVALID',
                'device_id' => 'device-fail',
            ]);
        }

        // 6th request should be rate limited (429 Too Many Requests)
        $response->assertStatus(429);
    }
}
