<?php

namespace App\TelegramBot\Application\DTO;

final class TelegramWebHookDto
{
    public function __construct(
        public readonly int $updateId

    )
    {
    }
}
