<?php

namespace App\TelegramBot\Infrastructure\Persistence\Repositories;

use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Infrastructure\Persistence\Model\Client;

class ClientRepository implements ClientRepositoryInterface
{

    public function findByChatId(int $chatId): ?Client
    {
        return Client::query()
            ->where('chat_id', $chatId)
            ->first();
    }

    public function createNewClient(object $dto): Client
    {
        // TODO: Implement createNewClient() method.
    }
}
