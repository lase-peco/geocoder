<?php

namespace LasePeCo\Geocoder\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use LasePeCo\Geocoder\Geocoder;
use LasePeCo\Geocoder\StructuredAddress;

class StructuredAddressTest extends TestCase
{
    /** @test */
    public function it_works()
    {
        $address = new StructuredAddress();

        $address->street('Rudolf-Diesel-Str. 115')->city('Wesel')->postalcode('46485')->state('NRW')->country('Germany');

        (new Geocoder())->search($address);

        Http::assertSent(function (Request $request) {
            $this->assertEquals('Rudolf-Diesel-Str. 115', $request['street']);
            $this->assertEquals('Wesel', $request['city']);
            $this->assertEquals('46485', $request['postalcode']);
            $this->assertEquals('NRW', $request['state']);
            $this->assertEquals('Germany', $request['country']);

            return true;
        });
    }

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();
    }
}
