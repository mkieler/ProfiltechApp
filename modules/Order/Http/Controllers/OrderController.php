<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Order\Http\Requests\ListOrdersRequest;
use Modules\Order\Services\OrderService;

class OrderController
{
    public function __construct(
        public OrderService $orderService
    ) { }

    public function list(ListOrdersRequest $request)
    {
        return $this->orderService->getOrders($request->toQueryFilters());
    }

    public function details(int $id)
    {
        return $this->orderService->getOrder($id);
    }
}
