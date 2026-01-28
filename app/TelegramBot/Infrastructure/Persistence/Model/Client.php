<?php

namespace App\TelegramBot\Infrastructure\Persistence\Model;

use App\GeoDecoder\Infrastructure\Persistence\Model\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * {@inheritDoc}
 *
 * @property int $id
 * @property int $chat_id
 * @property string|null $user_full_name
 * @property string|null $user_username
 * @property int $city_id
 * @property bool $is_subscribed
 * @property array $sent_time
 * @property City $city
 */
class Client extends Model
{
    protected $table = 'clients';

    protected $casts = [
        'chat_id' => 'integer',
        'user_full_name' => 'string',
        'user_username' => 'string',
        'city_id' => 'integer',
        'is_subscribed' => 'boolean',
        'sent_time' => 'array',
    ];
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
