<?php

namespace App\Location\Application\DTO;

final readonly class WeatherDailyDto
{
    public function __construct(
        public string $timeZone,
        public string $date,
        public float  $temperatureMin,
        public float  $temperatureMax,
        public float  $apparentTemperatureMin,
        public float  $apparentTemperatureMax,
        public float  $precipitationSum,
        public float  $windSpeedMax,
        public string $weatherCondition,
    )
    {}

    public function toStorageArray(): array
    {
        return [
            'temperature_min' => $this->temperatureMin,
            'temperature_max' => $this->temperatureMax,
            'apparent_temperature_min' => $this->apparentTemperatureMin,
            'apparent_temperature_max' => $this->apparentTemperatureMax,
            'precipitation_sum' => $this->precipitationSum,
            'wind_speed_max' => $this->windSpeedMax,
            'weather_condition' => $this->weatherCondition,
        ];
    }
}

