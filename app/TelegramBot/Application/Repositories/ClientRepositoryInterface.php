<?php

namespace App\TelegramBot\Application\Repositories;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Domain\Entities\ClientEntity;
use Generator;

interface ClientRepositoryInterface
{
    public function getYieldedClient(int $packSize): Generator;

    public function findByChatId(int $chatId): ?ClientEntity;

    public function getClient(int $id): ?ClientEntity;

    public function createNewClient(TelegramWebHookDto $dto): void;

    public function addCityToClient(int $clientId, int $cityId): void;

    public function updateClientFromWebAppData(TelegramWebHookDto $dto): void;

    public function saveSubscribe(int $clientId): void;

    public function deleteCity(int $clientId): void;

    public function deleteClient(int $clientId): void;
}
