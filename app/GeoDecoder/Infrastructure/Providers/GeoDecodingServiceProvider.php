<?php

namespace App\GeoDecoder\Infrastructure\Providers;
use App\GeoDecoder\Application\GeoDecoderApiExecutorInterface;
use App\GeoDecoder\Application\Repositories\CityRepositoryInterface;
use App\GeoDecoder\Infrastructure\Adapters\GeoDecoderApiAdapter;
use App\GeoDecoder\Infrastructure\Adapters\GeoDecoderResponseMapper;
use App\GeoDecoder\Infrastructure\Persistence\Repositories\CityRepository;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class GeoDecodingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GeoDecoderApiExecutorInterface::class, function($app) {
            $httpClient = new Client([
                'base_uri' => config('geo_decoder.base_api_url'),
                'timeout' => 20,
                'http_errors' => false,
            ]);

            return new GeoDecoderApiAdapter(
                $httpClient,
                $app->make(GeoDecoderResponseMapper::class),
            );
        });

        $this->app->bind(CityRepositoryInterface::class, function($app) {
            return new CityRepository();
        });
    }


    public function boot(): void
    {
    }
}

