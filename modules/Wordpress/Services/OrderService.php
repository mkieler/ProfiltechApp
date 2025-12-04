<?php

namespace Modules\Wordpress\Services;

use Modules\Shared\Data\SearchFilter;
use Modules\Wordpress\Models\WoocommerceOrder;

class OrderService
{

    public function getOrderById(int $id)
    {
        return WoocommerceOrder::with(['lines.meta', 'meta'])->find($id);
    }
}
