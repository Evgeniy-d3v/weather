<?php

namespace App\Location\Infrastructure\Persistence\Event;

use App\Location\Infrastructure\Persistence\Model\City;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public City $city
    ) {}
}
