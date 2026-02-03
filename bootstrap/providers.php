<?php

return [
    \laravel\Providers\AppServiceProvider::class,
    \App\TelegramBot\Infrastructure\Providers\TelegramBotServiceProvider::class,
    \App\Location\Infrastructure\Providers\LocationServiceProvider::class,
    \App\Location\Infrastructure\Providers\LocationEventServiceProvider::class,
    \App\Shared\Infrastructure\Providers\SharedEventServiceProvider::class,
];
