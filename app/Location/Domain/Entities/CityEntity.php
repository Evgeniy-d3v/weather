<?php

namespace App\Location\Domain\Entities;


final class CityEntity
{
    public function __construct(
        private int $id,
        private string $cityName,
        private string $timeZone,
        private float $latitude,
        private float $longitude,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCityName(): string
    {
        return $this->cityName;
    }

    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

}
