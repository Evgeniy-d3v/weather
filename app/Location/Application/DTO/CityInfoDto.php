<?php

namespace App\Location\Application\DTO;

final class CityInfoDto
{
    public function __construct(
        public readonly string $cityName,
        public readonly string $timeZone,
        public readonly float $latitude,
        public readonly float $longitude,
    ) {}
}
