<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Location;
use App\Models\MediaAsset;
use App\Models\Organization;
use App\Models\Playlist;
use App\Models\PlaylistItem;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EndToEndFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_platform_journey()
    {
        try {
            Storage::fake('public');

        // 1. New User Registers
        $response = $this->post('/register', [
            'name' => 'CEO Founder',
            'email' => 'founder@startup.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'organization_name' => 'Acme Corp',
        ]);
        
        $response->assertRedirect('/app/onboarding');
        $this->assertAuthenticated();

        /** @var User $user */
        $user = auth()->user();
        $this->assertEquals('admin', $user->role);
        
        $organization = $user->organization;
        $this->assertNotNull($organization);
        $this->assertEquals('Acme Corp', $organization->name);
        $this->assertFalse((bool)$organization->is_onboarded);

        // 1b. Simulate Billing Onboarding (Livewire Onboarding Flow)
        $plan = \App\Models\Plan::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'stripe_price_id' => 'price_123',
            'max_screens' => 10,
            'max_storage_bytes' => 10240,
            'is_active' => true,
        ]);
        $organization->subscription()->create([
            'plan_id' => $plan->id,
            'stripe_subscription_id' => 'sub_123',
            'status' => 'active',
        ]);
        $organization->update(['is_onboarded' => true]);

        // 2. Setup Wizard - Create Location
        $location = Location::create([
            'organization_id' => $organization->id,
            'name' => 'Headquarters',
            'timezone' => 'America/New_York',
        ]);
        $this->assertEquals('Headquarters', $location->name);

        // 3. Player announces intent
        $pairingCode = '123456';
        $response = $this->postJson('/api/player/register', [
            'registration_code' => $pairingCode,
            'device_id' => 'ANDROID_TV_123',
            'player_version' => '1.0.0',
            'resolution' => '1920x1080',
            'orientation' => 'landscape',
        ]);
        $response->assertStatus(202);

        // 3b. Dashboard provisions screen
        $screen = app(\App\Services\ScreenService::class)->provisionScreen($user, [
            'name' => 'Lobby TV',
            'location_id' => $location->id,
            'registration_code' => $pairingCode,
        ]);
        $this->assertEquals('Lobby TV', $screen->name);

        // 4. Setup Wizard - Upload Media
        $file = UploadedFile::fake()->image('logo.jpg');
        $path = $file->store('media', 'public');
        $media = MediaAsset::create([
            'organization_id' => $organization->id,
            'name' => 'logo.jpg',
            'file_path' => $path,
            'mime_type' => 'image/jpeg',
            'type' => 'image',
            'size' => 1024,
            'duration' => 10,
        ]);
        $this->assertEquals('logo.jpg', $media->name);

        // 5. Setup Wizard - Create Playlist
        $playlist = Playlist::create([
            'organization_id' => $organization->id,
            'name' => 'Welcome Playlist',
        ]);
        PlaylistItem::create([
            'playlist_id' => $playlist->id,
            'media_asset_id' => $media->id,
            'duration' => 10,
            'sort_order' => 1,
        ]);

        // 6. Setup Wizard - Create Campaign
        $campaign = Campaign::create([
            'organization_id' => $organization->id,
            'name' => 'Lobby Welcome',
            'status' => 'active',
            'priority' => 1,
            'target_type' => 'location',
            'target_location_id' => $location->id,
            'content_type' => 'playlist',
            'playlist_id' => $playlist->id,
        ]);
        $this->assertEquals('Lobby Welcome', $campaign->name);
        
        // 7. Verify Setup Complete
        $response = $this->actingAs($user)->post('/app/setup/complete');
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $organization->refresh();
        $this->assertTrue((bool)$organization->is_onboarded);

        // 8. Player API - Poll again
        auth()->logout();

        $response = $this->postJson('/api/player/register', [
            'registration_code' => $pairingCode,
            'device_id' => 'ANDROID_TV_123',
            'player_version' => '1.0.0',
            'resolution' => '1920x1080',
            'orientation' => 'landscape',
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $response->json());
        $token = $response->json('token');

        // 9. Player API - Sync Manifest
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/player/sync');
        $response->assertStatus(200);
        $data = $response->json();
        
        $this->assertEquals($screen->id, $data['screen_id']);
        $this->assertEquals('ANDROID_TV_123', $data['device_id']);
        $this->assertCount(1, $data['campaigns']);
        $this->assertEquals('Lobby Welcome', $data['campaigns'][0]['name']);
        
        // Playlist item unrolled correctly
        $this->assertCount(1, $data['campaigns'][0]['content']);
        $this->assertEquals($media->id, $data['campaigns'][0]['content'][0]['id']);

        // 10. Player API - Heartbeat
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                          ->postJson('/api/player/heartbeat', [
                              'player_version' => '1.0.0',
                              'disk_space_free' => 50000,
                              'memory_free' => 1024,
                          ]);
        $response->assertStatus(200);
        $screen->refresh();
        $this->assertNotNull($screen->last_seen_at);
        $this->assertEquals('online', $screen->status);
        } catch (\Throwable $e) {
            dump($e->getMessage());
            dump($e->getTraceAsString());
            throw $e;
        }
    }
}
