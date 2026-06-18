<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceSubscriptionLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $limitType = 'storage'): Response
    {
        $organization = $request->user()->organization;

        if (!$organization) {
            return response()->json(['error' => 'Organization not found.'], 403);
        }

        $billingService = app(\App\Services\BillingService::class);

        try {
            if ($limitType === 'screens') {
                $billingService->enforceScreenLimit($organization);
            } elseif ($limitType === 'storage') {
                $billingService->enforceStorageLimit($organization);
            }
        } catch (\App\Exceptions\PlanLimitExceededException $e) {
            if ($request->wantsJson() || $request->is('api/*') || $request->is('app/media/upload')) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'code' => 'plan_limit_exceeded',
                ], 403);
            }

            return redirect()->route('app.settings.index')
                ->with('error', $e->getMessage());
        }

        return $next($request);
    }
}
