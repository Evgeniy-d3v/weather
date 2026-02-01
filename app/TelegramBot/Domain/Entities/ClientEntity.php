<?php

namespace App\TelegramBot\Domain\Entities;

final class ClientEntity
{
    public function __construct(
        private int $id,
        private int $chatId,
        private ?string $userFullName,
        private ?string $username,
        private bool $isSubscribed,
        private ?int $cityId,
        private ?array $sentTime,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getUserFullName(): ?string
    {
        return $this->userFullName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function isSubscribed(): bool
    {
        return $this->isSubscribed;
    }

    public function hasCity(): bool
    {
        return $this->cityId !== null;
    }

    public function getCityId(): ?int
    {
        return $this->cityId;
    }

    public function getSentTime(): ?array
    {
        return $this->sentTime;
    }

    public function subscribe(): void
    {
        $this->isSubscribed = true;
    }

    public function unsubscribe(): void
    {
        $this->isSubscribed = false;
    }

    public function assignCity(int $cityId): void
    {
        $this->cityId = $cityId;
    }

    public function removeCity(): void
    {
        $this->cityId = null;
    }

    public function updateSentTime(?array $sentTime): void
    {
        $this->sentTime = $sentTime;
    }
}
