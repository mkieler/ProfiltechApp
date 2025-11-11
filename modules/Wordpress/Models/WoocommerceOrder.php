<?php

declare(strict_types=1);

namespace Modules\Wordpress\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WoocommerceOrder extends Model
{
    /** @use HasFactory<\Illuminate\Database\Eloquent\Factories\Factory<static>> */
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

    /**
     * @return HasMany<WoocommerceOrderLine, $this>
     */
    public function lines(): HasMany
    {
        return $this->hasMany(WoocommerceOrderLine::class, 'order_id', 'id');
    }

    /**
     * @return HasMany<WoocommerceOrderMeta, $this>
     */
    public function meta(): HasMany
    {
        return $this->hasMany(WoocommerceOrderMeta::class, 'order_id', 'id');
    }

    /**
     * @return HasOne<WoocommerceAddress, $this>
     */
    public function shipping(): HasOne
    {
        return $this->hasOne(WoocommerceAddress::class, 'order_id', 'id')->where('address_type', 'shipping');
    }

    /**
     * @return HasOne<WoocommerceAddress, $this>
     */
    public function billing(): HasOne
    {
        return $this->hasOne(WoocommerceAddress::class, 'order_id', 'id')->where('address_type', 'billing');
    }
}
