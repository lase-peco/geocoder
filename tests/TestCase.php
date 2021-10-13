<?php

namespace LasePeCo\Geocoder\Tests;

use Illuminate\Foundation\Application;
use LasePeCo\Geocoder\Facades\Geocoder;
use LasePeCo\Geocoder\ServiceProvider;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * Override application aliases.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Geocoder' => Geocoder::class,
        ];
    }

}
