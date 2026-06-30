<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ScreenService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class PlayerRegistrationController extends Controller
{
    private ScreenService $screenService;

    public function __construct(ScreenService $screenService)
    {
        $this->screenService = $screenService;
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'registration_code' => 'required|string|size:6',
            'device_id'         => 'required|string',
            'player_version'    => 'nullable|string|max:50',
            'resolution'        => 'nullable|string|max:20',
            'orientation'       => 'nullable|in:landscape,portrait',
        ]);

        //    dd(Cache::getDefaultDriver());
        $screen = $this->screenService->pairScreen(
            $validated['registration_code'],
            $validated['device_id'],
            $validated['player_version'] ?? '1.0.0',
            $validated['resolution'] ?? null,
            $validated['orientation'] ?? null
        );

        if (!$screen) {
            Cache::driver('redis')->put(
                'device_registration:' . strtoupper($validated['registration_code']),
                [
                    'device_id'      => $validated['device_id'],
                    'player_version' => $validated['player_version'] ?? '1.0.0',
                    'resolution'     => $validated['resolution'] ?? null,
                    'orientation'    => $validated['orientation'] ?? null,
                ],
                now()->addMinutes(5)
            );

            // dd(Cache::driver('redis')->get('device_registration:' . strtoupper($validated['registration_code'])));
            return response()->json([
                'status'  => 'pending',
                'message' => 'Waiting for user to enter code in the dashboard.',
            ], 202); // 202 Accepted = keep polling
        }

        // Issue a Sanctum token for the player
        $token = $screen->createToken('player-auth-token')->plainTextToken;

        return response()->json([
            'status'         => 'success',
            'message'        => 'Successfully registered.',
            'device_id'      => $screen->device_id,
            'screen_name'    => $screen->name,
            'location_id'    => $screen->location_id,
            'token'          => $token,
            'reverb_app_key' => config('broadcasting.connections.reverb.key'),
        ]);
    }
}
