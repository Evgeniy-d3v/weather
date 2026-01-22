<?php

namespace App\Weather\Infrastructure\Persistence\Model;

use App\TelegramBot\Infrastructure\Persistence\Model\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * {@inheritDoc}
 *
 * @property int $id
 * @property string $time
 * @property int $temperature
 * @property int $feels_like
 * @property string $wind_direction
 * @property integer $wind_speed
 * @property string $cloud_cover
 * @property string $weather_condition
 * @property string $weather_description
 * @property string $precipitation
 * @property int $city_id
 * @property City $city
 */
class WeatherForecast extends Model
{
    protected $table = 'weather_forecasts';

    protected $casts = [
        'city_id' => 'integer',
        'forecast_time' => 'string',
        'temperature' => 'integer',
        'feels_like' => 'integer',
        'wind_direction' => 'string',
        'wind_speed' => 'integer',
        'cloud_cover' => 'string',
        'weather_condition' => 'string',
        'weather_description' => 'string',
        'precipitation' => 'string',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
