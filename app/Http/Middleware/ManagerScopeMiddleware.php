<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerScopeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'manager') {
            $locationIds = \App\Models\ManagerLocation::where('user_id', $user->id)
                ->pluck('location_id')
                ->toArray();

            $request->attributes->set('manager_location_ids', $locationIds);
        }

        return $next($request);
    }
}
