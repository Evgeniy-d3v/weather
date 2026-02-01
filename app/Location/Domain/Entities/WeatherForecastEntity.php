<?php

namespace App\Location\Domain\Entities;

final class WeatherForecastEntity
{
    public function __construct(
        private int $id,
        private int $cityId,
        private string $day, // Y-m-d format
        private ?array $dailyForecast,
        private ?array $hourlyForecast,
    ) {}

    // Геттеры
    public function getId(): int
    {
        return $this->id;
    }

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function getDay(): string
    {
        return $this->day;
    }

    public function getDailyForecast(): ?array
    {
        return $this->dailyForecast;
    }

    public function getHourlyForecast(): ?array
    {
        return $this->hourlyForecast;
    }

    // Бизнес-логика
    public function updateDailyForecast(array $dailyForecast): void
    {
        $this->dailyForecast = $dailyForecast;
    }

    public function updateHourlyForecast(array $hourlyForecast): void
    {
        $this->hourlyForecast = $hourlyForecast;
    }

    public function hasDailyForecast(): bool
    {
        return $this->dailyForecast !== null && ! empty($this->dailyForecast);
    }

    public function hasHourlyForecast(): bool
    {
        return $this->hourlyForecast !== null && ! empty($this->hourlyForecast);
    }
}
