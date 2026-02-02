<?php

namespace App\Location\Infrastructure\Providers;

use App\Location\Application\GeoDecoderApiExecutorInterface;
use App\Location\Application\Repositories\CityRepositoryInterface;
use App\Location\Application\Repositories\WeatherForecastRepositoryInterface;
use App\Location\Application\WeatherApiExecutorInterface;
use App\Location\Infrastructure\Adapters\GeoDecoderApiAdapter;
use App\Location\Infrastructure\Adapters\GeoDecoderResponseMapper;
use App\Location\Infrastructure\Adapters\WeatherApiExecutor;
use App\Location\Infrastructure\Adapters\WeatherResponseMapper;
use App\Location\Infrastructure\Persistence\Event\CityCreatedEvent;
use App\Location\Infrastructure\Persistence\Repositories\CityRepository;
use App\Location\Infrastructure\Persistence\Repositories\WeatherForecastRepository;
use App\Location\Presentation\Commands\GetWeatherForecast;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;


class LocationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GeoDecoderApiExecutorInterface::class, function ($app) {
            $httpClient = new Client([
                'base_uri' => config('location.base_geo_decoding_api_url'),
                'timeout' => 20,
                'http_errors' => false,
            ]);

            return new GeoDecoderApiAdapter(
                $httpClient,
                $app->make(GeoDecoderResponseMapper::class),
            );
        });
        $this->app->bind(WeatherApiExecutorInterface::class, function ($app) {
            $httpClient = new Client([
                'base_uri' => config('location.base_meteo_api_url'),
                'timeout' => 20,
                'http_errors' => false,
            ]);

            return new WeatherApiExecutor(
                $httpClient,
                $app->make(WeatherResponseMapper::class),
            );
        });
        $this->app->bind(CityRepositoryInterface::class, function ($app) {
            return new CityRepository;
        });
        $this->app->bind(WeatherForecastRepositoryInterface::class, function ($app) {
            return new WeatherForecastRepository;
        });

        $this->app->bind(WeatherResponseMapper::class, function ($app) {
            return new WeatherResponseMapper(config('location.weather_condition_code_map'));
        });
    }

    public function boot(): void
    {

        if ($this->app->runningInConsole()) {
            $this->commands([
                GetWeatherForecast::class,
            ]);
        }
    }
}
