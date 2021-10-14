<?php

namespace LasePeCo\Geocoder;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Geocoder
{
    /**
     * @var array $parameters
     */
    protected array $parameters = [
        'format' => 'json',
    ];

    /**
     * @param PendingRequest $client
     */
    public function __construct(protected $client)
    {
    }

    public function search(string|StructuredAddress $address)
    {
        if ($address instanceof StructuredAddress) {
            $query = $address->toArray();
        } else {
            $query = ['q' => $address];
        }

        return $this->sendRequest('search?' . http_build_query($query + $this->parameters));
    }

    protected function sendRequest(string $url)
    {
        if (Cache::has('lase-peco-geocoder-rate-limit')) {
            throw new Exception('Too many requests');
        }

        $response = Cache::remember($url, config('geocoder.cache_time_in_seconds'), function () use ($url) {

            Cache::put('lase-peco-geocoder-rate-limit', true, config('geocoder.rate_limit_in_seconds'));

            return $this->client->get($url)->body();
        });

        if ($this->parameters['format'] == 'json') {
            return Collection::make(json_decode($response, false));
        }

        return $response;
    }


    public function reverse($lat, $long)
    {
        $query = http_build_query([
                'lat' => $lat,
                'lon' => $long,
            ] + $this->parameters);

        return $this->sendRequest('reverse?' . $query);
    }

    public function language(string $language)
    {
        if (!is_string($language) || strlen($language) > 2) {
            throw new Exception("Language: {$language} is not supported");
        }

        $this->parameters['accept-language'] = strtolower($language);

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

    public function withAddressDetails()
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
