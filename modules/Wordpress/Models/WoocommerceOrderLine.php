<?php

namespace Modules\Wordpress\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoocommerceOrderLine extends Model
{
    use HasFactory;

    protected $table = 'woocommerce_order_items';
    protected $connection = 'wordpress';
    protected $fillable = [];
    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }

    public function meta()
    {
        return $this->hasMany(WoocommerceOrderLineMeta::class, 'order_item_id', 'order_item_id');
    }
}
