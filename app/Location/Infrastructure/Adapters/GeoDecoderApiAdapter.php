<?php

namespace App\Location\Infrastructure\Adapters;

use App\Location\Application\DTO\CityCoordinateDto;
use App\Location\Application\GeoDecoderApiExecutorInterface;
use GuzzleHttp\Client;

class GeoDecoderApiAdapter implements GeoDecoderApiExecutorInterface
{
    public function __construct(
        public Client $httpClient,
        public GeoDecoderResponseMapper $responseMapper,
    ) {}

    public function getCoordinate(string $cityName): CityCoordinateDto
    {
        $response = $this->httpClient->get('search?q='.$cityName.'&api_key='.config('location.api_token'));

        $geoDecodeData = json_decode($response->getBody()->getContents(), true);

        return $this->responseMapper->mapGeoDecodeData($geoDecodeData);
    }
}
