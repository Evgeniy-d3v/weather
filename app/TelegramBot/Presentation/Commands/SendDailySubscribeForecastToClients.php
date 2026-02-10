<?php

namespace App\TelegramBot\Presentation\Commands;

use App\Location\Infrastructure\Job\GetAndSetDailyForecastJob;
use App\Location\Infrastructure\Job\GetAndSetHourlyForecastJob;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\ClientEntity;
use App\TelegramBot\Domain\Entities\ClientEntityCollection;
use App\TelegramBot\Infrastructure\Jobs\SendForecastWhenReadyJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDailySubscribeForecastToClients extends Command
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
    ) {
        parent::__construct();
    }

    protected $signature = 'telegram:send-forecast-to-client ';

    protected $description = 'Рассылка прогнозов погоды по подписке';

    public function handle(): void
    {

        /** @var ClientEntityCollection $clientEntityCollection */
        foreach ($this->clientRepository->getYieldedClient(1000) as $clientEntityCollection) {
            $dispatchedCityIds = [];
            /** @var ClientEntity $clientEntity */
            foreach ($clientEntityCollection as $clientEntity) {
                if (! $clientEntity->hasCity()) {
                    continue;
                }
                if (! $this->checkTime($clientEntity->getSentTime(), $clientEntity->getTimeZone())) {
                    continue;
                }
                $clientTodayDailyForecast = $clientEntity->getTodayForecast()['daily'];
                if ($clientTodayDailyForecast === null) {
                    if (! isset($dispatchedCityIds[$clientEntity->getCityId()])) {
                        GetAndSetDailyForecastJob::dispatch($clientEntity->getCityId());
                        GetAndSetHourlyForecastJob::dispatch($clientEntity->getCityId());
                        $dispatchedCityIds[$clientEntity->getCityId()] = true;
                    }
                    SendForecastWhenReadyJob::dispatch($clientEntity->getId())->delay(now()->addMinutes(2));

                    continue;
                }
                SendForecastWhenReadyJob::dispatch($clientEntity->getId());
            }
        }
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
