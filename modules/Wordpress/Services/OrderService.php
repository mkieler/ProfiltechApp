<?php

declare(strict_types=1);

namespace Modules\Wordpress\Services;

use Modules\Shared\Data\QueryFilter;
use Modules\Shared\Data\SearchFilter;
use Modules\Wordpress\Models\WoocommerceOrder;

class OrderService
{
    /**
     * @param  array<QueryFilter>|null  $filters
     */
    public function getOrders(
        ?array $filters = null,
        ?SearchFilter $searchFilter = null,
        int $perPage = 20,
        bool $withLines = true,
        bool $unprocessedOnly = true
    ) {
        return WoocommerceOrder::when(
            $withLines,
            fn ($query) => $query->with(['lines.meta', 'meta'])
        )
            ->when(
                $unprocessedOnly,
                fn ($query) => $query->where('status', 'processing')
            )
            ->when($filters, function ($query) use ($filters) {
                foreach ($filters as $filter) {
                    $query->where($filter->field, $filter->operator, $filter->value);
                }

                return $query;
            })
            ->paginate($perPage);
    }

    public function getOrderById(int $id)
    {
        return WoocommerceOrder::with(['lines.meta', 'meta'])->find($id);
    }
}
