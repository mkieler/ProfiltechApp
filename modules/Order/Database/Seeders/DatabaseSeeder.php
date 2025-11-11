<?php

declare(strict_types=1);

namespace Modules\Order\Database\Seeders;

use Modules\Order\Models\Order;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Order::factory()
            ->count(50)
            ->create();
    }
}
