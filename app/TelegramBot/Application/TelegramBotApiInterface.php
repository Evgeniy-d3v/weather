<?php

namespace App\TelegramBot\Application;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;

interface TelegramBotApiInterface
{
    public function sendMessage(TelegramSendMessageDto $dto): void;

    public function setWebhook(string $url): bool;

    public function deleteWebHook(): bool;
}
