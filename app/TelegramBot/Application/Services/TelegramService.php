<?php

namespace App\TelegramBot\Application\Services;

use App\TelegramBot\Domain\Entities\Update;
use App\TelegramBot\Domain\Interfaces\TelegramAdapterInterface;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private ?int $lastUpdateId = null;

    public function __construct(
        private readonly TelegramAdapterInterface $adapter
    ) {
    }

    /**
     * Получить новые обновления от Telegram
     *
     * @param int $timeout Таймаут для long polling
     * @return Update[]
     */
    public function fetchUpdates(int $timeout = 60): array
    {
        $updates = $this->adapter->getUpdates(
            offset: $this->lastUpdateId ? $this->lastUpdateId + 1 : null,
            limit: 100,
            timeout: $timeout
        );

        if (!empty($updates)) {
            $this->lastUpdateId = end($updates)->updateId;
        }

        return $updates;
    }

    /**
     * Отправить сообщение пользователю
     *
     * @param int|string $chatId ID чата
     * @param string $text Текст сообщения
     * @param array $options Дополнительные опции
     * @return int ID отправленного сообщения
     */
    public function sendMessage(int|string $chatId, string $text, array $options = []): int
    {
        return $this->adapter->sendMessage($chatId, $text, $options);
    }

    /**
     * Отправить сообщение с клавиатурой
     *
     * @param int|string $chatId ID чата
     * @param string $text Текст сообщения
     * @param array $keyboard Массив кнопок
     * @param array $options Дополнительные опции
     * @return int ID отправленного сообщения
     */
    public function sendMessageWithKeyboard(int|string $chatId, string $text, array $keyboard, array $options = []): int
    {
        return $this->adapter->sendMessageWithKeyboard($chatId, $text, $keyboard, $options);
    }

    /**
     * Обработать обновление
     *
     * @param Update $update
     * @return void
     */
    public function handleUpdate(Update $update): void
    {
        if (!$update->hasMessage()) {
            return;
        }

        $message = $update->getMessage();

        if (!$message->hasText()) {
            return;
        }

        Log::info('Received message', [
            'chat_id' => $message->chat->id,
            'user_id' => $message->from->id,
            'text' => $message->text,
        ]);

        // Здесь можно добавить логику обработки сообщений
        // Например, вызов обработчиков команд или диспетчера
    }

    /**
     * Установить последний обработанный update ID
     *
     * @param int|null $updateId
     * @return void
     */
    public function setLastUpdateId(?int $updateId): void
    {
        $this->lastUpdateId = $updateId;
    }

    /**
     * Получить последний обработанный update ID
     *
     * @return int|null
     */
    public function getLastUpdateId(): ?int
    {
        return $this->lastUpdateId;
    }
}

