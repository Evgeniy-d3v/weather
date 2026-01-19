<?php

namespace App\TelegramBot\Application\Repositories;

use App\TelegramBot\Infrastructure\Persistence\Model\Client;

interface ClientRepositoryInterface
{
    public function findByChatId(int $chatId): ?Client;
    public function createNewClient(object $dto): Client;
}
