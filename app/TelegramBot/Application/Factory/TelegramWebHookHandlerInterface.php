<?php

namespace App\TelegramBot\Application\Factory;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;

interface TelegramWebHookHandlerInterface
{
    public function createResponse(TelegramWebHookDto $dto): TelegramSendMessageDto;
}
