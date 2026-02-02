<?php

namespace App\Location\Infrastructure\Providers;

use App\Location\Infrastructure\Persistence\Event\CityCreatedEvent;
use App\Location\Infrastructure\Persistence\Listeners\CityCreatedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class LocationEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        CityCreatedEvent::class => [
            CityCreatedListener::class,
        ],
    ];
}
