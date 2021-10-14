<?php

namespace LasePeCo\Geocoder\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use LasePeCo\Geocoder\Facades\Geocoder;

class SearchTest extends TestCase
{
    /** @test */
    public function it_works()
    {
        $address = 'Rudolf-Diesel-Str. 115, 46485 Wesel, Germany';

        Geocoder::search($address);

        Http::assertSent(function (Request $request) use ($address) {
            $this->assertEquals($address, $request['q']);
            $this->assertEquals('json', $request['format']);
            $this->assertStringContainsString('search?', $request->url());

            return true;
        });
    }

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();
    }
}
