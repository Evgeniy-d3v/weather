<?php

namespace App\Weather\Infrastructure\Adapters;

use Illuminate\Support\Facades\Log;
use PhpWeather\Common\WeatherQuery;
use PhpWeather\Provider\OpenMeteo\OpenMeteo;

class WeatherAdapter
{
    public function __construct(
        public OpenMeteo $openMeteo,
        public WeatherResponseMapper $weatherResponseMapper,
    )
    {}

//    public function getCurrentWeather(float $latitude, float $longitude): WeatherDto
//    public function getCurrentWeather(): WeatherDto
//    {
//        $query = WeatherQuery::create(
//            latitude: 59.3293,
//            longitude: 18.0686,
//            hourly: [
//                'cloudcover',
//                'precipitation',
//                'snowfall',
//            ]
//        );
//
//
//        $response = $this->openMeteo->getCurrentWeather(
//            WeatherQuery::create(59.3293, 18.0686)
//        );
//
//        $a =  $this->weatherResponseMapper->mapCurrentResponse($response);
//        Log::debug('WeatherAdapter getCurrentWeather: ' . json_encode($a));
//        return $a;
//    }

//    public function getWeaklyWeather(float $latitude, float $longitude, ): WeatherDtoCollection
//    {
//        $query = WeatherQuery::create(
//            latitude: $latitude,
//            longitude: $longitude,
//        );
//        $query->
//        $response = $this->openMeteo->getForecast(
//
//        );
//    }

    private function createWeatherQuery(float $latitude, float $longitude): WeatherQuery
    {
        return WeatherQuery::create($latitude, $longitude, null);
    }
}
