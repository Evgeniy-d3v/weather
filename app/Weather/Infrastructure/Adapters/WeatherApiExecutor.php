<?php

namespace App\Weather\Infrastructure\Adapters;

use App\Weather\Application\WeatherApiExecutorInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WeatherApiExecutor implements WeatherApiExecutorInterface
{
    public function __construct(
        public Client $httpClient,
        public WeatherResponseMapper $responseMapper,
    )
    {

    }

//    public function getCurrentWeather(float $latitude, float $longitude): void
    public function getCurrentWeather(): void

    {

        $response = $this->httpClient->get('weather?lat='.'59.3293'.'&lon='.'18.0686'.'&appid='.config('weather.api_key'));

        $data = json_decode($response->getBody()->getContents(), true);

        Log::debug('WeatherApiExecutor getCurrentWeather: ' . json_encode($data));
    }
}
