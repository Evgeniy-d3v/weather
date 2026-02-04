<?php

namespace App\TelegramBot\Infrastructure\Jobs;

use App\Location\Application\Repositories\CityRepositoryInterface;
use App\Location\Application\Repositories\WeatherForecastRepositoryInterface;
use App\Location\Infrastructure\Job\GetAndSetDailyForecastJob;
use App\Location\Infrastructure\Job\GetAndSetHourlyForecastJob;
use App\Shared\Domain\CachePrefixEnum;
use App\Shared\Infrastructure\Cache\CacheLocker;
use App\Shared\Infrastructure\Job\AbstractJob;
use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendCurrentWeatherJob extends AbstractJob
{
    public function __construct(
        public int $clientId,
    ) {
        $this->onQueue('send_current_weather');

    }

    public function handle(
        WeatherForecastRepositoryInterface $weatherForecastRepository,
        ClientRepositoryInterface $clientRepository,
        CityRepositoryInterface $cityRepository,
        CacheLocker $cacheLocker,
    ): void {
        if (! $cacheLocker->tryLock(
            CachePrefixEnum::SEND_HOURLY_FORECAST_PREFIX->value,
            60,
            $this->clientId
        )
        ) {
            Log::debug('Duplicate sendCurrentWeatherJob for client: '.$this->clientId);

            return;
        }
        $client = $clientRepository->getClient($this->clientId);

        $city = $cityRepository->getCityById($client->getCityId());
        $now = Carbon::now($city->getTimeZone());
        $rounded = $now->copy();
        $rounded = $rounded->minute < 30
            ? $rounded->startOfHour()
            : $rounded->addHour()->startOfHour();

        $hourlyForecast = $weatherForecastRepository->getTodayForecast($city->getId())->getHourlyForecast();
        if ($hourlyForecast === null) {
            GetAndSetDailyForecastJob::dispatch($city->getId());
            GetAndSetHourlyForecastJob::dispatch($city->getId());
            $this->release(60);

            return;
        }

        $key = $rounded->format('H:00');

        if (! isset($hourlyForecast[$key])) {
            return;
        }

        $weather = $hourlyForecast[$key];
        $message = mb_convert_encoding(
            view(
                'weather_report_hourly',
                [
                    'time' => $now,
                    'city' => $city->getCityName(),
                    'temperature' => $weather['temperature'],
                    'temperatureFeelsLike' => $weather['apparent_temperature'],
                    'weatherCondition' => $weather['weather_condition'],
                    'precipitation' => $weather['precipitation'],
                ]
            )->render(),
            'UTF-8',
            'UTF-8'
        );

        SendTelegramBotMessageJob::dispatch(
            new TelegramSendMessageDto(
                $client->getChatId(),
                $message,
            )
        );
    }
}
