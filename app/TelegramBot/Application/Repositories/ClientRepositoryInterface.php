<?php

namespace App\TelegramBot\Application\Repositories;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Domain\Entities\ClientEntity;
use Illuminate\Database\Eloquent\Builder;

interface ClientRepositoryInterface
{
    public function findByChatId(int $chatId): ?ClientEntity;

    public function getClient(int $id): ?ClientEntity;

    public function getAllClientWithLastForecast(): Builder;

    public function createNewClient(TelegramWebHookDto $dto): void;

    public function addCityToClient(int $clientId, int $cityId): void;

    public function updateClientFromWebAppData(TelegramWebHookDto $dto): void;

    public function saveSubscribe(int $clientId): void;

    public function deleteCity(int $clientId): void;

    public function deleteClient(int $clientId): void;
}
