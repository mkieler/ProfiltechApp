<?php

declare(strict_types=1);

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Delivery\Database\Factories\StopFactory;
use Modules\Delivery\External\Dawa\DawaClient;
use Modules\Order\Models\Order;

class Stop extends Model
{
    /** @use HasFactory<StopFactory> */
    use HasFactory;

    protected $fillable = ['order_id', 'route_id', 'sequence', 'latitude', 'longitude', 'service_time'];

    protected function casts(): array
    {
        return [];
    }

    protected static function newFactory(): StopFactory
    {
        return StopFactory::new();
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * @return Attribute<float|null, float|null>
     */
    protected function longitude(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (! $value) {
                    $order = $this->order;
                    $shipping = $order?->shipping;

                    if ($shipping !== null && isset($shipping->address_1, $shipping->postcode)) {
                        $value = DawaClient::getLongitude(
                            $shipping->address_1,
                            (int) $shipping->postcode
                        );
                        $this->update(['longitude' => $value]);
                    }
                }

                return $value;
            },
            set: fn ($value) => $value
        );
    }

    /**
     * @return Attribute<float|null, float|null>
     */
    protected function latitude(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (! $value) {
                    $order = $this->order;
                    $shipping = $order?->shipping;

                    if ($shipping !== null && isset($shipping->address_1, $shipping->postcode)) {
                        $value = DawaClient::getLatitude(
                            $shipping->address_1,
                            (int) $shipping->postcode
                        );
                        $this->update(['latitude' => $value]);
                    }
                }

                return $value;
            },
            set: fn ($value) => $value
        );
    }
}
