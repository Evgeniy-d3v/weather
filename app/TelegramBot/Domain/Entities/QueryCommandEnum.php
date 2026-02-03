<?php

namespace App\TelegramBot\Domain\Entities;

enum QueryCommandEnum: string
{
    case SUBSCRIBE = 'subscribe';
    case UNSUBSCRIBE = 'unsubscribe';
    case CHANGE_CITY = 'change_city';
    case CURRENT_WEATHER = 'get_current_weather';
    case CHANGE_CONFIG = 'change_days';

}
