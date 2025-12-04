<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\User\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Mattias Kieler',
            'email' => 'mattias@mkieler.com',
            'password' => 'test1234',
        ]);

        $this->call([
            \Modules\Order\Database\Seeders\DatabaseSeeder::class,
            \Modules\Delivery\Database\Seeders\DatabaseSeeder::class,
        ]);
    }
}
