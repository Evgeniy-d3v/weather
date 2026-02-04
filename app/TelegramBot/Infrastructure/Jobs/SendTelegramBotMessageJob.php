<?php

namespace App\TelegramBot\Infrastructure\Jobs;

use App\Shared\Domain\CachePrefixEnum;
use App\Shared\Infrastructure\Cache\CacheLocker;
use App\Shared\Infrastructure\Job\AbstractJob;
use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\TelegramBotApiInterface;
use Illuminate\Support\Facades\Log;

class SendTelegramBotMessageJob extends AbstractJob
{
    public function __construct(
        public TelegramSendMessageDto $telegramSendMessageDto,
    ) {
        $this->onQueue('send_telegram_message');
    }

    public function handle(
        TelegramBotApiInterface $botApi,
        CacheLocker $cacheLocker,
    ): void {
        if (! $cacheLocker->tryLock(
            CachePrefixEnum::SEND_MESSAGE->value,
            60,
            CachePrefixEnum::SEND_MESSAGE->value,
            $this->telegramSendMessageDto->chatId,
            $this->telegramSendMessageDto->text
        )
        ) {
            Log::debug('Duplicate sendTelegramBotMessageJob with data: '.json_encode($this->telegramSendMessageDto));

            return;
        }

        $botApi->sendMessage($this->telegramSendMessageDto);

    }
}
