<?php

namespace App\Services;

use App\Models\Screen;
use Illuminate\Support\Str;

class ScreenService
{
    /**
     * Create a screen directly connected via Device ID.
     */
    public function createConnectedScreen(\App\Models\Organization $organization, array $data, \App\Services\BillingService $billingService): Screen
    {
        $billingService->enforceScreenLimit($organization);
        
        return Screen::create([
            'organization_id' => $organization->id,
            'name' => $data['name'],
            'location_id' => $data['location_id'],
            'device_id' => $data['device_id'],
            'status' => 'online',
            'last_heartbeat_at' => now(),
        ]);
    }

    /**
     * User enters the code from the device into the web dashboard to register it.
     */
    public function provisionScreen(\App\Models\User $user, array $data): Screen
    {
        $organizationId = $user->organization_id;
        $managerLocationIds = $user->role === 'manager' ? $user->locations()->pluck('locations.id')->toArray() : null;

        $location = \App\Models\Location::where('organization_id', $organizationId)
            ->when($managerLocationIds !== null, function ($q) use ($managerLocationIds) {
                $q->whereIn('id', $managerLocationIds);
            })
            ->findOrFail($data['location_id']);

        $code = strtoupper($data['registration_code']);
        $deviceData = \Illuminate\Support\Facades\Cache::get('device_registration:' . $code);

        if (!$deviceData) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'registration_code' => 'No device is currently waiting to connect with this code. Please ensure the player app is running.'
            ]);
        }

        $screen = Screen::create([
            'organization_id' => $organizationId,
            'name' => $data['name'],
            'location_id' => $data['location_id'],
            'device_id' => $deviceData['device_id'],
            'registration_code' => $code,
            'status' => 'online',
            'player_version' => $deviceData['player_version'],
            'resolution' => $deviceData['resolution'],
            'orientation' => $deviceData['orientation'],
            'last_heartbeat_at' => now(),
        ]);

        \Illuminate\Support\Facades\Cache::forget('device_registration:' . $code);

        return $screen;
    }

    /**
     * Pair a physical screen using its registration code when it polls the server.
     */
    public function pairScreen(string $code, string $deviceId, string $version, ?string $resolution, ?string $orientation): ?Screen
    {
        // First check if the device was already paired by the dashboard (device_id exists)
        $screen = Screen::where('device_id', $deviceId)->first();

        if ($screen) {
            $screen->update([
                'status' => 'online',
                'player_version' => $version,
                'resolution' => $resolution,
                'orientation' => $orientation,
                'last_heartbeat_at' => now(),
            ]);
            return $screen;
        }

        // Fallback: check if the dashboard pre-generated a code
        $screenByCode = Screen::where('registration_code', strtoupper($code))
            ->where('registration_code_expires_at', '>', now())
            ->first();

        if ($screenByCode) {
            $screenByCode->update([
                'registration_code' => null, // Clear the code after pairing
                'device_id' => $deviceId,    // Store the actual permanent device_id
                'status' => 'online',
                'player_version' => $version,
                'resolution' => $resolution,
                'orientation' => $orientation,
                'last_heartbeat_at' => now(),
            ]);
            return $screenByCode;
        }

        return null;
    }

    /**
     * Generate a unique 6-character alphanumeric registration code.
     */
    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (Screen::where('registration_code', $code)->exists());

        return $code;
    }

    /**
     * Update screen playback settings.
     */
    public function updatePlaybackSettings(Screen $screen, array $settings): Screen
    {
        $screen->update(array_intersect_key($settings, array_flip([
            'volume',
            'is_playing',
            'current_media_id',
            'default_media_id',
            'orientation',
        ])));

        broadcast(new \App\Events\ScreenUpdated($screen));

        return $screen;
    }

    /**
     * Delete a screen.
     */
    public function deleteScreen(Screen $screen): void
    {
        $screen->delete();
    }
}
