<?php

namespace Modules\Order\Services;

use Modules\Order\Models\Order;
use Modules\Shared\Data\QueryFilters;

class OrderService
{
    public function getOrders(QueryFilters $filters)
    {
        $test = Order::query()
            ->isOnRoute($filters->getFilter('is_on_route'))
            ->hasStatus($filters->getFilter('status', ['wc-pending', 'wc-processing']))
            ->search($filters->search)
            ->sort($filters->sortBy, $filters->sortOrder)
            ->paginate($filters->perPage);

        return $test;
    }

    public function getOrder(int $id)
    {
        return Order::findOrFail($id);
    }
}
