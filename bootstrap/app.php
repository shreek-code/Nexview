<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'org.auth' => \App\Http\Middleware\AuthenticatedOrgMiddleware::class,
            'manager.scope' => \App\Http\Middleware\ManagerScopeMiddleware::class,
            'onboarded' => \App\Http\Middleware\EnsureOrganizationIsOnboarded::class,
            'plan.limits' => \App\Http\Middleware\EnforceSubscriptionLimits::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
