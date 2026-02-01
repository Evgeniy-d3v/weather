<?php

namespace App\Location\Application\DTO;

final readonly class WeatherHourlyDto
{
    public function __construct(
        public string $time,
        public float  $temperature,
        public float  $apparentTemperature,
        public string $precipitation,
        public string $weatherCondition,
    )
    {}
    public function toStorageArray(): array
    {
        return [
            'temperature' => $this->temperature,
            'apparent_temperature' => $this->apparentTemperature,
            'precipitation' => $this->precipitation,
            'weather_condition' => $this->weatherCondition,
        ];
    }
}
