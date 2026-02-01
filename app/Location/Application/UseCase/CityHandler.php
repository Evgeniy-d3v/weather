<?php

namespace App\Location\Application\UseCase;

use App\Location\Application\DTO\CityInfoDto;
use App\Location\Application\GeoDecoderApiExecutorInterface;
use App\Location\Application\Repositories\CityRepositoryInterface;
use App\Location\Application\WeatherApiExecutorInterface;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;

class CityHandler
{
    public function __construct(
        public GeoDecoderApiExecutorInterface $geoDecoderApiExecutor,
        public WeatherApiExecutorInterface $weatherApiExecutor,
        public CityRepositoryInterface $cityRepository,
        public ClientRepositoryInterface $clientRepository,
    ) {}

    public function createCity(string $cityName, int $clientId): void
    {
        $cityInfoDto = $this->getCityInfo($cityName);

        $cityId = $this->cityRepository->createCity($cityName, $clientId, $cityInfoDto);
        $this->clientRepository->addCityToClient($clientId, $cityId);
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
}
