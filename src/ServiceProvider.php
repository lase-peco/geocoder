<?php

namespace LasePeCo\Geocoder;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/geocoder.php',
            'geocoder'
        );

        $this->app->singleton('geocoder', function ($app) {
            return new Geocoder(
                Http::baseUrl(config('geocoder.base_url'))->withoutVerifying()->timeout(5)->retry(2, 1)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/geocoder.php' => config_path('geocoder.php'),
        ], 'config');
    }
}
