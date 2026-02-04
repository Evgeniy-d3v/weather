<?php

namespace App\Shared\Domain;

enum CachePrefixEnum: string
{
    case SEND_MESSAGE = 'telegram_bot_send_message';
    case SEND_DAILY_FORECAST_PREFIX = 'send_daily_forecast_report';
    case SEND_HOURLY_FORECAST_PREFIX = 'send_hourly_forecast_report';
    case HANDLE_TELEGRAM_WEBHOOK_UPDATE = 'handle_telegram_webhook';
    case DAILY_FORECAST = 'daily_forecast';
    case HOURLY_FORECAST = 'hourly_forecast';
}
