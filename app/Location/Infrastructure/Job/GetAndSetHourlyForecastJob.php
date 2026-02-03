<?php

namespace App\Location\Infrastructure\Job;

use App\Location\Application\UseCase\WeatherHandler;
use App\Shared\Infrastructure\Cache\CacheLocker;
use App\Shared\Infrastructure\Job\AbstractJob;
use Illuminate\Support\Facades\Log;

class GetAndSetHourlyForecastJob extends AbstractJob
{
    public function __construct(
        public int $cityId,
    ) {
        $this->onQueue('hourly_forecast');
    }

    public function handle(
        WeatherHandler $weatherHandler,
        CacheLocker $cacheLocker,
    ): void {
        if (! $cacheLocker->tryLock('hourly_forecast '.$this->cityId, 30)) {
            Log::debug('Duplicate GetAndSetHourlyForecastJob for cityId: '.$this->cityId);

            return;
        }
        $weatherHandler->getAndSetHourlyForecast($this->cityId);

    }
}
