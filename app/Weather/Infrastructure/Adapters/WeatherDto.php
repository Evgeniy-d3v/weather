<?php

namespace App\Weather\Infrastructure\Adapters;


final readonly class WeatherDto
{
    public function __construct(
        public string|null $time, //дата замера
        public int|null $temperature,//температура воздуха
        public int|null $temperatureFeelsLike, //ощущаемая температура
        public string $windDirection, //направление ветра (С, СВ, В, ЮВ, Ю, ЮЗ, З, СЗ)
        public int|null $windSpeed, //скорость ветра м/с
        public string $cloudCover,//облачность
        public string $weatherCondition, //Дождь, Снег
        public string $weatherDescription, //небольшой дождь, легкий снег
        public string $precipitation, //осадки
        public ?int $timeZone, //часовой пояс
    )
    {}
}
