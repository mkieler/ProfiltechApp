<?php

declare(strict_types=1);

namespace Modules\Delivery\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Models\Route;

class RouteFactory extends Factory
{
    protected $model = Route::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Morning Route',
                'Afternoon Route',
                'Evening Route',
                'Route ' . fake()->numberBetween(1, 100),
                fake()->city() . ' Route',
            ]),
            'origin' => fake()->address(),
            'status' => fake()->randomElement([
                DeliveryStatus::DRAFT->name,
                DeliveryStatus::PROCESSING->name,
                DeliveryStatus::COMPLETED->name,
                DeliveryStatus::CANCELLED->name,
            ]),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DeliveryStatus::DRAFT->name,
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DeliveryStatus::PROCESSING->name,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DeliveryStatus::COMPLETED->name,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DeliveryStatus::CANCELLED->name,
        ]);
    }
}
