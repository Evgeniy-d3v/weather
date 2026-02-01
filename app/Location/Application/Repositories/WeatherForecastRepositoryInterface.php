<?php

namespace App\Location\Application\Repositories;


use App\Location\Application\DTO\WeatherDailyDto;
use App\Location\Application\DTO\WeatherHourlyDtoCollection;

interface WeatherForecastRepositoryInterface
{

    public function setDailyForecast(int $cityId, WeatherDailyDto $dailyDto): void;

    public function setHourlyForecast(int $cityId, WeatherHourlyDtoCollection $hourlyDtoCollection): void;

}
