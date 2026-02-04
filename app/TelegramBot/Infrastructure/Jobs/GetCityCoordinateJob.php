<?php

namespace App\TelegramBot\Infrastructure\Jobs;

use App\Location\Application\UseCase\CityHandler;
use App\Location\Domain\Exceptions\CityNotFoundException;
use App\Location\Domain\Exceptions\InvalidCityNameException;
use App\Shared\Domain\CachePrefixEnum;
use App\Shared\Infrastructure\Cache\CacheLocker;
use App\Shared\Infrastructure\Job\AbstractJob;
use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Domain\Entities\InlineKeyboard;
use App\TelegramBot\Domain\Entities\MessageTextEnum;
use Illuminate\Support\Facades\Log;

class GetCityCoordinateJob extends AbstractJob
{
    public function __construct(
        public string $cityName,
        public int $clientId,
        public int $chatId,
    ) {
        $this->onQueue('get_city_coordinate');

    }

    public function handle(
        CityHandler $cityHandler,
        CacheLocker $cacheLocker,
    ): void {
        if (! $cacheLocker->tryLock(
            CachePrefixEnum::HANDLE_TELEGRAM_WEBHOOK_UPDATE->value,
            60,
            $this->cityName,
            $this->clientId,
            $this->chatId,
        )) {
            Log::debug('Duplicate GetCityCoordinateJob for city: '.$this->cityName.' and user from chat: '.$this->chatId);

            return;
        }

        try {
            $cityHandler->createCity($this->cityName, $this->clientId);
            $this->sendMessage(
                MessageTextEnum::CITY_FOUND->value,
                InlineKeyboard::subscribeWeatherNewsLetterConfig()
            );
        } catch (InvalidCityNameException|CityNotFoundException $e) {
            Log::debug('GetCityCoordinateJob exception: '.$e->getMessage());
            $this->sendMessage(
                MessageTextEnum::EXCEPTION->value.' '.$e->getMessage(),
            );
        }

    }

    private function sendMessage(string $text, ?string $replyMarkup = null): void
    {
        SendTelegramBotMessageJob::dispatch(new TelegramSendMessageDto(
            chatId: $this->chatId,
            text: $text,
            replyMarkup: $replyMarkup
        ));
    }
}
