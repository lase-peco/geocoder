<?php

namespace LasePeCo\Geocoder;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Geocoder
{
    /**
     * @var array $parameters
     */
    protected array $parameters = [
        'format' => 'json',
    ];

    private string $baseUrl = 'https://nominatim.openstreetmap.org/';

    public function search(string|StructuredAddress $address)
    {
        if (is_string($address)) {
            $this->parameters['q'] = $address;
        }

        if ($address instanceof StructuredAddress) {
            $this->parameters += $address->toArray();
        }

        return $this->CallApi('search');
    }

    private function CallApi(string $type)
    {
        if (!in_array($type, [
            'search',
            'reverse'
        ])) {
            throw new Exception("Type: {$type} is not supported");
        }

        $query = $this->buildQuery();


        return Cache::remember($query, $three_months = 60 * 60 * 24 * 30 * 3, function () use ($type, $query) {
            return Http::withoutVerifying()->get($this->baseUrl . $type . '?' . $query)->body();
        });
    }

    protected function buildQuery(): string
    {
        return http_build_query($this->parameters);
    }

    public function reverse($lat, $long)
    {
        $this->parameters += [
            'lat' => $lat,
            'lon' => $long,
        ];

        return $this->CallApi('reverse');
    }

    public function lang(string $lang)
    {
        if (!is_string($lang) || strlen($lang) > 2) {
            throw new Exception("Language: {$lang} is not supported");
        }

        $this->parameters['accept-language'] = strtolower($lang);

        return $this;
    }

    public function json()
    {
        $this->format('json');

        return $this;
    }

    public function format($format)
    {
        if (!in_array($format, ['xml', 'json', 'jsonv2', 'geojson', 'geocodejson'])) {
            throw new Exception("Format: {$format} not supported");
        }

        $this->parameters['format'] = $format;

        return $this;
    }

    public function xml()
    {
        $this->format('xml');

        return $this;
    }

    public function withDetails()
    {
        $this->parameters['addressdetails'] = 1;

        return $this;
    }

    public function withExtraTags()
    {
        $this->parameters['extratags'] = 1;

        return $this;
    }

    public function withNameDetails()
    {
        $this->parameters['namedetails'] = 1;

        return $this;
    }
}
