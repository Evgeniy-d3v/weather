<?php

namespace App\Location\Infrastructure\Persistence\Repositories;

use App\Location\Application\DTO\WeatherDailyDto;
use App\Location\Application\DTO\WeatherHourlyDtoCollection;
use App\Location\Application\Repositories\WeatherForecastRepositoryInterface;
use App\Location\Domain\Entities\WeatherForecastEntity;
use App\Location\Infrastructure\Persistence\Model\City;
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

    public function getTodayForecast(int $cityId): WeatherForecastEntity
    {
        $forecast = City::where('id', $cityId)->first()->todayForecast;

        return $this->toDomainEntity($cityId, $forecast);
    }

    private function toDomainEntity(int $cityId, WeatherForecast $forecast): WeatherForecastEntity
    {
        return new WeatherForecastEntity(
            $forecast->id,
            $cityId,
            $forecast->day,
            $forecast->daily_forecast,
            $forecast->hourly_forecast
        );
    }
}
