<?php

declare(strict_types=1);

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Models\Order;
use Modules\Wordpress\Models\WoocommerceOrder;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $order = WoocommerceOrder::inRandomOrder()->first();

        return [
            'wc_order_id' => $order !== null ? $order->id : 1,
        ];
    }
}
