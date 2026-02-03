<?php

namespace App\Location\Application\UseCase;

use App\Location\Application\DTO\CityInfoDto;
use App\Location\Application\GeoDecoderApiExecutorInterface;
use App\Location\Application\Repositories\CityRepositoryInterface;
use App\Location\Application\WeatherApiExecutorInterface;
use App\Location\Domain\Entities\CityEntity;
use App\Location\Domain\Exceptions\InvalidCityNameException;
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
        $this->validateCityName($cityName);
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

    /**
     * @throws InvalidCityNameException
     */
    private function validateCityName(string $cityName): string
    {
        $cityName = trim($cityName);
        $cityName = preg_replace('/\s+/u', ' ', $cityName);

        if ($cityName === '') {
            throw new InvalidCityNameException('Ты отправил что то пустое');
        }

        if (mb_strlen($cityName) < 2 || mb_strlen($cityName) > 80) {
            throw new InvalidCityNameException('Что то не так с количесвм символов');
        }

        if (! preg_match('/^[\p{L}][\p{L}\s\-\'.()]*$/u', $cityName)) {
            throw new InvalidCityNameException('Название города содержит недоступные символы');
        }

        return $cityName;
    }
}
