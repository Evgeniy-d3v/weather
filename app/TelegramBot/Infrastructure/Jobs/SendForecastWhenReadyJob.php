<?php

namespace App\TelegramBot\Infrastructure\Jobs;

use App\Location\Application\Repositories\CityRepositoryInterface;
use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Infrastructure\Jobs\SendTelegramBotMessageJob;
use Illuminate\Support\Facades\Log;
use Shared\Job\AbstractJob;

class SendForecastWhenReadyJob extends AbstractJob
{
    public function __construct(
        public int $clientId
    )
    {
        $this->onQueue('send_forecast_report');
    }

    public function handle(
        ClientRepositoryInterface $clientRepository,
        CityRepositoryInterface $cityRepository
    ): void
    {
        $client = $clientRepository->getClient($this->clientId);
        
        if ($client === null || !$client->hasCity()) {
            Log::warning("Client {$this->clientId} has no city");
            return;
        }

        $city = $cityRepository->getCityById($client->getCityId());
        
        // Получаем прогноз из БД через модель (так как нужны связи)
        $cityModel = \App\Location\Infrastructure\Persistence\Model\City::find($city->getId());
        if ($cityModel === null) {
            Log::warning("City {$city->getId()} not found");
            return;
        }

        $forecastModel = $cityModel->todayForecast()->first();
        if ($forecastModel === null || !isset($forecastModel->daily_forecast)) {
            Log::warning("No daily forecast for city {$city->getId()}");
            return;
        }

        $forecast = $forecastModel->daily_forecast;

        $message = mb_convert_encoding(
            view(
                'weather_report_daily',
                [
                    'city' => $city->getCityName(),
                    'windSpeedMax' => $forecast['wind_speed_max'],
                    'temperatureMax' => $forecast['temperature_max'],
                    'temperatureMin' => $forecast['temperature_min'],
                    'temperatureFeelsLikeMax' =>$forecast['apparent_temperature_max'],
                    'temperatureFeelsLikeMin' => $forecast['apparent_temperature_min'],
                    'precipitation' => $forecast['precipitation_sum'],
                    'weatherCondition' => $forecast['weather_condition']
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
