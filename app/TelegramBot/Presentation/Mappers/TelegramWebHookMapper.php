<?php

namespace App\TelegramBot\Presentation\Mappers;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;

class TelegramWebHookMapper
{
    //Telegram webhook payload: {"update_id":135318098,"message":{"message_id":2,"from":{"id":1068910688,"is_bot":false,"first_name":"\u0415\u0432\u0433\u0435\u043d\u0438\u0439","username":"T4ke1t","language_code":"ru"},"chat":{"id":1068910688,"first_name":"\u0415\u0432\u0433\u0435\u043d\u0438\u0439","username":"T4ke1t","type":"private"},"date":1768827170,"text":"ss"}}
    public function mapWebHook(array $payload): TelegramWebHookDto
    {
        return new TelegramWebHookDto(
            chatId: $payload['message']['chat']['id'] ?? null,
            userFullName: trim(($payload['message']['from']['first_name'] ?? '') . ' ' . (($payload['message']['from']['last_name'] ?? ''))),
            username: $payload['message']['from']['username'] ?? null,
            text: $payload['message']['text'] ?? null,
        );
    }
}
