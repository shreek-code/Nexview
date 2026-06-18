<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationIsOnboarded
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for platform admins or non-authenticated routes
        if (!auth()->check() || auth()->guard('platform')->check()) {
            return $next($request);
        }

        $organization = auth()->user()->organization;
        $route = $request->route()->getName();

        if (!$organization->is_onboarded) {
            if ($route !== 'app.onboarding') {
                return redirect()->route('app.onboarding');
            }
        } else {
            $subscription = $organization->subscription;

            if (!$subscription) {
                if ($route !== 'app.onboarding') {
                    return redirect()->route('web.pricing')->with('error', 'Please select a plan to continue.');
                }
            } else if (!$subscription->isActive()) {
                if ($route !== 'app.billing.index' && $route !== 'app.settings.index' && $route !== 'app.onboarding') {
                    return redirect()->route('app.billing.index')->with('error', 'Please activate your subscription to continue.');
                }
            }
        }

        return $next($request);
    }
}
