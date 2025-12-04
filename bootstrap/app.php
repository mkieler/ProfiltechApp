<?php

use App\Exceptions\CustomException;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpKernel\Exception\HttpException;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../app/routes/api.php',
        commands: __DIR__.'/../app/routes/console.php',
        health: '/up',
        apiPrefix: '',
        then: function () {
            // Define Fortify rate limiters
            RateLimiter::for('login', function (Request $request) {
                return Limit::perMinute(5)->by($request->email.$request->ip());
            });

            RateLimiter::for('two-factor', function (Request $request) {
                return Limit::perMinute(5)->by($request->session()->get('login.id'));
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // API middleware with Sanctum for SPA authentication
        $middleware->api(
            prepend: [
                EnsureFrontendRequestsAreStateful::class,
            ],
            append: [
                'auth:sanctum',
            ]
        );

        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (CustomException $e) {
            return response()->json([
                'title' => $e->getTitle(),
                'message' => $e->getMessage(),
                'error_code' => $e->getErrorCode(),
            ], $e->getStatusCode());
        });

        $exceptions->renderable(function (HttpException $e) {
            return response()->json([
                'title' => 'HTTP Fejl',
                'message' => $e->getMessage(),
                'error_code' => 'HTTP_'.$e->getStatusCode(),
            ], $e->getStatusCode());
        });

        $exceptions->renderable(function (ValidationException $e) {
            return response()->json([
                'title' => 'Valideringsfejl',
                'message' => 'Der opstod valideringsfejl.',
                'errors' => $e->errors(),
                'error_code' => 'VAL_001',
            ], 422);
        });

        $exceptions->renderable(function (Throwable $e) {
            return response()->json([
                'title' => 'Der skete en fejl',
                'message' => 'Der skete en ukendt fejl under behandlingen af din anmodning.',
                'error_code' => 'SYS_999',
            ], 500);
        });
    })
    ->create();

// Configure custom paths
$app->useDatabasePath($app->basePath('app/database'));
$app->useStoragePath($app->basePath('storage'));

// Set resource path via binding
$app->bind('path.resources', function () use ($app) {
    return $app->basePath('app/resources');
});

$app->bind('path.lang', function () use ($app) {
    return $app->basePath('app/resources/lang');
});

return $app;
