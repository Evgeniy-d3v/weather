<?php

namespace App\TelegramBot\Application\Repositories;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Domain\Entities\ClientEntity;
use App\TelegramBot\Infrastructure\Persistence\Model\Client;
use Illuminate\Database\Eloquent\Collection;

interface ClientRepositoryInterface
{
    public function findByChatId(int $chatId): ?ClientEntity;

    //todo переписать на домен клиента
    public function getAllClientWithLastForecast(): Collection;
    public function createNewClient(TelegramWebHookDto $dto): void;
    public function addCityToClient(int $clientId, int $cityId): void;
    public function updateClientFromWebAppData(TelegramWebHookDto $dto): void;
    public function saveSubscribe(int $clientId): void;
    public function deleteCity(int $clientId): void;
    public function deleteClient(int $clientId): void;
}
