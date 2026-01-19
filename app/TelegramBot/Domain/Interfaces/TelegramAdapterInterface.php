<?php

namespace App\TelegramBot\Domain\Interfaces;

use App\TelegramBot\Domain\Entities\Update;

interface TelegramAdapterInterface
{
    /**
     * Получить обновления от Telegram
     *
     * @param int|null $offset Идентификатор первого обновления для получения
     * @param int $limit Максимальное количество обновлений (1-100)
     * @param int $timeout Таймаут в секундах для long polling
     * @return Update[]
     */
    public function getUpdates(?int $offset = null, int $limit = 100, int $timeout = 0): array;

    /**
     * Отправить текстовое сообщение
     *
     * @param int|string $chatId ID чата
     * @param string $text Текст сообщения
     * @param array $options Дополнительные опции (parse_mode, reply_to_message_id и т.д.)
     * @return int ID отправленного сообщения
     */
    public function sendMessage(int|string $chatId, string $text, array $options = []): int;

    /**
     * Отправить сообщение с клавиатурой
     *
     * @param int|string $chatId ID чата
     * @param string $text Текст сообщения
     * @param array $keyboard Массив кнопок клавиатуры
     * @param array $options Дополнительные опции
     * @return int ID отправленного сообщения
     */
    public function sendMessageWithKeyboard(int|string $chatId, string $text, array $keyboard, array $options = []): int;
}

