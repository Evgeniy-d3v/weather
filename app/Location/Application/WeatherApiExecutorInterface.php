<?php

namespace App\Location\Application;

use App\Location\Application\DTO\WeatherDailyDto;
use App\Location\Application\DTO\WeatherHourlyDtoCollection;

interface WeatherApiExecutorInterface
{
    public function getDailyWeather(string $latitude, string $longitude): WeatherDailyDto;

    public function getHourlyWeather(string $latitude, string $longitude): WeatherHourlyDtoCollection;
}
