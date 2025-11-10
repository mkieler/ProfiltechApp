<?php

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Delivery\External\Dawa\DawaClient;
use Modules\Order\Models\Order;

class Stop extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'sequence', 'latitude', 'longitude'];

    protected function casts(): array
    {
        return [];
    }

    protected static function newFactory()
    {
        return \Modules\Delivery\Database\Factories\StopFactory::new();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    protected function longitude(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    $value = DawaClient::getLongitude(
                        $this->order->shipping->address_1,
                        $this->order->shipping->postcode
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
                        $this->order->shipping->address_1,
                        $this->order->shipping->postcode
                    );
                    $this->update(['latitude' => $value]);
                }
                return $value;
            },
            set: fn ($value) => $this->latitude = $value
        );
    }
}