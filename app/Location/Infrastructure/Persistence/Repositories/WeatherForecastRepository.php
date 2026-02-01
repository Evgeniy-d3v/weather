<?php

namespace App\Location\Infrastructure\Persistence\Repositories;

use App\Location\Application\DTO\DailyForecastDto;
use App\Location\Application\DTO\HourlyForecastDto;
use App\Location\Application\DTO\WeatherDailyDto;
use App\Location\Application\DTO\WeatherHourlyDtoCollection;
use App\Location\Application\Repositories\WeatherForecastRepositoryInterface;
use App\Location\Infrastructure\Persistence\Model\WeatherForecast;

class WeatherForecastRepository implements WeatherForecastRepositoryInterface
{


    public function setDailyForecast(int $cityId, WeatherDailyDto $dailyDto): void
    {
       $forecast = WeatherForecast::firstOrNew([
            'city_id' => $cityId,
            'day' => $dailyDto->date,
        ]);
        $forecast->daily_forecast = $dailyDto->toStorageArray();
        $forecast->save();
    }

    public function setHourlyForecast(int $cityId, WeatherHourlyDtoCollection $hourlyDtoCollection): void
    {
        $forecast = WeatherForecast::firstOrNew([
            'city_id' => $cityId,
            'day' => $hourlyDtoCollection->date(),
        ]);
        $forecast->hourly_forecast = $hourlyDtoCollection->toStorageArray();
        $forecast->save();
    }

}
