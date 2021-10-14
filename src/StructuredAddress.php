<?php

namespace LasePeCo\Geocoder;

/**
 * @method StructuredAddress street(string $string)
 * @method StructuredAddress city(string $string)
 * @method StructuredAddress county(string $string)
 * @method StructuredAddress state(string $string)
 * @method StructuredAddress country(string $string)
 * @method StructuredAddress postalcode(string $string)
 */
class StructuredAddress
{
    protected array $address = [];

    public function __call(string $name, array $arguments)
    {
        if (! in_array($name, [
            'street',
            'city',
            'county',
            'state',
            'country',
            'postalcode',
        ])) {
            throw new \Exception("Method: {$name} is not supported");
        }

        $this->address[$name] = $arguments[0];

        return $this;
    }

    public function toArray()
    {
        return $this->address;
    }
}
