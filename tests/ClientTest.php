<?php

namespace LasePeCo\Geocoder\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use LasePeCo\Geocoder\Geocoder;

class ClientTest extends TestCase
{
    /** @test */
    public function it_works()
    {
        $address = 'Rudolf-Diesel-Str. 115, 46485 Wesel, Germany';

        (new Geocoder())->search($address);

        Http::assertSent(function (Request $request) use ($address) {
            $this->assertEquals($address, $request['q']);
            $this->assertEquals('json', $request['format']);
            $this->assertStringContainsString('search?', $request->url());

            return true;
        });
    }

    /** @test */
    public function it_supports_multiple_langs()
    {
        (new Geocoder())->lang('de')->search('address');

        Http::assertSent(function (Request $request) {
            return $request['accept-language'] == 'de';
        });
    }

    /** @test */
    public function it_throws_an_error_for_unsupported_lagns()
    {
        $this->expectException(\Exception::class);

        (new Geocoder())->lang('alfa')->search('address');
    }

    /** @test */
    public function json_is_standard_format()
    {
        (new Geocoder())->search('address');

        Http::assertSent(function (Request $request) {
            return $request['format'] == 'json';
        });
    }

    /** @test */
    public function it_fetches_data_in_xml_format()
    {
        (new Geocoder())->xml()->search('address');

        Http::assertSent(function (Request $request) {
            return $request['format'] == 'xml';
        });
    }

    /** @test */
    public function it_fetches_data_in_json_format()
    {
        (new Geocoder())->json()->search('address');

        Http::assertSent(function (Request $request) {
            return $request['format'] == 'json';
        });
    }

    /** @test */
    public function user_can_give_a_specific_format()
    {
        (new Geocoder())->format('jsonv2')->search('address');

        Http::assertSent(function (Request $request) {
            return $request['format'] == 'jsonv2';
        });
    }

    /** @test */
    public function it_throws_an_exception_if_format_is_not_supported()
    {
        $this->expectException(\Exception::class);

        (new Geocoder())->format('asdasd')->search('address');
    }

    /** @test */
    public function it_fetches_extra_details()
    {
        (new Geocoder())->withDetails()->search('address');

        Http::assertSent(function (Request $request) {
            return $request['addressdetails'] == 1;
        });

    }

    /** @test */
    public function it_fetches_extra_tags()
    {
        (new Geocoder())->withExtraTags()->search('address');

        Http::assertSent(function (Request $request) {
            return $request['extratags'] == 1;
        });

    }

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();
    }

}
