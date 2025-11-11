<?php

declare(strict_types=1);

namespace Modules\Delivery\External\Dawa;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Modules\Delivery\External\Dawa\Data\DawaAddressResponse;

class DawaClient
{
    public static function getLongitude(string $address, int $postalCode): float
    {
        return (float) self::getAddressData($address, $postalCode)?->x;
    }

    public static function getLatitude(string $address, int $postalCode): float
    {
        return (float) self::getAddressData($address, $postalCode)?->y;
    }

    public static function getAddressData(
        string $address,
        int $postalCode
    ): ?DawaAddressResponse {
        return Cache::remember(
            "dawa:{$address}:{$postalCode}:addressData",
            86400,
            function () use ($address, $postalCode) {
                $data = Http::get('https://api.dataforsyningen.dk/adresser', [
                    'q' => $address,
                    'postnr' => $postalCode,
                    'format' => 'json',
                    'struktur' => 'mini',
                ])->json()[0];

                return empty($data) ? null : DawaAddressResponse::from($data);
            }
        );
    }
}
