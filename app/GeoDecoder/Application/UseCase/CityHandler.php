<?php

namespace App\GeoDecoder\Application\UseCase;

use App\GeoDecoder\Application\GeoDecoderApiExecutorInterface;
use App\GeoDecoder\Application\Repositories\CityRepositoryInterface;
use App\GeoDecoder\Infrastructure\Adapters\CityInfoDto;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\Weather\Application\WeatherApiExecutorInterface;

class CityHandler
{
    public function __construct(
        public GeoDecoderApiExecutorInterface $geoDecoderApiExecutor,
        public WeatherApiExecutorInterface $weatherApiExecutor,
        public CityRepositoryInterface $cityRepository,
        public ClientRepositoryInterface $clientRepository,
    )
    {
    }

    public function createCity(string $cityName, int $clientId): void
    {
        $cityInfoDto = $this->getCityInfo($cityName);

        $cityId = $this->cityRepository->createCity($cityName, $clientId, $cityInfoDto);
        $this->clientRepository->addCityToClient($clientId, $cityId);
    }

    private function getCityInfo(string $cityName): CityInfoDto
    {
        $coordinateDto =  $this->geoDecoderApiExecutor->getCoordinate($cityName);
        $timeZone =  $this->weatherApiExecutor->getCurrentWeather($coordinateDto->latitude, $coordinateDto->longitude)->timeZone;
        return new CityInfoDto(
            cityName: $cityName,
            timeZone: $timeZone,
            latitude: $coordinateDto->latitude,
            longitude: $coordinateDto->longitude,
        );
    }
}
