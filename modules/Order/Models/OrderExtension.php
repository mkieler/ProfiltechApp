<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Delivery\External\Dawa\DawaClient;
use Modules\Delivery\Models\Stop;
use Modules\Shared\Traits\HasSearchScope;
use Modules\Shared\Traits\HasSortScope;
use Modules\Wordpress\Models\WoocommerceOrder;

class OrderExtension extends Model
{
    use HasFactory, HasSearchScope, HasSortScope;

    protected $fillable = ['wc_order_id', 'latitude', 'longitude'];

    protected $hidden = [];

    /**
     * Columns that can be searched
     */
    protected array $searchable = ['order_number', 'customer_name'];

    /**
     * Columns that can be sorted
     */
    protected array $sortable = ['id', 'order_number', 'customer_name', 'created_at', 'updated_at'];

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

    public function stop()
    {
        return $this->hasOne(Stop::class, 'order_id', 'id');
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
                        (int)$this->shipping->postcode
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
                        (int)$this->shipping->postcode
                    );
                    $this->update(['latitude' => $value]);
                }
                return $value;
            },
            set: fn ($value) => $this->latitude = $value
        );
    }

    /**
     * Scope to filter orders by route status
     *
     * @param Builder $query
     * @param bool|null $value - null: all orders, true: only on route, false: only not on route
     */
    public function scopeIsOnRoute(Builder $query, ?bool $value): Builder
    {
        return $query->when($value !== null, fn($query) =>
            $value ? $query->whereHas('stop') : $query->whereDoesntHave('stop')
        );
    }

    public function scopeHasStatus(Builder $query, ?array $statuses): Builder
    {
        if (empty($statuses)) {
            return $query;
        }

        // Get WooCommerce order IDs from wordpress database that match the statuses
        $wcOrderIds = WoocommerceOrder::whereIn('status', $statuses)
            ->pluck('id')
            ->toArray();

        // Then filter orders based on those IDs
        return $query->whereIn('wc_order_id', $wcOrderIds);
    }
}
