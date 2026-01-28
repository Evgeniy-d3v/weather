<?php

namespace App\TelegramBot\Presentation\Controllers;

use App\TelegramBot\Application\Jobs\HandleTelegramWebHookJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController
{
    public function __construct() {
    }

    /**
     * Обработка входящего вебхука от Telegram
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $payload = $request->all();
        Log::debug('Telegram webhook payload: ' . json_encode($request->all()));
        dispatch(new HandleTelegramWebHookJob($payload));
        return response()->noContent(Response::HTTP_OK);
    }
}

