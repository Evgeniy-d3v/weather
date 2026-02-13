<?php

namespace Tests\Unit\TelegramBot\Application\DataProvider;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Factory\CommonMessageHandler;
use App\TelegramBot\Application\Factory\QueryHandler;
use App\TelegramBot\Application\Factory\WebAppHandler;

class TelegramWebHookFactoryDataProvider
{
    public static function createHandlerDataProvider(): array
    {
        return [
            'create_web_app_handler' => [
                new TelegramWebHookDto(
                    false,
                    1,
                    'fakeUserFullName',
                    'fakeUserName',
                    'fakeText',
                    'fakeWebAppData'
                ),
                WebAppHandler::class,
            ],
            'create_query_handler' => [new TelegramWebHookDto(
                true,
                1,
                'fakeUserFullName',
                'fakeUserName',
                'fakeText',
                null
            ),
                QueryHandler::class],

            'create_common_message_handler' => [new TelegramWebHookDto(
                false,
                1,
                'fakeUserFullName',
                'fakeUserName',
                'fakeText',
                null
            ),
                CommonMessageHandler::class],
        ];
    }
}
