<?php

namespace App\TelegramBot\Application\Examples;

use App\TelegramBot\Application\Services\TelegramService;
use App\TelegramBot\Domain\Entities\Update;

/**
 * Пример использования TelegramService
 * 
 * Этот класс демонстрирует, как использовать TelegramService
 * для отправки сообщений и обработки обновлений
 */
class TelegramServiceExample
{
    public function __construct(
        private readonly TelegramService $telegramService
    ) {
    }

    /**
     * Пример обработки обновлений
     */
    public function handleUpdates(): void
    {
        // Получаем обновления с таймаутом 60 секунд (long polling)
        $updates = $this->telegramService->fetchUpdates(60);

        foreach ($updates as $update) {
            $this->processUpdate($update);
        }
    }

    /**
     * Пример обработки одного обновления
     */
    private function processUpdate(Update $update): void
    {
        if (!$update->hasMessage()) {
            return;
        }

        $message = $update->getMessage();

        // Пример обработки команды /start
        if ($message->text === '/start') {
            $this->telegramService->sendMessage(
                $message->chat->id,
                "Привет, {$message->from->getDisplayName()}! Добро пожаловать!"
            );
            return;
        }

        // Пример отправки сообщения с клавиатурой
        if ($message->text === '/menu') {
            $keyboard = [
                [['text' => 'Кнопка 1'], ['text' => 'Кнопка 2']],
                [['text' => 'Кнопка 3']],
            ];

            $this->telegramService->sendMessageWithKeyboard(
                $message->chat->id,
                'Выберите опцию:',
                $keyboard
            );
            return;
        }

        // Эхо-ответ на любое сообщение
        $this->telegramService->sendMessage(
            $message->chat->id,
            "Вы написали: {$message->text}",
            ['reply_to_message_id' => $message->messageId]
        );
    }

    /**
     * Пример отправки простого сообщения
     */
    public function sendSimpleMessage(int|string $chatId, string $text): void
    {
        $this->telegramService->sendMessage($chatId, $text);
    }

    /**
     * Пример отправки сообщения с форматированием
     */
    public function sendFormattedMessage(int|string $chatId, string $text): void
    {
        $this->telegramService->sendMessage($chatId, $text, [
            'parse_mode' => 'HTML',
        ]);
    }
}

