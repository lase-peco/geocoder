<?php

namespace LasePeCo\Geocoder\Tests;

use Exception;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use LasePeCo\Geocoder\Facades\Geocoder;

class ParametersTest extends TestCase
{
    /** @test */
    public function it_supports_multiple_langs()
    {
        Geocoder::language('de')->search('address');

        Http::assertSent(function (Request $request) {
            return $request['accept-language'] == 'de';
        });
    }

    /** @test */
    public function it_throws_an_error_for_unsupported_lagns()
    {
        $this->expectException(Exception::class);

        Geocoder::language('alfa')->search('address');
    }

    /** @test */
    public function json_is_standard_format()
    {
        Geocoder::search('address');

        Http::assertSent(function (Request $request) {
            return $request['format'] == 'json';
        });
    }

    /** @test */
    public function it_fetches_data_in_xml_format()
    {
        Geocoder::xml()->search('address');

        Http::assertSent(function (Request $request) {
            return $request['format'] == 'xml';
        });
    }

    /** @test */
    public function it_fetches_data_in_json_format()
    {
        Geocoder::json()->search('address');

        Http::assertSent(function (Request $request) {
            return $request['format'] == 'json';
        });
    }

    /** @test */
    public function user_can_give_a_specific_format()
    {
        Geocoder::format('jsonv2')->search('address');

        Http::assertSent(function (Request $request) {
            return $request['format'] == 'jsonv2';
        });
    }

    /** @test */
    public function it_throws_an_exception_if_format_is_not_supported()
    {
        $this->expectException(Exception::class);

        Geocoder::format('asdasd')->search('address');
    }

    /** @test */
    public function it_fetches_extra_details()
    {
        Geocoder::withAddressDetails()->search('address');

        Http::assertSent(function (Request $request) {
            return $request['addressdetails'] == 1;
        });
    }

    /** @test */
    public function it_fetches_extra_tags()
    {
        Geocoder::withExtraTags()->search('address');

        Http::assertSent(function (Request $request) {
            return $request['extratags'] == 1;
        });
    }

    /** @test */
    public function it_fetches_named_details()
    {
        Geocoder::withNameDetails()->search('address');

        Http::assertSent(function (Request $request) {
            return $request['namedetails'] == 1;
        });
    }

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();
    }
}
