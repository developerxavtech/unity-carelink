<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'dsp.verified' => \App\Http\Middleware\EnsureDspIsVerified::class,
        ]);

        // The public join-waitlist form is posted from outside this app
        // (no Laravel-rendered CSRF token available), so it's exempted here.
        $middleware->validateCsrfTokens(except: [
            'api/register-join-waitlist',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
