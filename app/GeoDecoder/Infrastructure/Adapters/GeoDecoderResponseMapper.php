<?php

namespace App\GeoDecoder\Infrastructure\Adapters;

class GeoDecoderResponseMapper
{
    public function mapGeoDecodeData(array $geoDecodeData): CityCoordinateDto
    {

        return new CityCoordinateDto(
            latitude: (float) ($geoDecodeData[0]['lat'] ?? null),
            longitude: (float) ($geoDecodeData[0]['lon'] ?? null),
        );
    }
}
