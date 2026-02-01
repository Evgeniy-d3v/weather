<?php

namespace App\TelegramBot\Infrastructure\Jobs;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\TelegramBotApiInterface;
use App\TelegramBot\Domain\Entities\CachePrefixEnum;
use Illuminate\Support\Facades\Log;
use Shared\Cache\CacheKeyFactory;
use Shared\Cache\CacheLocker;
use Shared\Job\AbstractJob;

class SendTelegramBotMessageJob extends AbstractJob
{
    public function __construct(
        public TelegramSendMessageDto $telegramSendMessageDto,
    ) {
        $this->onQueue('send_telegram_message');
    }

    /**
     * Execute the job.
     */
    public function handle(
        TelegramBotApiInterface $botApi,
        CacheLocker $cacheLocker,
        CacheKeyFactory $cacheKeyFactory,
    ): void {
        Log::debug('SendTelegramBotMessageJob');
        // todo (Мб стоит подумать на тем, что пользователь будет жать на кнопку отправки несколько раз и тогда сообщение не отправится)
        $uniqueKey = $cacheKeyFactory->generateUniqKey(
            CachePrefixEnum::SEND_MESSAGE->value,
            $this->telegramSendMessageDto->chatId,
            $this->telegramSendMessageDto->text,
        );
        //        if (!$cacheLocker->tryLock($uniqueKey, 300)) {
        //            Log::debug('Duplicate SendTelegramBotMessageJob: ' . $uniqueKey);
        //            return;
        //        }

        $botApi->sendMessage($this->telegramSendMessageDto);

    }
}
