<?php

namespace App\TelegramBot\Domain\Entities;

class Update
{
    public function __construct(
        public readonly int $updateId,
        public readonly ?Message $message = null,
        public readonly ?Message $editedMessage = null,
        public readonly ?Message $channelPost = null,
        public readonly ?Message $editedChannelPost = null,
    ) {
    }

    public function getMessage(): ?Message
    {
        return $this->message 
            ?? $this->editedMessage 
            ?? $this->channelPost 
            ?? $this->editedChannelPost;
    }

    public function hasMessage(): bool
    {
        return $this->getMessage() !== null;
    }
}

