<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedOrgMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Ensure user belongs to an organization (Platform Users don't use this middleware)
        if (auth()->guard() !== 'platform' && !$user->organization_id) {
            abort(403, 'User does not belong to an organization.');
        }

        // Check if organization is suspended
        if (auth()->guard() !== 'platform' && $user->organization->status === 'suspended') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Your organization account has been suspended. Please contact support.']);
        }

        // Bind organization to request for easy access
        if (auth()->guard() !== 'platform') {
            $request->attributes->set('organization_id', $user->organization_id);
            $request->attributes->set('organization', $user->organization);
        }

        return $next($request);
    }
}
