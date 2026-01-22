<?php

namespace App\Weather\Application;

use App\Weather\Infrastructure\Adapters\WeatherDto;
use App\Weather\Infrastructure\Adapters\WeatherDtoCollection;

interface WeatherApiExecutorInterface
{
    public function getCurrentWeather(string $latitude, string $longitude): WeatherDto;

    public function getFiveDayThreeHourForecast(string $latitude, string $longitude, int $daysCount): WeatherDtoCollection;


}
