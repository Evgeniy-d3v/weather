<?php

namespace App\Weather\Infrastructure\Adapters;

use DateTimeInterface;

final readonly class WeatherDto
{
    public function __construct(
        public DateTimeInterface|null $time,
        public float|null $temperature,
        public string $windDirection,
        public float|null $windSpeed,
        public string $cloudCover,
        public string $precipitation,
    )
    {}
}
