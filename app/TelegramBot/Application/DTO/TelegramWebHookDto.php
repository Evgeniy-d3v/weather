<?php

namespace App\TelegramBot\Application\DTO;

final readonly class TelegramWebHookDto
{
    //Telegram webhook payload: {"update_id":135318098,"message":{"message_id":2,"from":{"id":1068910688,"is_bot":false,"first_name":"\u0415\u0432\u0433\u0435\u043d\u0438\u0439","username":"T4ke1t","language_code":"ru"},"chat":{"id":1068910688,"first_name":"\u0415\u0432\u0433\u0435\u043d\u0438\u0439","username":"T4ke1t","type":"private"},"date":1768827170,"text":"ss"}}

    public function __construct(
        public int    $chatId,
        public string $userFullName,
        public string $username,
        public string $text,
    )
    {}
}
