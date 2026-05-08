<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'json' => \App\Http\Middleware\ForceJsonResponse::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\App\Exceptions\LeaveQuotaExceededException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        });

        $exceptions->render(function (\App\Exceptions\LeaveOverlapException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        });

        $exceptions->render(function (\App\Exceptions\UnauthorizedLeaveActionException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        });

        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        });
    })->create();
