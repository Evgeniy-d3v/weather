<?php

namespace App\TelegramBot\Presentation\Mappers;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use RuntimeException;

class TelegramWebHookMapper
{
    public function mapWebHook(array $payload): TelegramWebHookDto
    {
        $isQuery  = isset($payload['callback_query']);
        $message  = $payload['message'] ?? null;
        $callback = $payload['callback_query'] ?? null;

        $chatId = $message['chat']['id']
            ?? $callback['message']['chat']['id']
            ?? null;

        if ($chatId === null) {
            throw new RuntimeException('Cannot detect chat_id from Telegram update');
        }

        $from = $message['from'] ?? $callback['from'] ?? [];

        $firstName = (string)($from['first_name'] ?? '');
        $lastName  = (string)($from['last_name'] ?? '');
        $userFullName = trim($firstName . ' ' . $lastName);

        $username = $from['username'] ?? null;

        $text = $message['text']
            ?? $callback['data']
            ?? null;
        $webAppData = is_array($message) ? ($message['web_app_data']['data'] ?? null) : null;
        return new TelegramWebHookDto(
            isQuery: $isQuery,
            chatId: (int) $chatId,
            userFullName: $userFullName,
            username: $username,
            text: $text,
            webAppData: $webAppData,
        );
    }
}
