<?php

namespace Modules\Order\Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \Modules\Order\Models\Order::factory()
            ->count(50)
            ->create();
    }
}
