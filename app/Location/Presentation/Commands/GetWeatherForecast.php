<?php

namespace App\Location\Presentation\Commands;

use App\Location\Application\Repositories\CityRepositoryInterface;
use App\Location\Infrastructure\Job\GetAndSetDailyForecastJob;
use App\Location\Infrastructure\Job\GetAndSetHourlyForecastJob;
use Illuminate\Console\Command;

class GetWeatherForecast extends Command
{
    public function __construct(
        public CityRepositoryInterface $cityRepository,
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-weather-forecast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить дневной и почасовой прогнозы погоды';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $cities = $this->cityRepository->getAllCitiesWithTodayForecast()->get();
        foreach ($cities as $city) {
            if ($city->todayForecast != null) {
                continue;
            }
            GetAndSetDailyForecastJob::dispatch($city->id);
            GetAndSetHourlyForecastJob::dispatch($city->id);

        }
    }
}
