<?php

namespace App\TelegramBot\Domain\Entities;

enum CachePrefixEnum:string
{
    case SEND_MESSAGE = 'telegram_bot_send_message';
}
