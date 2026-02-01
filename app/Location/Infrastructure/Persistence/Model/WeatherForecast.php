<?php

namespace App\Location\Infrastructure\Persistence\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * {@inheritDoc}
 *
 * @property int $id
 * @property int $city_id
 * @property City $city
 * @property Carbon $day
 * @property array $daily_forecast
 * @property array $hourly_forecast
 */
class WeatherForecast extends Model
{
    protected $table = 'weather_forecasts';

    protected $fillable = [
        'city_id',
        'day',
        'daily_forecast',
        'hourly_forecast',
    ];
    protected $casts = [
        'city_id' => 'integer',
        'day' => 'date:Y-m-d',
        'daily_forecast' => 'array',
        'hourly_forecast' => 'array',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

}
