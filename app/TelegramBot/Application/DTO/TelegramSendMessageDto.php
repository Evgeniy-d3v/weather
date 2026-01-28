<?php

namespace App\TelegramBot\Application\DTO;

final readonly class TelegramSendMessageDto
{
    public function __construct(
        public int    $chatId,
        public string $text,
        public ?string $replyMarkup = null,
    )
    {}
}
