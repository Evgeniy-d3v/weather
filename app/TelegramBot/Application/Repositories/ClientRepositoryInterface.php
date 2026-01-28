<?php

namespace App\TelegramBot\Application\Repositories;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Infrastructure\Persistence\Model\Client;

interface ClientRepositoryInterface
{
    public function findByChatId(int $chatId): ?Client;
    public function createNewClient(TelegramWebHookDto $dto): void;
    public function addCityToClient(int $clientId, int $cityId): void;
    public function updateClientFromWebAppData(TelegramWebHookDto $dto): void;
}
