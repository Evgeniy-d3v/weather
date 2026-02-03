<?php

namespace App\Location\Infrastructure\Persistence\Repositories;

use App\Location\Application\DTO\CityInfoDto;
use App\Location\Application\Repositories\CityRepositoryInterface;
use App\Location\Domain\Entities\CityEntity;
use App\Location\Infrastructure\Persistence\Model\City;
use Illuminate\Database\Eloquent\Builder;

class CityRepository implements CityRepositoryInterface
{
    public function createCity(string $cityName, int $clientId, CityInfoDto $dto): int
    {
        $entity = new CityEntity(
            id: 0,
            cityName: $cityName,
            timeZone: $dto->timeZone,
            latitude: $dto->latitude,
            longitude: $dto->longitude,
        );

        $model = $this->toModel($entity);
        $model->save();

        return $model->id;
    }

    public function getAllCitiesWithLastForecast(): Builder
    {
        return City::query()->with('latestWeatherForecast');
    }

    public function getCityById(int $cityId): CityEntity
    {
        $model = City::where('id', $cityId)->firstOrFail();

        return $this->toDomainEntity($model);
    }

    private function toDomainEntity(City $model): CityEntity
    {
        return new CityEntity(
            id: $model->id,
            cityName: $model->city_name,
            timeZone: $model->time_zone,
            latitude: $model->latitude,
            longitude: $model->longitude,
        );
    }

    private function toModel(CityEntity $entity): City
    {
        $model = City::findOrNew($entity->getId());
        $model->city_name = $entity->getCityName();
        $model->time_zone = $entity->getTimeZone();
        $model->latitude = $entity->getLatitude();
        $model->longitude = $entity->getLongitude();

        return $model;
    }

    public function getCityByName(string $cityName): ?CityEntity
    {
        $model = City::where('city_name', $cityName)->first();
        if ($model === null) {
            return null;
        }

        return $this->toDomainEntity($model);
    }
}
