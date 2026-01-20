<?php

namespace App\Weather\Infrastructure\Adapters;

use PhpWeather\Common\WeatherQuery;
use PhpWeather\Provider\OpenMeteo\OpenMeteo;

class WeatherAdapter
{
    public function __construct(
        public OpenMeteo $openMeteo,
        public WeatherResponseMapper $weatherResponseMapper,
    )
    {}

    public function getCurrentWeather(float $latitude, float $longitude): WeatherDto
    {
        $response = $this->openMeteo->getCurrentWeather(
            WeatherQuery::create($latitude, $longitude)
        );
        return $this->weatherResponseMapper->mapCurrentResponse($response);
    }

//    public function getWeaklyWeather(float $latitude, float $longitude, ): WeatherDtoCollection
//    {
//        $query = WeatherQuery::create(
//            latitude: $latitude,
//            longitude: $longitude,
//            start: $start,
//            end: $end,
//        );
//
//        $response = $this->openMeteo->getForecast(
//
//        );
//    }

    private function createWeatherQuery(float $latitude, float $longitude): WeatherQuery
    {
        return WeatherQuery::create($latitude, $longitude, null);
    }
}
