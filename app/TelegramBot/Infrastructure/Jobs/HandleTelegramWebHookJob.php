<?php

namespace App\TelegramBot\Infrastructure\Jobs;

use App\TelegramBot\Application\UseCase\ProcessIncomingTelegramUpdate;
use App\TelegramBot\Presentation\Mappers\TelegramWebHookMapper;
use Illuminate\Support\Facades\Log;
use Shared\Cache\CacheLocker;
use Shared\Job\AbstractJob;

class HandleTelegramWebHookJob extends AbstractJob
{

    public function __construct(
       public array $payload,
    )
    {
        $this->onQueue('handle_telegram_webhook');

    }

    /**
     * Execute the job.
     */
    public function handle(
        ProcessIncomingTelegramUpdate $processIncomingTelegramUpdate,
        TelegramWebHookMapper $hookMapper,
        CacheLocker $cacheLocker,
    ): void
    {
        //todo (переписать лок отрпавки)
        if (!$cacheLocker->tryLock($this->payload['update_id'], 360)) {
            Log::debug('Duplicate Telegram webhook received with update_id: ' . $this->payload['update_id']);
            return;
        }

        $processIncomingTelegramUpdate->handle(
            $hookMapper->mapWebHook($this->payload)
        );
    }
}
