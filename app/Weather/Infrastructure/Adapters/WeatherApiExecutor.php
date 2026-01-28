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
    {}

    public function getCurrentWeather(string $latitude, string $longitude): WeatherDto
    {
        $usl = 'weather?lat='.$latitude.'&lon='.$longitude.'&appid='. config('weather.api_token');
        $baseUrl = $this->httpClient->getConfig('base_uri');
        Log::debug('Requesting current weather url: ' . $baseUrl . $usl);
        $response = $this->httpClient->get($usl);
        $weatherData = json_decode($response->getBody()->getContents(), true);
        Log::debug('Current weather response', $weatherData);
        return $this->responseMapper->mapCurrentWeather($weatherData);
    }
    public function getFiveDayThreeHourForecast(string $latitude, string $longitude): WeatherDtoCollection
    {
        $response = $this->httpClient->get('forecast/?lat='.$latitude.'&lon='.$longitude.'&appid='. config('weather.api_token'));

        $weatherData = json_decode($response->getBody()->getContents(), true);

        Log::debug('Hourly forecast response', $weatherData);
        return $this->responseMapper->mapHourlyForecast(json_decode($response->getBody()->getContents(), true));


    }
}
