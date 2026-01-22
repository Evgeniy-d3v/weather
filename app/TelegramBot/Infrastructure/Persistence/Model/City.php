<?php

namespace App\TelegramBot\Infrastructure\Persistence\Model;

use App\Weather\Infrastructure\Persistence\Model\WeatherForecast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * {@inheritDoc}
 *
 * @property int $id
 * @property string $city_name
 * @property int $time_zone
 * @property array $coordinates
 * @property int $city_weather_forecast
 * @property Client[] $clients
 * @property WeatherForecast[] $weatherForecasts
 */
class City extends Model
{
    protected $table = 'cities';

    protected $casts = [
        'city_name' => 'string',
        'time_zone' => 'integer',
        'coordinates' => 'array',
        'city_weather_forecast' => 'integer'
    ];
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'city_id');
    }
    public function weatherForecasts(): HasMany
    {
        return $this->hasMany(WeatherForecast::class, 'city_id', 'id');
    }
}
