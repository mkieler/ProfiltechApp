<?php

declare(strict_types=1);

namespace Modules\Delivery\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Delivery\Models\Route;
use Modules\Delivery\Models\Stop;
use Modules\Order\Models\Order;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 routes with different statuses
        $routes = collect([
            Route::factory()->draft()->create(['name' => 'Morning Route - North']),
            Route::factory()->processing()->create(['name' => 'Afternoon Route - South']),
            Route::factory()->processing()->create(['name' => 'Evening Route - West']),
            Route::factory()->completed()->create(['name' => 'Morning Route - East']),
            Route::factory()->cancelled()->create(['name' => 'Weekend Route']),
            Route::factory()->draft()->count(2)->create(),
            Route::factory()->processing()->count(2)->create(),
            Route::factory()->completed()->create(),
        ])->flatten();

        // For each route, create orders and stops
        $routes->each(function (Route $route, $index): void {
            // Skip cancelled routes
            if ($route->status === 'CANCELLED') {
                return;
            }

            // Create 3-8 stops per route
            $stopCount = random_int(3, 8);

            for ($i = 1; $i <= $stopCount; $i++) {
                // Create an order for this stop
                $order = Order::factory()->create();

                // Create the stop with proper sequence
                Stop::factory()
                    ->forRoute($route)
                    ->forOrder($order)
                    ->withSequence($i)
                    ->create();
            }
        });

        $this->command->info('Delivery module seeded successfully!');
        $this->command->info('Created ' . Route::count() . ' routes with ' . Stop::count() . ' stops.');
    }
}
