<?php

namespace App\Shared\Providers;

use App\Shared\Events\CityAssignedToClientEvent;
use App\TelegramBot\Infrastructure\Persistence\Listener\CityAssignedToClientListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class SharedEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        CityAssignedToClientEvent::class => [
            CityAssignedToClientListener::class,
        ],
    ];
}
