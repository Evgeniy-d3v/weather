<?php

namespace App\TelegramBot\Domain\Entities;

class User
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $firstName,
        public readonly ?string $lastName = null,
        public readonly ?string $username = null,
        public readonly ?string $languageCode = null,
        public readonly ?bool $isBot = false,
    ) {
    }

    public function getFullName(): string
    {
        $name = $this->firstName ?? '';
        if ($this->lastName) {
            $name .= ' ' . $this->lastName;
        }
        return trim($name);
    }

    public function getDisplayName(): string
    {
        return $this->username ?? $this->getFullName() ?? "Client #{$this->id}";
    }
}

