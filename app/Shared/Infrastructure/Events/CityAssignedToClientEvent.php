<?php

namespace App\Shared\Infrastructure\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityAssignedToClientEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $cityId,
        public int $clientId,
    ) {}
}
