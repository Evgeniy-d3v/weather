<?php

namespace App\TelegramBot\Presentation\Commands;

use App\Location\Infrastructure\Job\GetAndSetDailyForecastJob;
use App\Location\Infrastructure\Job\GetAndSetHourlyForecastJob;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Infrastructure\Jobs\SendForecastWhenReadyJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDailySubscribeForecastToClients extends Command
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository
    ) {
        parent::__construct();
    }

    protected $signature = 'telegram:send-forecast-to-client ';

    protected $description = 'Рассылка прогнозов погоды по подписке';

    public function handle(): void
    {
        $dispatchedCityIds = [];

        $this->clientRepository->getAllClientWithLastForecast()
            ->chunkById(1000, function ($clients) use (&$dispatchedCityIds) {
                foreach ($clients as $client) {
                    if (! $client->city) {
                        continue;
                    }

                    if (! $this->checkTime($client->sent_time, $client->city->time_zone)) {
                        continue;
                    }

                    $cityId = $client->city->id;

                    $forecast = $client->city->todayForecast;

                    if ($forecast === null) {
                        if (! isset($dispatchedCityIds[$cityId])) {
                            GetAndSetDailyForecastJob::dispatch($cityId);
                            GetAndSetHourlyForecastJob::dispatch($cityId);
                            $dispatchedCityIds[$cityId] = true;
                        }
                        SendForecastWhenReadyJob::dispatch($client->id)->delay(now()->addMinutes(2));

                        continue;
                    }

                    SendForecastWhenReadyJob::dispatch($client->id);
                }
            });
    }

    private function checkTime(array $sentTime, string $timeZone): bool
    {
        $now = Carbon::now($timeZone);
        $day = (string) $now->isoWeekday();
        $hour = $now->hour;
        $minute = $now->minute;

        if (! isset($sentTime[$day])) {
            return false;
        }

        if ($minute > 5) {
            return false;
        }

        return in_array($hour, $sentTime[$day], true);
    }
}
