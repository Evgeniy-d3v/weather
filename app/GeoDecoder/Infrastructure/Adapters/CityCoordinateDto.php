<?php

namespace App\GeoDecoder\Infrastructure\Adapters;

final class CityCoordinateDto
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
    ) {
    }
}
