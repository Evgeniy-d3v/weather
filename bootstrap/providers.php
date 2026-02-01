<?php


return [
    \laravel\Providers\AppServiceProvider::class,
    \App\TelegramBot\Infrastructure\Providers\TelegramBotServiceProvider::class,
    \App\Location\Infrastructure\Providers\LocationServiceProvider::class,
];
