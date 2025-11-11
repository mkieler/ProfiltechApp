<?php

declare(strict_types=1);

namespace Modules\Wordpress\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Shared\Data\QueryFilter;
use Modules\Shared\Data\SearchFilter;
use Modules\Wordpress\Models\WoocommerceOrder;

class OrderService
{
    /**
     * @param  array<QueryFilter>|null  $filters
     * @return LengthAwarePaginator<int, WoocommerceOrder>
     */
    public function getOrders(
        ?array $filters = null,
        ?SearchFilter $searchFilter = null,
        int $perPage = 20,
        bool $withLines = true,
        bool $unprocessedOnly = true
    ): LengthAwarePaginator {
        return WoocommerceOrder::when(
            $withLines,
            fn ($query) => $query->with(['lines.meta', 'meta'])
        )
            ->when(
                $unprocessedOnly,
                fn ($query) => $query->where('status', 'processing')
            )
            ->when($filters !== null && $filters !== [], function ($query) use ($filters): void {
                if ($filters !== null) {
                    foreach ($filters as $filter) {
                        $query->where($filter->field, $filter->operator, $filter->value);
                    }
                }
            })
            ->paginate($perPage);
    }

    public function getOrderById(int $id): ?WoocommerceOrder
    {
        return WoocommerceOrder::with(['lines.meta', 'meta'])->find($id);
    }
}
