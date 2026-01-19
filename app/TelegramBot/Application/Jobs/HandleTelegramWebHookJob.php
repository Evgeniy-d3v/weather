<?php

namespace App\TelegramBot\Application\Jobs;

use App\TelegramBot\Presentation\Mappers\TelegramWebHookMapper;
use Cache\CacheLocker;
use Illuminate\Support\Facades\Log;
use Job;

class HandleTelegramWebHookJob extends Job
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
        TelegramWebHookMapper $hookMapper,
        CacheLocker $cacheLocker,
    ): void
    {
        if (!$cacheLocker->tryLock($this->payload['update_id'], 360)) {
            Log::debug('Duplicate Telegram webhook received with update_id: ' . $this->payload['update_id']);
            return;
        }

        $webHookDto = $hookMapper->mapWebHook($this->payload);

    }
}
