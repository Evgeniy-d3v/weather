<?php

namespace App\TelegramBot\Presentation\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController
{
    public function __construct(
    ) {
    }

    /**
     * Обработка входящего вебхука от Telegram
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        Log::debug('Telegram webhook payload: ' . json_encode($request->all()));

        return response()->noContent(Response::HTTP_OK);
    }
}

