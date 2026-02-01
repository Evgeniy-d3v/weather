<?php

namespace App\Location\Infrastructure\Adapters;

use App\Location\Application\DTO\WeatherDailyDto;
use App\Location\Application\DTO\WeatherHourlyDto;
use App\Location\Application\DTO\WeatherHourlyDtoCollection;

class WeatherResponseMapper
{
    /**
     * @param  array<int, string>  $weatherConditionCodeMap
     */
    public function __construct(
        private readonly array $weatherConditionCodeMap
    ) {}

    public function mapDailyForecast(array $weatherData): WeatherDailyDto
    {
        $dailyParameters = $weatherData['daily'];

        return new WeatherDailyDto(
            timeZone: $this->normalizeTimeZone($weatherData['timezone_abbreviation']),
            date: $dailyParameters['time'][0],
            temperatureMin: $dailyParameters['temperature_2m_min'][0],
            temperatureMax: $dailyParameters['temperature_2m_max'][0],
            apparentTemperatureMin: $dailyParameters['apparent_temperature_min'][0],
            apparentTemperatureMax: $dailyParameters['apparent_temperature_max'][0],
            precipitationSum: $dailyParameters['precipitation_sum'][0],
            windSpeedMax: $dailyParameters['wind_speed_10m_max'][0],
            weatherCondition: $this->weatherConditionCodeToText($dailyParameters['weathercode'][0]),
        );
    }

    public function mapHourlyForecast(array $weatherData): WeatherHourlyDtoCollection
    {
        $hourly = $weatherData['hourly'];

        $time = $hourly['time'];
        $temperature = $hourly['temperature_2m'];
        $weatherApparent = $hourly['apparent_temperature'];
        $weatherConditionCode = $hourly['weathercode'];
        $precipitation = $hourly['precipitation'];

        $date = explode('T', $time[0])[0];

        $weatherHourlyDtoCollection = new WeatherHourlyDtoCollection($date, []);

        for ($i = 0; $i < count($time); $i++) {
            $weatherHourlyDto = new WeatherHourlyDto(
                time: explode('T', $time[$i])[1],
                temperature: $temperature[$i],
                apparentTemperature: $weatherApparent[$i],
                precipitation: $precipitation[$i],
                weatherCondition: $this->weatherConditionCodeToText($weatherConditionCode[$i]),
            );
            $weatherHourlyDtoCollection->add($weatherHourlyDto);
        }

        return $weatherHourlyDtoCollection;
    }

    private function weatherConditionCodeToText(int $code): string
    {
        return $this->weatherConditionCodeMap[$code] ?? 'неизвестно';
    }

    private function normalizeTimeZone(string $timeZone): string
    {
        $pattern = '/[+-]\d{1,2}/';
        preg_match($pattern, $timeZone, $matches);

        return $matches[0] ?? $timeZone; // Fallback на исходное значение, если не найдено
    }
}
