<?php

declare(strict_types=1);

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\Database\Factories\OrderFactory;
use Modules\Wordpress\Models\WoocommerceOrder;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = ['wc_order_id'];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    /**
     * @return BelongsTo<WoocommerceOrder, $this>
     */
    public function wcOrder(): BelongsTo
    {
        return $this->belongsTo(WoocommerceOrder::class, 'wc_order_id', 'id');
    }

    public function getShippingAttribute(): ?object
    {
        $wcOrder = $this->wcOrder;

        return $wcOrder?->shipping;
    }

    public function getBillingAttribute(): ?object
    {
        $wcOrder = $this->wcOrder;

        return $wcOrder?->billing;
    }
}
