<?php

namespace App\TelegramBot\Infrastructure\Adapters;

use App\TelegramBot\Application\TelegramBotApiInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramBotApiAdapter implements TelegramBotApiInterface
{
    public function __construct(
        public Api $apiBot
    )
    {}

    public function sendMessage()
    {

    }

    /**
     * @throws TelegramSDKException
     */
    public function setWebhook(string $url): bool
    {
        return $this->apiBot->setWebhook(['url'=> $url]);
    }

    /**
     * @throws TelegramSDKException
     */
    public function deleteWebHook(): bool
    {
        return $this->apiBot->deleteWebhook();
    }
}
