<?php

namespace App\TelegramBot\Infrastructure\Jobs;

use App\Location\Application\Repositories\CityRepositoryInterface;
use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Shared\Job\AbstractJob;

class SendCurrentWeatherJob extends AbstractJob
{
    public function __construct(
        public int $clientId,
    )
    {
        $this->onQueue('send_current_weather');

    }

    public function handle(
        ClientRepositoryInterface $clientRepository,
        CityRepositoryInterface $cityRepository
    ): void
    {
        Log::debug("SendCurrentWeatherJob");
        $client = $clientRepository->getClient($this->clientId);

        if ($client === null || !$client->hasCity()) {
            Log::warning("Client {$this->clientId} has no city");
            return;
        }

        $city = $cityRepository->getCityById($client->getCityId());
        $now = Carbon::now($city->getTimeZone());
        $rounded = $now->copy();
        $rounded = $rounded->minute < 30
            ? $rounded->startOfHour()
            : $rounded->addHour()->startOfHour();
        //todo отрефакторить
        $cityModel = \App\Location\Infrastructure\Persistence\Model\City::find($city->getId());
        if ($cityModel === null) {
            Log::warning("City {$city->getId()} not found");
            return;
        }

        $forecastModel = $cityModel->todayForecast()->first();
        if ($forecastModel === null || !isset($forecastModel->hourly_forecast)) {
            Log::warning("No hourly forecast for city {$city->getId()}");
            return;
        }

        $hourlyForecast = $forecastModel->hourly_forecast;
        $key = $rounded->format('H:00');

        if (!isset($hourlyForecast[$key])) {
            Log::warning("No forecast data for key {$key}");
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

