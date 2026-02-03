<?php

namespace App\Location\Infrastructure\Job;

use App\Location\Application\UseCase\WeatherHandler;
use App\Shared\Infrastructure\Cache\CacheLocker;
use App\Shared\infrastructure\Job\AbstractJob;
use Illuminate\Support\Facades\Log;

class GetAndSetDailyForecastJob extends AbstractJob
{
    public function __construct(
        public int $cityId,
    ) {
        $this->onQueue('daily_forecast');
    }

    public function handle(
        WeatherHandler $weatherHandler,
        CacheLocker $cacheLocker,
    ): void {
        if (! $cacheLocker->tryLock('daily_forecast '.$this->cityId, 30)) {
            Log::debug('Duplicate GetAndSetDailyForecastJob for cityId: '.$this->cityId);

            return;
        }

        $weatherHandler->getAndSetDailyForecast($this->cityId);
    }
}
