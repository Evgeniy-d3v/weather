<?php

namespace App\GeoDecoder\Infrastructure\Persistence\Repositories;

use App\GeoDecoder\Application\Repositories\CityRepositoryInterface;
use App\GeoDecoder\Infrastructure\Persistence\Model\City;
use App\GeoDecoder\Infrastructure\Adapters\CityInfoDto;

class CityRepository implements CityRepositoryInterface
{

    public function createCity(string $cityName, int $clientId, CityInfoDto $dto): int
    {
        $city = new City();
        $city->city_name = $cityName;
        $city->time_zone = $dto->timeZone;
        $city->latitude = $dto->latitude;
        $city->longitude = $dto->longitude;
        $city->save();

        return $city->id;
    }
}
