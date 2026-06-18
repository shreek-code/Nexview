<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PlatformAuditLog;

class ImpersonationController extends Controller
{
    /**
     * Start impersonating a user.
     */
    public function start(User $user)
    {
        // Must be logged in as a platform admin
        if (!auth('platform')->check()) {
            abort(403, 'Unauthorized.');
        }

        // Store the original platform admin ID in the session
        session()->put('impersonator_id', auth('platform')->id());
        
        // Log in as the targeted user using the default web guard
        auth('web')->login($user);

        PlatformAuditLog::create([
            'organization_id' => $user->organization_id,
            'platform_user_id' => session('impersonator_id'),
            'action' => 'impersonation_started',
            'metadata' => ['target_user_id' => $user->id, 'target_user_email' => $user->email],
            'created_at' => now(),
        ]);

        return redirect()->route('app.dashboard')
            ->with('message', 'You are now impersonating ' . $user->name);
    }

    /**
     * Stop impersonating and return to the admin dashboard.
     */
    public function stop()
    {
        // If not impersonating, just redirect to dashboard
        if (!session()->has('impersonator_id')) {
            return redirect()->route('app.dashboard');
        }

        $user = auth('web')->user();

        if ($user) {
            PlatformAuditLog::create([
                'organization_id' => $user->organization_id,
                'platform_user_id' => session('impersonator_id'),
                'action' => 'impersonation_ended',
                'metadata' => ['target_user_id' => $user->id],
                'created_at' => now(),
            ]);
        }

        // Log out the user
        auth('web')->logout();

        // Clear the impersonation flag
        session()->forget('impersonator_id');

        // Redirect back to admin dashboard
        return redirect()->route('admin.dashboard')
            ->with('message', 'Impersonation ended.');
    }
}
