<?php

declare(strict_types=1);

namespace Modules\Wordpress\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WoocommerceOrderLine extends Model
{
    /** @use HasFactory<\Illuminate\Database\Eloquent\Factories\Factory<static>> */
    use HasFactory;

    protected $table = 'woocommerce_order_items';

    protected $connection = 'wordpress';

    protected $fillable = [];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }

    /**
     * @return HasMany<WoocommerceOrderLineMeta, $this>
     */
    public function meta(): HasMany
    {
        return $this->hasMany(WoocommerceOrderLineMeta::class, 'order_item_id', 'order_item_id');
    }
}
