<?php

namespace App\Location\Infrastructure\Adapters;

use App\Location\Application\DTO\CityCoordinateDto;
use App\Location\Domain\Exceptions\CityNotFoundException;

class GeoDecoderResponseMapper
{
    /**
     * @throws CityNotFoundException
     */
    public function mapGeoDecodeData(array $geoDecodeData): CityCoordinateDto
    {
        if (empty($geoDecodeData) || ! isset($geoDecodeData[0])) {
            throw new CityNotFoundException('Город не был найден');
        }

        $firstResult = $geoDecodeData[0];
        $latRaw = $firstResult['lat'] ?? null;
        $lonRaw = $firstResult['lon'] ?? null;

        if ($latRaw === null || $lonRaw === null) {
            throw new CityNotFoundException('Город не был найден');
        }

        $lat = (float) $latRaw;
        $lon = (float) $lonRaw;

        if ($lat === 0.0 && $lon === 0.0) {
            throw new CityNotFoundException('Город не был найден');
        }
        if ($lat < -90.0 || $lat > 90.0 || $lon < -180.0 || $lon > 180.0) {
            throw new CityNotFoundException('Что то пошло не так');
        }

        return new CityCoordinateDto(
            latitude: $lat,
            longitude: $lon,
        );
    }
}
