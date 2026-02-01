<?php

namespace App\TelegramBot\Presentation\Commands;

use App\Location\Infrastructure\Job\GetAndSetDailyForecastJob;
use App\Location\Infrastructure\Job\GetAndSetHourlyForecastJob;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Infrastructure\Jobs\SendForecastWhenReadyJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailySubscribeForecastToClients extends Command
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:send-forecast-to-client ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Рассылка прогнозов погоды по подписке';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $clients = $this->clientRepository->getAllClientWithLastForecast();
        foreach ($clients as $client) {
            if (! $this->checkTime($client->sent_time, $client->city->time_zone)) {
                continue;
            }
            Log::debug('client: '.json_encode($client));

            $forecast = $client->city->today_forecast;
            if ($forecast === null) {
                // todo добавить проверку на город тк у многих клиентов 1 и тот же город что бы не диспатчить 1 и тоже
                GetAndSetDailyForecastJob::dispatch($client->city->id);
                GetAndSetHourlyForecastJob::dispatch($client->city->id);
                SendForecastWhenReadyJob::dispatch($client->id)->delay(now()->addMinutes(2));

                return;
            }
            SendForecastWhenReadyJob::dispatch($client->id);
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

        // окно на 5 минут тк что то может пойти не так, затупи и тд
        if ($minute > 5) {
            return false;
        }

        return in_array($hour, $sentTime[$day], true);
    }
}
