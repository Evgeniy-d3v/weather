<?php

namespace Tests\Unit\TelegramBot\Application\DataProvider;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Domain\Entities\ClientEntity;
use App\TelegramBot\Domain\Entities\InlineKeyboard;
use App\TelegramBot\Domain\Entities\MessageTextEnum;
use App\TelegramBot\Domain\Entities\QueryCommandEnum;

class TelegramMessageHandlerDataProvider
{
    public static function queryHandlerTestCreateResponseIfClientNotNullClientRepositoryCases(): array
    {
        $telegramWebHookDtoSubscribe = new TelegramWebHookDto(
            true,
            1,
            'fakeUserFullName',
            'fakeUserName',
            QueryCommandEnum::SUBSCRIBE->value,
            null
        );
        $telegramWebHookDtoUnsubscribe = new TelegramWebHookDto(
            true,
            1,
            'fakeUserFullName',
            'fakeUserName',
            QueryCommandEnum::UNSUBSCRIBE->value,
            null
        );
        $telegramWebHookDtoChangeCity = new TelegramWebHookDto(
            true,
            1,
            'fakeUserFullName',
            'fakeUserName',
            QueryCommandEnum::CHANGE_CITY->value,
            null
        );

        $clientEntity = new ClientEntity(
            1,
            1,
            'fakeUserFullName',
            'fakeUserName',
            true,
            1,
            null,
            null,
            null,
        );

        return [
            'subscribe_message' => [
                $telegramWebHookDtoSubscribe,
                $clientEntity,
                'saveSubscribe',
                new TelegramSendMessageDto(
                    $telegramWebHookDtoSubscribe->chatId,
                    MessageTextEnum::SUBSCRIBE_MESSAGE->value,
                    null
                ),
            ],
            'unsubscribe_message' => [
                $telegramWebHookDtoUnsubscribe,
                $clientEntity,
                'deleteClient',
                new TelegramSendMessageDto(
                    $telegramWebHookDtoUnsubscribe->chatId,
                    MessageTextEnum::UNSUBSCRIBE_MESSAGE->value,
                    null
                ),
            ],
            'change_city_message' => [
                $telegramWebHookDtoChangeCity,
                $clientEntity,
                'deleteCity',
                new TelegramSendMessageDto(
                    $telegramWebHookDtoChangeCity->chatId,
                    MessageTextEnum::CHANGE_CITY_MESSAGE->value,
                    null
                ),
            ],
        ];
    }

    public static function queryHandlerTestCreateResponseIfClientNotNullClientDontCallRepoAndDispatcherMethods(): array
    {
        $telegramWebHookDtoChangeConfig = new TelegramWebHookDto(
            true,
            1,
            'fakeUserFullName',
            'fakeUserName',
            QueryCommandEnum::CHANGE_CONFIG->value,
            null
        );
        $telegramWebHookDtoDefault = new TelegramWebHookDto(
            true,
            1,
            'fakeUserFullName',
            'fakeUserName',
            'unknown_text_command',
            null
        );

        $clientEntity = new ClientEntity(
            1,
            1,
            'fakeUserFullName',
            'fakeUserName',
            true,
            1,
            null,
            null,
            null,
        );

        return [
            'change_config' => [
                $telegramWebHookDtoChangeConfig,
                $clientEntity,
                new TelegramSendMessageDto(
                    $telegramWebHookDtoChangeConfig->chatId,
                    MessageTextEnum::CONFIGURE_NEWS_LETTER_MESSAGE->value,
                    InlineKeyboard::subscribeWeatherNewsLetterConfig()
                ),
            ],
            'default_case' => [
                $telegramWebHookDtoDefault,
                $clientEntity,
                new TelegramSendMessageDto(
                    $telegramWebHookDtoDefault->chatId,
                    MessageTextEnum::REMIND_SUBSCRIPTION_MESSAGE->value,
                    InlineKeyboard::subscriptionMenu()
                ),
            ],
        ];
    }
}
