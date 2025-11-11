<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Delivery\External\Dawa\DawaClient;
use Modules\Wordpress\Models\WoocommerceOrder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['wc_order_id', 'latitude', 'longitude'];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }

    protected $appends = ['shipping', 'billing', 'latitude', 'longitude'];

    protected $with = ['wcOrder.shipping', 'wcOrder.billing'];


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

    protected function longitude(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    $value = DawaClient::getLongitude(
                        $this->shipping->address_1,
                        $this->shipping->postcode
                    );
                    $this->update(['longitude' => $value]);
                }
                return $value;
            },
            set: fn ($value) => $this->longitude = $value
        );
    }

    protected function latitude(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    $value = DawaClient::getLatitude(
                        $this->shipping->address_1,
                        $this->shipping->postcode
                    );
                    $this->update(['latitude' => $value]);
                }
                return $value;
            },
            set: fn ($value) => $this->latitude = $value
        );
    }
}
