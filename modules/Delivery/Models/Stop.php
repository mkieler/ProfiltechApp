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

    protected $fillable = ['order_id', 'sequence', 'status', 'service_time'];

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

    public function getLatitudeAttribute(): ?float
    {
        return $this->order->latitude;
    }

    public function getLongitudeAttribute(): ?float
    {
        return $this->order->longitude;
    }
}