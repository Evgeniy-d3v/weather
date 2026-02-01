<?php

namespace App\Location\Application;

use App\Location\Application\DTO\CityCoordinateDto;

interface GeoDecoderApiExecutorInterface
{
    public function getCoordinate(string $cityName): CityCoordinateDto;
}
