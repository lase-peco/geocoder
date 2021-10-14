<?php

namespace LasePeCo\Geocoder\Facades;

use Illuminate\Support\Facades\Facade;
use LasePeCo\Geocoder\StructuredAddress;

/**
 * @method static \LasePeCo\Geocoder\Geocoder withAddressDetails()
 * @method static \LasePeCo\Geocoder\Geocoder withExtraTags()
 * @method static \LasePeCo\Geocoder\Geocoder format(string $format)
 * @method static \LasePeCo\Geocoder\Geocoder json()
 * @method static \LasePeCo\Geocoder\Geocoder xml()
 * @method static mixed search(string|StructuredAddress $address)
 * @method static mixed reverse($lat, $lon)
 * @method static \LasePeCo\Geocoder\Geocoder language(string $language)
 * @method static \LasePeCo\Geocoder\Geocoder withNameDetails()
 */
class Geocoder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'geocoder';
    }
}
