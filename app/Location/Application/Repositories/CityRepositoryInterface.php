<?php

namespace App\Location\Application\Repositories;

use App\Location\Application\DTO\CityInfoDto;
use App\Location\Domain\Entities\CityEntity;
use Illuminate\Support\Collection;

interface CityRepositoryInterface
{
    public function createCity(string $cityName, int $clientId, CityInfoDto $dto): int;

    // TODO: Вернуть коллекцию ентити
    public function getAllCitiesWithLastForecast(): Collection;

    public function getCityById(int $cityId): CityEntity;
}
