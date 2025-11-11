<?php

declare(strict_types=1);

namespace Modules\Wordpress\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoocommerceOrderLineMeta extends Model
{
    use HasFactory;

    protected $table = 'woocommerce_order_itemmeta';

    protected $connection = 'wordpress';

    protected $fillable = [];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }
}
