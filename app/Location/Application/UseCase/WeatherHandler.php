<?php

namespace App\Location\Application\UseCase;

use App\Location\Application\Repositories\CityRepositoryInterface;
use App\Location\Application\Repositories\WeatherForecastRepositoryInterface;
use App\Location\Application\WeatherApiExecutorInterface;

class WeatherHandler
{
    public function __construct(
        public CityRepositoryInterface $cityRepository,
        public WeatherApiExecutorInterface $weatherApiExecutor,
        public WeatherForecastRepositoryInterface $weatherForecastRepository,
    )
    {}
    public function getAndSetDailyForecast(int $cityId): void
    {
        $city = $this->cityRepository->getCityById($cityId);
        $forecast = $this->weatherApiExecutor->getDailyWeather(
            $city->getLatitude(),
            $city->getLongitude()
        );
        $this->weatherForecastRepository->setDailyForecast($cityId, $forecast);
    }

    public function getAndSetHourlyForecast(int $cityId): void
    {
        $city = $this->cityRepository->getCityById($cityId);
        $forecast = $this->weatherApiExecutor->getHourlyWeather(
            $city->getLatitude(),
            $city->getLongitude()
        );
        $this->weatherForecastRepository->setHourlyForecast($cityId, $forecast);
    }
}
