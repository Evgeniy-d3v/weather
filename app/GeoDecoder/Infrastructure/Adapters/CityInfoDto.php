<?php

namespace App\GeoDecoder\Infrastructure\Adapters;

final class CityInfoDto
{
    public function __construct(
        public readonly string $cityName,
        public readonly string $timeZone,
        public readonly float $latitude,
        public readonly float $longitude,
    ) {
    }
}
