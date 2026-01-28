<?php

use App\TelegramBot\Presentation\Controllers\TelegramWebAppController;
use App\TelegramBot\Presentation\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/webhook', [WebhookController::class, 'handle']);
Route::get('/telegram/webapp/weather-newsletter-config', [TelegramWebAppController::class, 'showWeatherNewsletterConfig']);

