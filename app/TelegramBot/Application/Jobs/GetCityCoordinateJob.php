<?php

namespace App\TelegramBot\Application\Jobs;

use App\GeoDecoder\Application\UseCase\CityHandler;
use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Domain\Entities\InlineKeyboard;
use App\TelegramBot\Domain\Entities\MessageTextEnum;
use Illuminate\Support\Facades\Log;
use Shared\Cache\CacheLocker;
use Shared\Job\AbstractJob;

class GetCityCoordinateJob extends AbstractJob
{
    public function __construct(
        public string $cityName,
        public int $clientId,
        public int $chatId,
    )
    {
        $this->onQueue('get_city_coordinate');

    }

    /**
     * Execute the job.
     */
    public function handle(
        CityHandler $cityHandler,
        CacheLocker $cacheLocker,
    ): void
    {
        //todo (переписать лок отрпавки)
//        if (!$cacheLocker->tryLock($this->payload['update_id'], 360)) {
//            Log::debug('Duplicate Telegram webhook received with update_id: ' . $this->payload['update_id']);
//            return;
//        }
        try {
            $cityHandler->createCity($this->cityName, $this->clientId);
            SendTelegramBotMessageJob::dispatch(new TelegramSendMessageDto(
                chatId: $this->chatId,
                text: MessageTextEnum::CITY_FOUND->value,
                replyMarkup:InlineKeyboard::subscribeWeatherNewsLetterConfig()
            ));
        } catch (\Exception $e) {
            Log::debug('GetCityCoordinateJob exception: ' . $e->getMessage());
            SendTelegramBotMessageJob::dispatch(new TelegramSendMessageDto(
                chatId: $this->chatId,
                text: MessageTextEnum::GET_CITY_INFO_EXCEPTION->value,
            ));
        }

    }
}
