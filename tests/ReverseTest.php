<?php

namespace LasePeCo\Geocoder\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use LasePeCo\Geocoder\Facades\Geocoder;

class ReverseTest extends TestCase
{
    /** @test */
    public function it_works()
    {
        $lat = 51.6675239;
        $lon = 51.6675239;

        Geocoder::reverse($lat, $lon);

        Http::assertSent(function (Request $request) use ($lon, $lat) {
            $this->assertEquals($lat, $request['lat']);
            $this->assertEquals($lon, $request['lon']);
            $this->assertStringContainsString('reverse?', $request->url());

            return true;
        });
    }

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();
    }
}
