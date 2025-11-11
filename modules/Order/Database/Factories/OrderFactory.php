<?php

declare(strict_types=1);

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Models\Order;
use Modules\Wordpress\Models\WoocommerceOrder;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'wc_order_id' => WoocommerceOrder::inRandomOrder()->first()->id,
        ];
    }
}
