<?php

namespace App\TelegramBot\Application\Factory;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\JobDispatcherInterface;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\InlineKeyboard;
use App\TelegramBot\Domain\Entities\MessageTextEnum;
use App\TelegramBot\Domain\Entities\QueryCommandEnum;

class QueryHandler implements TelegramWebHookHandlerInterface
{
    public function __construct(
        public ClientRepositoryInterface $clientRepository,
        public JobDispatcherInterface $dispatcher
    ) {}

    public function createResponse(TelegramWebHookDto $dto): TelegramSendMessageDto
    {
        $client = $this->clientRepository->findByChatId($dto->chatId);
        if ($client === null) {
            $this->clientRepository->createNewClient($dto);

            return new TelegramSendMessageDto(
                $dto->chatId,
                MessageTextEnum::FIRST_MESSAGE->value,
                InlineKeyboard::subscriptionMenu()
            );
        }
        switch ($dto->text) {
            case QueryCommandEnum::SUBSCRIBE->value:
                $this->clientRepository->saveSubscribe($client->getId());

                return new TelegramSendMessageDto(
                    $dto->chatId,
                    MessageTextEnum::SUBSCRIBE_MESSAGE->value
                );
            case QueryCommandEnum::UNSUBSCRIBE->value:
                $this->clientRepository->deleteClient($client->getId());

                return new TelegramSendMessageDto(
                    $dto->chatId,
                    MessageTextEnum::UNSUBSCRIBE_MESSAGE->value
                );
            case QueryCommandEnum::CHANGE_CITY->value:
                $this->clientRepository->deleteCity($client->getId());

                return new TelegramSendMessageDto(
                    $dto->chatId,
                    MessageTextEnum::CHANGE_CITY_MESSAGE->value
                );
            case QueryCommandEnum::CURRENT_WEATHER->value:
                $this->dispatcher->dispatchSendCurrentWeatherJob($client->getId());

                return new TelegramSendMessageDto(
                    $dto->chatId,
                    MessageTextEnum::GET_CURRENT_WEATHER_FORECAST->value
                );
            case QueryCommandEnum::CHANGE_CONFIG->value:
                return new TelegramSendMessageDto(
                    $dto->chatId,
                    MessageTextEnum::CONFIGURE_NEWS_LETTER_MESSAGE->value,
                    InlineKeyboard::subscribeWeatherNewsLetterConfig()
                );
            default:
                return new TelegramSendMessageDto(
                    $dto->chatId,
                    MessageTextEnum::REMIND_SUBSCRIPTION_MESSAGE->value,
                    InlineKeyboard::subscriptionMenu()
                );

        }
    }
}
