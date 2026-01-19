<?php

namespace App\TelegramBot\Domain\Entities;

class Message
{
    public function __construct(
        public readonly int $messageId,
        public readonly User $from,
        public readonly Chat $chat,
        public readonly int $date,
        public readonly ?string $text = null,
        public readonly ?int $replyToMessageId = null,
    ) {
    }

    public function hasText(): bool
    {
        return $this->text !== null && $this->text !== '';
    }

    public function isReply(): bool
    {
        return $this->replyToMessageId !== null;
    }
}

