<?php

declare(strict_types=1);

namespace Modules\Wordpress\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoocommerceAddress extends Model
{
    /** @use HasFactory<\Illuminate\Database\Eloquent\Factories\Factory<static>> */
    use HasFactory;

    protected $table = 'wc_order_addresses';

    protected $connection = 'wordpress';

    protected $fillable = ['order_id', 'address_type', 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country', 'email', 'phone'];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }

    public function getHouseNumberAttribute(): ?string
    {
        $address = $this->address_1;
        $address = preg_replace('/\s+/', ' ', trim($address));

        $pattern = '/\b(\d+[a-zA-Z]?)\b(?=\s*(?:,|port|st\.|tv\.|th\.|mf\.|$|\s+\d{4}|\s+[A-ZÆØÅ]))/i';

        if (preg_match($pattern, (string) $address, $matches)) {
            return $matches[1];
        }

        if (preg_match('/\b(\d{1,3}[a-zA-Z]?)\b/', (string) $address, $matches)) {
            return $matches[1];
        }

        return '1';
    }
}
