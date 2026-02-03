<?php

namespace App\Location\Application\Repositories;

use App\Location\Application\DTO\CityInfoDto;
use App\Location\Domain\Entities\CityEntity;
use Illuminate\Database\Eloquent\Builder;

interface CityRepositoryInterface
{
    public function createCity(string $cityName, int $clientId, CityInfoDto $dto): int;

    public function getAllCitiesWithLastForecast(): Builder;

    public function getCityById(int $cityId): CityEntity;

    public function getCityByName(string $cityName): ?CityEntity;
}
