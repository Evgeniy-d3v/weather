<?php

namespace App\TelegramBot\Infrastructure\Persistence\Repositories;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\ClientEntity;
use App\TelegramBot\Infrastructure\Persistence\Model\Client;
use Illuminate\Database\Eloquent\Collection;

class ClientRepository implements ClientRepositoryInterface
{
    public function findByChatId(int $chatId): ?ClientEntity
    {
        $client = Client::query()
            ->where('chat_id', $chatId)
            ->first();

        return $this->toDomainEntity($client);
    }

    public function createNewClient(TelegramWebHookDto $dto): void
    {
        $entity = new ClientEntity(
            id: 0,
            chatId: $dto->chatId,
            userFullName: $dto->userFullName,
            username: $dto->username,
            isSubscribed: false,
            cityId: null,
            sentTime: null,
        );
        $this->save($entity);
    }

    public function addCityToClient(int $clientId, int $cityId): void
    {
        $entity = $this->getClient($clientId);
        if ($entity === null) {
            return;
        }
        $entity->assignCity($cityId);
        $this->save($entity);
    }

    public function updateClientFromWebAppData(TelegramWebHookDto $dto): void
    {
        $entity = $this->findByChatId($dto->chatId);
        if ($entity === null) {
            return;
        }

        $data = json_decode($dto->webAppData, true);
        $entity->updateSentTime($data['schedule'] ?? null);

        $this->save($entity);
    }

    public function getAllClientWithLastForecast(): Collection
    {
        return Client::query()
            ->with(['city.todayForecast'])
            ->get();
    }

    public function getClient(int $id): ?ClientEntity
    {
        $model = Client::query()
            ->where('id', $id)
            ->first();

        return $this->toDomainEntity($model);
    }

    public function saveSubscribe(int $clientId): void
    {
        $entity = $this->getClient($clientId);
        if ($entity === null) {
            return;
        }
        $entity->subscribe();
        $this->save($entity);
    }

    public function deleteCity(int $clientId): void
    {
        $entity = $this->getClient($clientId);
        if ($entity === null) {
            return;
        }
        $entity->removeCity();
        $this->save($entity);
    }

    public function deleteClient(int $clientId): void
    {
        Client::query()
            ->where('id', $clientId)
            ->delete();
    }

    private function toDomainEntity(?Client $model): ?ClientEntity
    {
        if ($model === null) {
            return null;
        }

        return new ClientEntity(
            id: $model->id,
            chatId: $model->chat_id,
            userFullName: $model->user_full_name,
            username: $model->user_username,
            isSubscribed: $model->is_subscribed,
            cityId: $model->city_id,
            sentTime: $model->sent_time,
        );
    }

    private function toModel(ClientEntity $entity): Client
    {
        $model = Client::findOrNew($entity->getId());
        $model->chat_id = $entity->getChatId();
        $model->user_full_name = $entity->getUserFullName();
        $model->user_username = $entity->getUsername();
        $model->is_subscribed = $entity->isSubscribed();
        $model->city_id = $entity->getCityId();
        $model->sent_time = $entity->getSentTime();

        return $model;
    }

    private function save(ClientEntity $entity): void
    {
        $model = $this->toModel($entity);
        $model->save();
    }
}
