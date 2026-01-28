<?php

namespace App\GeoDecoder\Application\Repositories;

use App\GeoDecoder\Infrastructure\Adapters\CityInfoDto;

interface CityRepositoryInterface
{
    public function createCity(string $cityName, int $clientId, CityInfoDto $dto): int;
}
