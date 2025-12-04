<?php

namespace Modules\Delivery\External\Dawa;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Modules\Delivery\External\Dawa\Data\DawaAddressResponse;

class DawaClient
{
    public static function getLongitude(string $address, int $postalCode): float {
        return (float) self::getAddressData($address, $postalCode)?->x;
    }

    public static function getLatitude(string $address, int $postalCode): float {
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
                $data = self::fetchAddressData($address, $postalCode);
                if ($data) {
                    return $data;
                }

                // Fuzzy search if exact match not found
                return self::fetchAddressData($address, $postalCode, true);
            }
        );
    }

    private static function fetchAddressData(
        string $address,
        int $postalCode,
        bool $fuzzy = false
    ): ?DawaAddressResponse {

        if($address === 'Birkevej3') {
            $test = null;
        }
        $address = self::normalizeAddress($address);
        $data = Http::get("https://api.dataforsyningen.dk/adresser", [
            'q' => $address,
            'postnr' => $postalCode,
            'format' => 'json',
            'struktur' => 'mini',
            'fuzzy' => $fuzzy ? 'true' : 'false'
        ])->json()[0] ?? [];

        return empty($data) ? null : DawaAddressResponse::from($data);
    }


    private static function normalizeAddress(string $address): string
    {
        $address = strtolower($address);
        $address = preg_replace('/[^\w\s]/', '', $address); // Remove punctuation
        $address = preg_replace('/(\d+)/', ' $1', $address); // Ensure space between address and number
        $address = preg_replace('/\s+/', ' ', $address); // Replace multiple spaces with single space
        return trim($address);
    }
}