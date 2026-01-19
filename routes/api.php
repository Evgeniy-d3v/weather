<?php

use App\TelegramBot\Presentation\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/webhook', [WebhookController::class, 'handle']);

