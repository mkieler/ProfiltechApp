<?php

declare(strict_types=1);

namespace Modules\Wordpress\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoocommerceOrder extends Model
{
    use HasFactory;

    protected $table = 'wc_orders';

    protected $connection = 'wordpress';

    protected $fillable = [];

    protected $hidden = [];

    protected $with = ['meta'];

    protected function casts(): array
    {
        return [];
    }

    public function lines()
    {
        return $this->hasMany(WoocommerceOrderLine::class, 'order_id', 'id');
    }

    public function meta()
    {
        return $this->hasMany(WoocommerceOrderMeta::class, 'order_id', 'id');
    }

    public function shipping()
    {
        return $this->hasOne(WoocommerceAddress::class, 'order_id', 'id')->where('address_type', 'shipping');
    }

    public function billing()
    {
        return $this->hasOne(WoocommerceAddress::class, 'order_id', 'id')->where('address_type', 'billing');
    }
}
