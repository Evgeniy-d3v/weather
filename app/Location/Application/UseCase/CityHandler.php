<?php

namespace App\Location\Application\UseCase;

use App\Location\Application\DTO\CityInfoDto;
use App\Location\Application\GeoDecoderApiExecutorInterface;
use App\Location\Application\Repositories\CityRepositoryInterface;
use App\Location\Application\WeatherApiExecutorInterface;
use App\Location\Domain\Entities\CityEntity;
use App\Shared\Infrastructure\Events\CityAssignedToClientEvent;

class CityHandler
{
    public function __construct(
        public GeoDecoderApiExecutorInterface $geoDecoderApiExecutor,
        public WeatherApiExecutorInterface $weatherApiExecutor,
        public CityRepositoryInterface $cityRepository,
    ) {}

    public function createCity(string $cityName, int $clientId): void
    {
        $city = $this->findCity($cityName);
        if ($city !== null) {
            $cityId = $city->getId();
        } else {
            $cityInfoDto = $this->getCityInfo($cityName);
            $cityId = $this->cityRepository->createCity($cityName, $clientId, $cityInfoDto);
        }

        event(new CityAssignedToClientEvent($cityId, $clientId));
    }

    private function getCityInfo(string $cityName): CityInfoDto
    {
        $coordinateDto = $this->geoDecoderApiExecutor->getCoordinate($cityName);
        $timeZone = $this->weatherApiExecutor->getDailyWeather($coordinateDto->latitude, $coordinateDto->longitude)->timeZone;

        return new CityInfoDto(
            cityName: $cityName,
            timeZone: $timeZone,
            latitude: $coordinateDto->latitude,
            longitude: $coordinateDto->longitude,
        );
    }

    private function findCity(string $cityName): ?CityEntity
    {
        return $this->cityRepository->getCityByName($cityName);
    }
}
