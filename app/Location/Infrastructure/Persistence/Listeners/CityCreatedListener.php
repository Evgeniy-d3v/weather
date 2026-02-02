<?php

namespace App\Location\Infrastructure\Persistence\Listeners;

use App\Location\Infrastructure\Job\GetAndSetDailyForecastJob;
use App\Location\Infrastructure\Job\GetAndSetHourlyForecastJob;
use App\Location\Infrastructure\Persistence\Event\CityCreatedEvent;

class CityCreatedListener
{
    public function handle(CityCreatedEvent $event): void
    {

        GetAndSetDailyForecastJob::dispatch($event->city->id);
        GetAndSetHourlyForecastJob::dispatch($event->city->id);

    }
}
