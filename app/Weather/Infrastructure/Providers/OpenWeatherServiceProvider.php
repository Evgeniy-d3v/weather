<?php

namespace App\Weather\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use PhpWeather\Provider\OpenMeteo\OpenMeteo;
use GuzzleHttp\Client;

class OpenWeatherServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OpenMeteo::class, function ($app) {
            return new OpenMeteo($app->make(Client::class));
        });
    }
}
