<?php

namespace App\TelegramBot\Application;

interface TelegramBotApiInterface
{
    public function sendMessage();

    public function setWebhook(string $url): bool;

    public function deleteWebHook(): bool;
}
