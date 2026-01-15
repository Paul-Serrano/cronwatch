<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (Throwable $e) {
            // On ne déclenche l'alerte que en production
            if (!app()->environment('production')) {
                return;
            }

            // Filtrer le bruit classique
            if ($e instanceof ValidationException) {
                return;
            }

            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                // 401/403/404/419 = pas des "pannes" de prod
                if (in_array($status, [401, 403, 404, 419], true)) {
                    return;
                }
            }

            // Fait échouer /health pendant 5 minutes => monitor DOWN => incident => push
            Cache::put(
                'health:last_critical_at',
                now()->toIso8601String(),
                now()->addMinutes(5)
            );
        });
    })->create();
