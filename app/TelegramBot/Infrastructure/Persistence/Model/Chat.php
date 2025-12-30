<?php

namespace App\TelegramBot\Infrastructure\Persistence\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * {@inheritDoc}
 *
 * @property int $id
 * @property int $user_id
 * @property int $telegram_chat_id
 * @property int $last_state
 */

class Chat extends Model
{
    protected $table = 'chats';

    protected $casts = [
        'user_id' => 'integer',
        'channel_id' => 'integer',
        'telegram_chat_id' => 'integer',
        'last_state' => 'integer',
    ];
    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'chat_id');
    }

}
