<?php

declare(strict_types=1);

namespace Modules\Delivery\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Delivery\Events\RouteUpdatedEvent;
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

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // Register event listeners
        Event::listen(
            RouteUpdatedEvent::class,
            OptimizeRoute::class
        );
    }
}
