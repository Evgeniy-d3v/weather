<?php

namespace App\Location\Infrastructure\Persistence\Model;

use App\TelegramBot\Infrastructure\Persistence\Model\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * {@inheritDoc}
 *
 * @property int $id
 * @property string $city_name
 * @property string $time_zone
 * @property float $latitude
 * @property float $longitude
 * @property int $city_weather_forecast
 * @property Client[] $clients
 * @property WeatherForecast[] $weather_forecasts
 */
class City extends Model
{
    protected $table = 'cities';

    protected $casts = [
        'city_name' => 'string',
        'time_zone' => 'string',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'city_id');
    }
    public function weatherForecasts(): HasMany
    {
        return $this->hasMany(WeatherForecast::class, 'city_id', 'id');
    }

    public function todayForecast(): HasOne
    {
        $tz = $this->time_zone;
        $cityDay = Carbon::now($tz)->toDateString();

        return $this->hasOne(WeatherForecast::class, 'city_id', 'id')
            ->whereDate('day', $cityDay);
    }
}
