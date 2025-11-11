<?php

declare(strict_types=1);

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Wordpress\Models\WoocommerceOrder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['wc_order_id'];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }

    protected static function newFactory()
    {
        return \Modules\Order\Database\Factories\OrderFactory::new();
    }

    public function wcOrder()
    {
        return $this->belongsTo(WoocommerceOrder::class, 'wc_order_id', 'id');
    }

    public function getShippingAttribute(): ?object
    {
        return $this->wcOrder->shipping;
    }

    public function getBillingAttribute(): ?object
    {
        return $this->wcOrder->billing;
    }
}
