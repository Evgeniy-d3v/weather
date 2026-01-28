<?php

namespace App\TelegramBot\Presentation\Controllers;
use Illuminate\View\View;

class TelegramWebAppController
{
    public function showWeatherNewsletterConfig(): View
    {
        return view('telegram_web_app.weather_newsletter_config');
    }
}
