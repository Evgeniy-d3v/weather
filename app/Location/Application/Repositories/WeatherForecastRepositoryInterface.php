<?php

namespace App\Location\Application\Repositories;

use App\Location\Application\DTO\WeatherDailyDto;
use App\Location\Application\DTO\WeatherHourlyDtoCollection;
use App\Location\Domain\Entities\WeatherForecastEntity;

interface WeatherForecastRepositoryInterface
{
    public function setDailyForecast(int $cityId, WeatherDailyDto $dailyDto): void;

    public function setHourlyForecast(int $cityId, WeatherHourlyDtoCollection $hourlyDtoCollection): void;

    public function getTodayForecast(int $cityId): WeatherForecastEntity;
}
