<?php

namespace App\GeoDecoder\Application;

use App\GeoDecoder\Infrastructure\Adapters\CityCoordinateDto;

interface GeoDecoderApiExecutorInterface
{
    public function getCoordinate(string $cityName): CityCoordinateDto;
}
