<?php

namespace App\Location\Application\DTO;

final class CityCoordinateDto
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
    ) {}
}
