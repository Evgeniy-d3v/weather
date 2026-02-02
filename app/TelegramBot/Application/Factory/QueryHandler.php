<?php

namespace App\TelegramBot\Application\Factory;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\JobDispatcherInterface;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\InlineKeyboard;
use App\TelegramBot\Domain\Entities\MessageTextEnum;

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
            return new TelegramSendMessageDto(
                $dto->chatId,
                MessageTextEnum::FIRST_MESSAGE->value,
                InlineKeyboard::subscriptionMenu()
            );
        }
        switch ($dto->text) {
            case 'subscribe':
                $this->clientRepository->saveSubscribe($client->getId());

                return new TelegramSendMessageDto(
                    $dto->chatId,
                    MessageTextEnum::SUBSCRIBE_MESSAGE->value
                );
            case 'unsubscribe':
                return new TelegramSendMessageDto(
                    $dto->chatId,
                    MessageTextEnum::UNSUBSCRIBE_MESSAGE->value
                );
            case 'change_city':
                return new TelegramSendMessageDto(
                    $dto->chatId,
                    MessageTextEnum::CHANGE_CITY_MESSAGE->value
                );
            case 'get_current_weather':
                $this->dispatcher->dispatchSendCurrentWeatherJob($client->getId());

                return new TelegramSendMessageDto(
                    $dto->chatId,
                    MessageTextEnum::GET_CURRENT_WEATHER_FORECAST->value
                );
            case 'change_days':
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
