<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../app/routes/web.php',
        commands: __DIR__.'/../app/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
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
