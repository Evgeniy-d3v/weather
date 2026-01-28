<?php

namespace App\GeoDecoder\Infrastructure\Adapters;

use App\GeoDecoder\Application\GeoDecoderApiExecutorInterface;
use GuzzleHttp\Client;
class GeoDecoderApiAdapter implements GeoDecoderApiExecutorInterface
{
    public function __construct(
        public Client $httpClient,
        public GeoDecoderResponseMapper $responseMapper,
    )
    {}
    public function getCoordinate(string $cityName): CityCoordinateDto
    {
        $response = $this->httpClient->get('search?q=' . $cityName . '&api_key='. config('geo_decoder.api_token'));

        $geoDecodeData = json_decode($response->getBody()->getContents(), true);

        return $this->responseMapper->mapGeoDecodeData($geoDecodeData);
    }
}
