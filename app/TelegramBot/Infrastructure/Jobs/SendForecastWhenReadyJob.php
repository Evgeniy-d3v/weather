<?php

namespace App\TelegramBot\Infrastructure\Jobs;

use App\Location\Application\Repositories\CityRepositoryInterface;
use App\Location\Application\Repositories\WeatherForecastRepositoryInterface;
use App\Shared\Domain\CachePrefixEnum;
use App\Shared\Infrastructure\Cache\CacheLocker;
use App\Shared\Infrastructure\Job\AbstractJob;
use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SendForecastWhenReadyJob extends AbstractJob
{
    public function __construct(
        public int $clientId
    ) {
        $this->onQueue('send_forecast_report');
    }

    public function handle(
        ClientRepositoryInterface $clientRepository,
        WeatherForecastRepositoryInterface $weatherForecastRepository,
        CityRepositoryInterface $cityRepository,
        CacheLocker $cacheLocker,
    ): void {
        if (! $cacheLocker->tryLock(
            CachePrefixEnum::SEND_DAILY_FORECAST_PREFIX->value,
            60,
            $this->clientId
        )
        ) {
            Log::debug('Duplicate sendForecastWhenReadyJob for client: '.$this->clientId);

            return;
        }
        $client = $clientRepository->getClient($this->clientId);
        $city = $cityRepository->getCityById($client->getCityId());
        $dailyForecast = $weatherForecastRepository->getTodayForecast($city->getId())->getDailyForecast();
        Log::debug('dailyForecast: '.json_encode($dailyForecast));
        if ($dailyForecast === null) {
            Log::warning("dailyForecast {$city->getId()} not found");

            return;
        }
        $message = mb_convert_encoding(
            view(
                'weather_report_daily',
                [
                    'city' => $city->getCityName(),
                    'windSpeedMax' => $dailyForecast['wind_speed_max'],
                    'temperatureMax' => $dailyForecast['temperature_max'],
                    'temperatureMin' => $dailyForecast['temperature_min'],
                    'temperatureFeelsLikeMax' => $dailyForecast['apparent_temperature_max'],
                    'temperatureFeelsLikeMin' => $dailyForecast['apparent_temperature_min'],
                    'precipitation' => $dailyForecast['precipitation_sum'],
                    'weatherCondition' => $dailyForecast['weather_condition'],
                ]
            )->render(),
            'UTF-8',
            'UTF-8'
        );

        SendTelegramBotMessageJob::dispatch(
            new TelegramSendMessageDto(
                $client->getChatId(),
                $message
            )
        );
    }
}
