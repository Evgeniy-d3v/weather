<?php

namespace App\TelegramBot\Infrastructure\Persistence\Repositories;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
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

    public function createNewClient(TelegramWebHookDto $dto): void
    {
        $client = new Client();
        $client->chat_id = $dto->chatId;
        $client->user_full_name = $dto->userFullName;
        $client->user_username = $dto->username;
        $client->save();

    }

    public function addCityToClient(int $clientId, int $cityId): void
    {
        $client = Client::query()
            ->where('id', $clientId)
            ->first();
        $client->city_id = $cityId;
        $client->save();
    }

    public function updateClientFromWebAppData(TelegramWebHookDto $dto): void
    {
        $client = Client::query()
            ->where('chat_id', $dto->chatId)
            ->firstOrFail();

        $data = json_decode($dto->webAppData, true);

        $client->sent_time = $data['schedule'] ?? null;

        $client->save();
    }
}
