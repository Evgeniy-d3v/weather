<?php

namespace App\Weather\Infrastructure\Providers;

use App\Weather\Application\WeatherApiExecutorInterface;
use App\Weather\Infrastructure\Adapters\WeatherApiExecutor;
use App\Weather\Infrastructure\Adapters\WeatherResponseMapper;
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

        $this->app->bind(WeatherApiExecutorInterface::class, function($app) {
            $httpClient = new Client([
                'base_uri' => config('weather.base_api_url'),
                'timeout' => 20,
                'http_errors' => false,
            ]);

            return new WeatherApiExecutor(
                $httpClient,
                $app->make(WeatherResponseMapper::class),
            );
        });
    }
}
