<?php

namespace App\TelegramBot\Infrastructure\Persistence\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * {@inheritDoc}
 *
 * @property int $id
 * @property int $chat_id
 * @property string|null $user_full_name
 * @property string $city
 * @property bool $is_subscribed
 * @property int $user_time_zone
 * @property array $sent_time
 */
class Client extends Model
{
    protected $table = 'Clients';

    protected $casts = [
        'chat_id' => 'integer',
        'user_full_name' => 'string',
        'user_username' => 'string',
        'city' => 'string',
        'is_subscribed' => 'boolean',
        'user_time_zone' => 'integer',
        'sent_time' => 'array',
    ];
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
