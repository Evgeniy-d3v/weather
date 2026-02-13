<?php

namespace App\Location\Infrastructure\Adapters;

use App\Location\Application\DTO\WeatherDailyDto;
use App\Location\Application\DTO\WeatherHourlyDtoCollection;
use App\Location\Application\WeatherApiExecutorInterface;
use App\Location\Domain\Exceptions\InvalidWeatherDataException;
use GuzzleHttp\Client;

class WeatherApiExecutor implements WeatherApiExecutorInterface
{
    public function __construct(
        public Client $httpClient,
        public WeatherResponseMapper $responseMapper,
    ) {}

    public function getDailyWeather(string $latitude, string $longitude): WeatherDailyDto
    {
        $response = $this->httpClient->get('forecast?latitude='.$latitude.'&longitude='.$longitude.'&daily=temperature_2m_min,temperature_2m_max,apparent_temperature_min,apparent_temperature_max,precipitation_sum,wind_speed_10m_max,weathercode&forecast_days=1&timezone=auto');
        $weatherData = json_decode($response->getBody()->getContents(), true);
        if (! is_array($weatherData)) {
            throw new InvalidWeatherDataException('Пришел невалидный ответ');
        }

        return $this->responseMapper->mapDailyForecast($weatherData);
    }

    public function getHourlyWeather(string $latitude, string $longitude): WeatherHourlyDtoCollection
    {
        $response = $this->httpClient->get('forecast?latitude='.$latitude.'&longitude='.$longitude.'&hourly=temperature_2m,apparent_temperature,precipitation,wind_speed_10m,weathercode&forecast_days=1&timezone=auto');
        $weatherData = json_decode($response->getBody()->getContents(), true);
        if (! is_array($weatherData)) {
            throw new InvalidWeatherDataException('Пришел невалидный ответ');
        }

        return $this->responseMapper->mapHourlyForecast($weatherData);
    }
}
