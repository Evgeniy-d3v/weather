<?php


return [
    \laravel\Providers\AppServiceProvider::class,
    \App\TelegramBot\Infrastructure\Providers\TelegramBotServiceProvider::class,
    \App\Weather\Infrastructure\Providers\OpenWeatherServiceProvider::class,
    \App\GeoDecoder\Infrastructure\Providers\GeoDecodingServiceProvider::class,
];
