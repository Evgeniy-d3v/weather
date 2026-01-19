<?php

namespace App\TelegramBot\Domain\Entities;

class Chat
{
    public function __construct(
        public readonly int $id,
        public readonly string $type, // 'private', 'group', 'supergroup', 'channel'
        public readonly ?string $title = null,
        public readonly ?string $username = null,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
    ) {
    }

    public function isPrivate(): bool
    {
        return $this->type === 'private';
    }

    public function isGroup(): bool
    {
        return in_array($this->type, ['group', 'supergroup']);
    }

    public function isChannel(): bool
    {
        return $this->type === 'channel';
    }

    public function getDisplayName(): string
    {
        if ($this->title) {
            return $this->title;
        }
        
        if ($this->username) {
            return '@' . $this->username;
        }
        
        $name = $this->firstName ?? '';
        if ($this->lastName) {
            $name .= ' ' . $this->lastName;
        }
        
        return trim($name) ?: "Chat #{$this->id}";
    }
}

