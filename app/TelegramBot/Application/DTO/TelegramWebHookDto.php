<?php

namespace App\TelegramBot\Application\DTO;

final readonly class TelegramWebHookDto
{
    public function __construct(
        public bool $isQuery,
        public int    $chatId,
        public string $userFullName,
        public ?string $username,
        public ?string $text,
        public ?string $webAppData,
    )
    {}
}
