<?php

namespace Modules\Delivery\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Delivery\Events\StopsUpdatedEvent;
use Modules\Delivery\Listeners\OptimizeRoute;

class DeliveryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Load routes as API routes (no prefix)
        Route::middleware('api')->group(__DIR__ . '/../Routes/api.php');

        // Register event listeners
        Event::listen(
            StopsUpdatedEvent::class,
            OptimizeRoute::class
        );
    }
}
