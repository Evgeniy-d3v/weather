<?php

namespace App\TelegramBot\Infrastructure\Jobs;

use App\Shared\Domain\CachePrefixEnum;
use App\Shared\Infrastructure\Cache\CacheLocker;
use App\Shared\Infrastructure\Job\AbstractJob;
use App\TelegramBot\Application\UseCase\ProcessIncomingTelegramUpdate;
use App\TelegramBot\Presentation\Mappers\TelegramWebHookMapper;
use Illuminate\Support\Facades\Log;

class HandleTelegramWebHookJob extends AbstractJob
{
    public function __construct(
        public array $payload,
    ) {
        $this->onQueue('handle_telegram_webhook');

    }

    public function handle(
        ProcessIncomingTelegramUpdate $processIncomingTelegramUpdate,
        TelegramWebHookMapper $hookMapper,
        CacheLocker $cacheLocker,
    ): void {
        if (! $cacheLocker->tryLock(
            CachePrefixEnum::HANDLE_TELEGRAM_WEBHOOK_UPDATE->value,
            60,
            $this->payload['update_id']
        )) {
            Log::debug('Duplicate Telegram webhook received with update_id: '.$this->payload['update_id']);

            return;
        }

        $processIncomingTelegramUpdate->handle(
            $hookMapper->mapWebHook($this->payload)
        );
    }
}
