<?php

namespace App\TelegramBot\Infrastructure\Persistence\Listener;

use App\Shared\Infrastructure\Events\CityAssignedToClientEvent;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;

class CityAssignedToClientListener
{
    public function __construct(
        public ClientRepositoryInterface $clientRepository
    ) {}

    public function handle(CityAssignedToClientEvent $event): void
    {

        $this->clientRepository->addCityToClient($event->clientId, $event->cityId);

    }
}
