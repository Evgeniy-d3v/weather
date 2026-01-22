<?php

namespace App\Weather\Infrastructure\Adapters;

use Illuminate\Support\Facades\Log;

class WeatherResponseMapper
{

    public function mapCurrentWeather(array $weatherData): WeatherDto
    {
      $a = $this->mapWeatherItem($weatherData, '1h');
        Log::debug('Mapped current weather'. json_encode($a));
        return $this->mapWeatherItem($weatherData, '1h');
    }

    public function mapHourlyForecast(array $weatherData): WeatherDtoCollection
    {
        $items = [];
        $list = $weatherData['list'] ?? [];

        foreach ($list as $item) {
            $items[] = $this->mapWeatherItem($item, '3h');
        }

        return new WeatherDtoCollection($items);
    }

    /**
     * Преобразует один элемент данных погоды в WeatherDto
     *
     * @param array $weatherItem Элемент данных погоды
     * @param string $precipitationPeriod Период осадков ('1h' или '3h')
     * @return WeatherDto
     */
    private function mapWeatherItem(array $weatherItem, string $precipitationPeriod): WeatherDto
    {
        $main = $weatherItem['main'] ?? [];
        $weatherList = $weatherItem['weather'] ?? [];
        $wind = $weatherItem['wind'] ?? [];
        $clouds = $weatherItem['clouds'] ?? [];

        $time = null;
        if (isset($weatherItem['dt_txt'])) {
            $time = $weatherItem['dt_txt'];
        } elseif (isset($weatherItem['dt'])) {
            $dt = (int) $weatherItem['dt'];
            $dateTime = new \DateTimeImmutable('@' . $dt);
            $dateTime = $dateTime->setTimezone(new \DateTimeZone('UTC'));
            $time = $dateTime->format('Y-m-d H:i:s');
        }

        // температуры (К -> °C, целое число)
        $temperature = isset($main['temp']) ? (int) round(((float) $main['temp']) - 273.15) : null;
        $temperatureFeelsLike = isset($main['feels_like']) ? (int) round(((float) $main['feels_like']) - 273.15) : null;

        // ветер
        $windDirectionDeg = isset($wind['deg']) ? (float) $wind['deg'] : null;
        $windSpeed = isset($wind['speed']) ? (int) round((float)$wind['speed']) : null;

        // облачность
        $cloudCoverValue = isset($clouds['all']) ? (float) $clouds['all'] : null;

        // погода / описание
        $firstWeather = $weatherList[0] ?? [];
        $weatherCondition = isset($firstWeather['main']) ? (string) $firstWeather['main'] : '';
        $weatherDescription = isset($firstWeather['description']) ? (string) $firstWeather['description'] : '';

        // количество осадков (rain["1h"]/["3h"] или snow["1h"]/["3h"])
        $precipitationAmount = null;
        if (isset($weatherItem['rain'][$precipitationPeriod])) {
            $precipitationAmount = (float) $weatherItem['rain'][$precipitationPeriod];
        } elseif (isset($weatherItem['snow'][$precipitationPeriod])) {
            $precipitationAmount = (float) $weatherItem['snow'][$precipitationPeriod];
        }

        return new WeatherDto(
            time: $time,
            temperature: $temperature,
            temperatureFeelsLike: $temperatureFeelsLike,
            windDirection: $this->parseWindDirection($windDirectionDeg),
            windSpeed: $windSpeed,
            cloudCover: $this->parseCloudCover($cloudCoverValue),
            weatherCondition: $weatherCondition,
            weatherDescription: $weatherDescription,
            precipitation: $this->precipitation($temperature, $precipitationAmount),
        );
    }
    private function parseWindDirection(?float $windDirection): string
    {
        if ($windDirection === null) {
            return 'нет данных';
        }

        $directions = [
            'С', 'ССВ', 'СВ',  'ВСВ',
            'В', 'ВЮВ', 'ЮВ',  'ЮЮВ',
            'Ю', 'ЮЮЗ', 'ЮЗ',  'ЗЮЗ',
            'З', 'ЗСЗ', 'СЗ',  'ССЗ',
        ];

        $index = (int) ( ($windDirection + 11.25) / 22.5 ) % 16;

        return $directions[$index];
    }

    private function parseCloudCover(?float $cloudCover): string
    {
        if ($cloudCover === null) {
            return 'нет данных';
        }

        return match (true) {
            $cloudCover < 10  => 'ясно',
            $cloudCover < 30  => 'малооблачно',
            $cloudCover < 60  => 'переменная облачность',
            $cloudCover < 90  => 'облачно',
            default           => 'пасмурно',
        };
    }

    private function precipitation(?int $temperature, ?float $precipitation): string
    {
        if ($precipitation === null) {
            return 'нет данных';
        }

        if ($temperature === null) {
            return $precipitation . ' мм';
        }

        if ($temperature >= 0) {
            return $this->parseRainPrecipitation($precipitation);
        }

        return $this->parseSnowPrecipitation($precipitation);
    }

    private function parseRainPrecipitation(float $precipitation): string
    {
        return match (true) {
            $precipitation == 0      => 'без осадков',
            $precipitation < 1       => 'лёгкий дождь',
            $precipitation < 2.5     => 'дождь',
            default                  => 'сильный дождь',
        };
    }

    private function parseSnowPrecipitation(float $precipitation): string
    {
        return match (true) {
            $precipitation == 0      => 'без осадков',
            $precipitation < 1       => 'лёгкий снег',
            $precipitation < 2.5     => 'снегопад',
            default                  => 'сильный снегопад',
        };
    }


}
