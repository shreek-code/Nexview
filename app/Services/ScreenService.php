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
     * Enforces billing limits internally — callers do not need to check separately.
     */
    public function provisionScreen(\App\Models\User $user, array $data): Screen
    {
        $organization = $user->organization;
        $organizationId = $organization->id;

        // Enforce billing limit within the service — single source of truth
        $billingService = app(\App\Services\BillingService::class);
        $billingService->enforceScreenLimit($organization);

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
     * Find an already-provisioned screen by device ID and update its heartbeat.
     *
     * Called when the Android player polls after the dashboard has provisioned
     * the screen via provisionScreen(). The organization_id is required to
     * prevent cross-tenant device hijacking.
     *
     * @param string $deviceId     The permanent hardware device identifier.
     * @param int    $organizationId  The org that owns the screen (resolved from cache by the controller).
     * @param string $version      Player app version string.
     * @param string|null $resolution  Screen resolution e.g. '1920x1080'.
     * @param string|null $orientation 'landscape' or 'portrait'.
     * @return Screen|null  The matched screen, or null if no provisioned screen exists for this device+org.
     */
    public function pairScreen(string $deviceId, int $organizationId, string $version, ?string $resolution, ?string $orientation): ?Screen
    {
        // Explicitly bypass the global organization scope and apply manual org filter.
        // The global scope is inactive in API context (auth user is a Screen, not User),
        // so we must enforce tenant isolation manually here.
        $screen = Screen::withoutGlobalScope('organization')
            ->where('device_id', $deviceId)
            ->where('organization_id', $organizationId)
            ->first();

        if (!$screen) {
            return null;
        }

        $screen->update([
            'registration_code' => null, // Prevents re-pairing or hijacking once successfully paired
            'status' => 'online',
            'player_version' => $version,
            'resolution' => $resolution,
            'orientation' => $orientation,
            'last_heartbeat_at' => now(),
        ]);

        return $screen;
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
