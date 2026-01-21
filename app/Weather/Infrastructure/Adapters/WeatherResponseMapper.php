<?php

namespace App\Weather\Infrastructure\Adapters;
use PhpWeather\Weather;

class WeatherResponseMapper
{

    public function mapCurrentResponse(Weather $weather): WeatherDto
    {
        $temperature = $weather->getTemperature();
        return new WeatherDto(
            time: $weather->getUtcDateTime(),
            temperature: $temperature,
            windDirection: $this->parseWindDirection($weather->getWindDirection()),
            windSpeed: $weather->getWindSpeed(),
            cloudCover: $this->parseCloudCover($weather->getCloudCover()),
            precipitation: $this->precipitation($temperature, $weather->getPrecipitation()),
        );
    }
    private function parseWindDirection(?float $windDirection): string
    {
        if ($windDirection === null) {
            return 'Нет данных';
        }

        $directions = [
            'С', 'ССВ', 'СВ',  'ВСВ',
            'В', 'ВЮВ', 'ЮВ',  'ЮЮВ',
            'Ю', 'ЮЮЗ', 'ЮЗ',  'ЗЮЗ',
            'З', 'ЗСЗ', 'СЗ',  'ССЗ',
        ];

        $index = (int)(($windDirection + 11.25) / 22.5) % 16;

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

    private function precipitation(?float $temperature, ?float $precipitation ): string
    {
        if ($temperature === null) {
            return $precipitation !== null
                ? $precipitation . ' мм рт. ст.'
                : 'нет данных';
        }
        if ($temperature >= 0) {
            return $this->parseRainPrecipitation($precipitation);
        }
        return $this->parseSnowPrecipitation($precipitation);
    }
    private function parseRainPrecipitation(float $precipitation): string
    {

        return match (true) {
            $precipitation == 0  => 'ясно',
            $precipitation < 1  => 'лёгкий дождь',
            $precipitation < 2.5  => 'дождь',
            default  => 'Сильный дождь',
        };
    }
    private function parseSnowPrecipitation(?float $precipitation): string
    {
        if ($precipitation === null) {
            return 'нет данных';
        }
        return match (true) {
            $precipitation == 0  => 'ясно',
            $precipitation < 1  => 'лёгкий сег',
            $precipitation < 2.5  => 'снегопад',
            default  => 'Сильный снегопад',
        };
    }
}
